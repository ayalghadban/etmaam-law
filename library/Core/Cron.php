<?php
    /**
     * Cron Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Cron.php, v1.00 6/29/2023 9:11 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    
    use Exception;
    use Stripe\Exception\ApiErrorException;
    use Stripe\PaymentIntent;
    use Stripe\Stripe;
    use Wojo\Container\Container;
    use Wojo\Database\Database;
    use Wojo\Date\Date;
    use Wojo\Exception\NotFoundException;
    use Wojo\Language\Language;
    use Stripe\Exception\CardException;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Cron
    {
        /**
         * run
         *
         * @param int $days
         * @return void
         * @throws ApiErrorException
         * @throws \PHPMailer\PHPMailer\Exception
         * @throws NotFoundException
         */
        public static function run(int $days): void
        {
            $data = self::expireMemberships($days);
            self::runStripe();
            self::sendEmails($data);
        }
        
        /**
         * expireMemberships
         *
         * @param int $days
         * @return mixed
         */
        public static function expireMemberships(int $days): mixed
        {
            $lg = Language::$lang;
            $sql = "
            SELECT u.id, CONCAT(u.fname,' ',u.lname) as fullname, u.email, u.mem_expire, m.id AS mid, m.title$lg as title
              FROM `" . User::mTable . '` as u
              LEFT JOIN `' . Membership::mTable . "` AS m ON m.id = u.membership_id
              WHERE u.active = ?
              AND u.membership_id <> 0
              AND u.mem_expire <= DATE_ADD(DATE(NOW()),
              INTERVAL $days DAY)
            ";
            
            $result = Database::Go()->rawQuery($sql, array('y'))->run();
            
            if ($result) {
                $query = 'UPDATE `' . User::mTable . '` SET mem_expire = NULL, membership_id = CASE ';
                $idlist = '';
                foreach ($result as $usr) {
                    $query .= ' WHEN id = ' . $usr->id . ' THEN membership_id = 0';
                    $idlist .= $usr->id . ',';
                }
                $idlist = substr($idlist, 0, -1);
                $query .= '
				  END
				  WHERE id IN (' . $idlist . ')';
                Database::Go()->rawQuery($query)->run();
            }
            
            return $result;
        }
        
        /**
         * runStripe
         *
         * @return void
         * @throws ApiErrorException
         * @throws NotFoundException
         */
        public static function runStripe(): void
        {
            $sql = "
            SELECT u.id AS uid, CONCAT(u.fname,' ',u.lname) as fullname, u.email, cj.amount, cj.id as cid, cj.membership_id, cj.stripe_customer, cj.stripe_pm
              FROM `" . Core::cjTable . '` as cj
              LEFT JOIN `' . User::mTable . '` as u ON u.id = cj.user_id
              WHERE u.active = ?
              AND DATE(cj.renewal) = CURDATE()
            ';
            
            $data = Database::Go()->rawQuery($sql, array('y'))->run();
            
            require_once BASEPATH . '/gateways/stripe/vendor/autoload.php';
            $key = Database::Go()->select(Core::gTable)->where('name', 'stripe', '=')->first()->run();
            Stripe::setApiKey($key->extra);
            
            if ($data) {
                try {
                    foreach ($data as $row) {
                        PaymentIntent::create([
                            'amount' => round($row->amount * 100),
                            'currency' => $key->extra2,
                            'customer' => $row->stripe_customer,
                            'payment_method' => $row->stripe_pm,
                            'off_session' => true,
                            'confirm' => true,
                        ]);
                        
                        // insert transaction
                        $data = array(
                            'txn_id' => time(),
                            'membership_id' => $row->mid,
                            'user_id' => $row->uid,
                            'rate_amount' => $row->price,
                            'total' => $row->amount,
                            'currency' => $key->extra2,
                            'pp' => 'Stripe',
                            'status' => 1,
                        );
                        
                        $last_id = Database::Go()->insert(Membership::pTable, $data)->run();
                        
                        //update user membership
                        $udata = array(
                            'transaction_id' => $last_id,
                            'user_id' => $row->uid,
                            'membership_id' => $row->mid,
                            'expire' => Membership::calculateDays($row->mid),
                            'recurring' => 1,
                            'active' => 1,
                        );
                        
                        Database::Go()->insert(Membership::umTable, $udata)->run();
                        
                        //update user record
                        Database::Go()->update(User::mTable, array('mem_expire' => $udata['expire'], 'membership_id' => $udata['mid']))->where('id', $row->uid, '=')->run();
                        
                        //update cron record
                        Database::Go()->update(Core::cjTable, array('renewal' => $udata['expire']))->where('id', $row->cid, '=')->run();
                    }
                    
                } catch (CardException) {
                }
            }
        }
        
        /**
         * sendEmails
         *
         * @param mixed $data
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public static function sendEmails(mixed $data): void
        {
            if ($data) {
                $sql = array('body' . Language::$lang . ' as body', 'subject' . Language::$lang . ' as subject');
                $tpl = Database::Go()->select(Content::eTable, $sql)->where('typeid', 'memExp', '=')->first()->run();
                
                $core = Container::instance()->get('core');
                
                $mailer = Mailer::sendMail();
                $mailer->Subject = $tpl->subject;
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->isHTML();
                
                foreach ($data as $row) {
                    $html[$row->email] = str_replace(array(
                        '[LOGO]',
                        '[NAME]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[ITEMNAME]',
                        '[EXPIRE]',
                        '[LINK]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $core->plogo,
                        $row->fullname,
                        date('Y'),
                        $core->company,
                        $core->site_name,
                        $row->title,
                        Date::doDate('short_date', $row->mem_expire),
                        SITEURL . 'login',
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL
                    ), $tpl->body);
                    
                    $mailer->Body = $html;
                    $mailer->addAddress($row->email, $row->fullname);
                    
                    try {
                        $mailer->send();
                    } catch (Exception) {
                        $mailer->getSMTPInstance()->reset();
                    }
                    $mailer->clearAddresses();
                    $mailer->clearAttachments();
                }
                unset($row);
            }
        }
    }