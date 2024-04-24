<?php
    /**
     * Stats Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Stats.php, v1.00 4/28/2023 8:53 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Stats;
    
    use Wojo\Core\Membership;
    use Wojo\Core\User;
    use Wojo\Database\Database;
    use Wojo\Date\Date;
    use Wojo\Language\Language;
    use Wojo\Utility\Utility;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Stats
    {
        const sTable = 'stats';
        
        /**
         * exportUsers
         *
         * @return array
         */
        public static function exportUsers(): array
        {
            $lg = Language::$lang;
            $sql = "
            SELECT CONCAT(fname, ' ', lname) as name, u.membership_id, u.mem_expire, u.email, u.newsletter, u.created, m.title$lg as mtitle
              FROM `" . User::mTable . '` as u
              LEFT JOIN ' . Membership::mTable . " as m ON m.id = u.membership_id
              WHERE (TYPE = 'staff' || TYPE = 'editor' || TYPE = 'member')
              ORDER BY u.fname
            ";
            
            $rows = Database::Go()->rawQuery($sql)->run();
            
            $result = array();
            if (is_array($rows)) {
                foreach ($rows as $i => $val) {
                    $result[$i]['name'] = $val->name;
                    $result[$i]['membership'] = $val->membership_id ? $val->mtitle : '-/-';
                    $result[$i]['mem_expire'] = $val->membership_id ? Date::doDate('long_date', $val->mem_expire) : '-/-';
                    $result[$i]['email'] = $val->email;
                    $result[$i]['newsletter'] = $val->newsletter ? Language::$word->YES : Language::$word->NO;
                    $result[$i]['created'] = $val->created;
                }
            }
            
            return $result;
        }
        
        /**
         * userHistory
         *
         * @param int $id
         * @param string $order
         * @return array|false|int|mixed
         */
        public static function userHistory(int $id, string $order = 'activated'): mixed
        {
            $lg = Language::$lang;
            $sql = "
			SELECT um.activated, um.membership_id, um.transaction_id, um.expire, um.recurring, m.price, m.title$lg as title
			  FROM `" . Membership::umTable . '` AS um
			  LEFT JOIN ' . Membership::mTable . " AS m ON m.id = um.membership_id
			  WHERE um.user_id = ?
			  ORDER BY um.$order DESC;
			";
            
            return Database::Go()->rawQuery($sql, array($id))->run() ?: 0;
            
        }
        
        /**
         * userPayments
         *
         * @param int $id
         * @return array|false|int|mixed
         */
        public static function userPayments(int $id): mixed
        {
            $lg = Language::$lang;
            $sql = "
			SELECT p.txn_id, m.title$lg as title, p.rate_amount, p.tax, p.coupon, p.total, p.created, p.status, p.membership_id
			  FROM `" . Membership::pTable . '` AS p
			  LEFT JOIN ' . Membership::mTable . ' AS m ON m.id = p.membership_id
			  WHERE p.user_id =?
			  ORDER BY p.created DESC;
			';
            
            return Database::Go()->rawQuery($sql, array($id))->run() ?: 0;
        }
        
        /**
         * exportUserPayments
         *
         * @param int $id
         * @return int|mixed
         */
        public static function exportUserPayments(int $id): mixed
        {
            $lg = Language::$lang;
            $sql = "
            SELECT p.txn_id, m.title$lg as title, p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.pp, p.created
              FROM `" . Membership::pTable . '` AS p
              LEFT JOIN ' . Membership::mTable . ' AS m ON m.id = p.membership_id
              WHERE p.user_id =?
              ORDER BY p.created DESC;
            ';
            
            return Database::Go()->rawQuery($sql, array($id))->run('array') ?: 0;
        }
        
        /**
         * getUserPaymentsChart
         *
         * @param int $id
         * @return array
         */
        public static function getUserPaymentsChart(int $id): array
        {
            
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = Utility::currencySymbol();
            
            $color = array(
                '#03a9f4',
                '#33BFC1',
                '#ff9800',
                '#e91e63',
            );
            
            $labels = array(
                Language::$word->TRX_SALES,
                Language::$word->TRX_AMOUNT,
                Language::$word->TRX_TAX,
                Language::$word->TRX_COUPON,
            );
            
            for ($i = 1; $i <= 12; $i++) {
                $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
                $reg_data[$i] = array(
                    'month' => date('M', mktime(0, 0, 0, $i)),
                    'sales' => 0,
                    'amount' => 0,
                    'tax' => 0,
                    'coupon' => 0
                );
            }
            
            $sql = '
            SELECT COUNT(id) as sales, SUM(rate_amount) as amount, SUM(tax) as tax, SUM(coupon) as coupon, MONTH(created) as created
              FROM `' . Membership::pTable . '`
              WHERE user_id = ?
              GROUP BY MONTH(created)
            ';
            
            $query = Database::Go()->rawQuery($sql, array($id));
            
            foreach ($query->run() as $result) {
                $reg_data[$result->created] = array(
                    'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                    'sales' => $result->sales,
                    'amount' => $result->amount,
                    'tax' => $result->tax,
                    'coupon' => $result->coupon
                );
            }
            
            foreach ($reg_data as $key => $value) {
                $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
                $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
                $data['data'][$key][Language::$word->TRX_TAX] = $value['tax'];
                $data['data'][$key][Language::$word->TRX_COUPON] = $value['coupon'];
            }
            
            foreach ($labels as $k => $label) {
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
            
            $data['data'] = array_values($data['data']);
            return $data;
        }
        
        /**
         * getMembershipPaymentsChart
         *
         * @param int $id
         * @return array
         */
        public static function getMembershipPaymentsChart(int $id): array
        {
            
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = Utility::currencySymbol();
            
            $color = array(
                '#03a9f4',
                '#33BFC1',
                '#ff9800',
                '#e91e63',
            );
            
            $labels = array(
                Language::$word->TRX_SALES,
                Language::$word->TRX_AMOUNT,
                Language::$word->TRX_TAX,
                Language::$word->TRX_COUPON,
            );
            
            for ($i = 1; $i <= 12; $i++) {
                $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
                $reg_data[$i] = array(
                    'month' => date('M', mktime(0, 0, 0, $i)),
                    'sales' => 0,
                    'amount' => 0,
                    'tax' => 0,
                    'coupon' => 0
                );
            }
            
            $sql = '
            SELECT COUNT(id) as sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, MONTH(created) as created
              FROM `' . Membership::pTable . '`
              WHERE membership_id = ?
              AND status = ?
              GROUP BY MONTH(created)
            ';
            $query = Database::Go()->rawQuery($sql, array($id, 1));
            
            foreach ($query->run() as $result) {
                $reg_data[$result->created] = array(
                    'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                    'sales' => $result->sales,
                    'amount' => $result->amount,
                    'tax' => $result->tax,
                    'coupon' => $result->coupon
                );
            }
            
            foreach ($reg_data as $key => $value) {
                $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
                $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
                $data['data'][$key][Language::$word->TRX_TAX] = $value['tax'];
                $data['data'][$key][Language::$word->TRX_COUPON] = $value['coupon'];
            }
            
            foreach ($labels as $k => $label) {
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
            $data['data'] = array_values($data['data']);
            return $data;
        }
        
        /**
         * exportMembershipPayments
         *
         * @param int $id
         * @return int|mixed
         */
        public static function exportMembershipPayments(int $id): mixed
        {
            $sql = "
            SELECT p.txn_id, CONCAT(u.fname,' ',u.lname) as name, p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.pp, p.created
              FROM `" . Membership::pTable . '` as p
              LEFT JOIN `' . User::mTable . '` as u ON u.id = p.user_id
              WHERE p.membership_id = ?
              AND p.status = ?
              ORDER BY p.created DESC;
            ';
            
            $rows = Database::Go()->rawQuery($sql, array($id, 1))->run();
            $array = json_decode(json_encode($rows), true);
            
            return $array ?: 0;
        }
        
        /**
         * MembershipsExpireMonth
         *
         * @return array|false|int|mixed
         */
        public static function MembershipsExpireMonth(): mixed
        {
            $sql = "
            SELECT COUNT(id) as total, DATE_FORMAT(mem_expire,'%Y-%m-%d') as expires
              FROM `" . User::mTable . '`
              WHERE MONTH(mem_expire) = MONTH(NOW())
              AND YEAR(mem_expire) = YEAR(NOW())
              AND membership_id > 0
              GROUP BY expires
            ';
            
            return Database::Go()->rawQuery($sql)->run();
        }
        
        /**
         * doArraySum
         *
         * @param array|int $array $array
         * @param string $key
         * @return int|string
         */
        public static function doArraySum(array|int $array, string $key): int|string
        {
            if (is_array($array)) {
                return (number_format(array_sum(array_map(function ($item) use ($key) {
                    return $item->$key;
                }, $array)), 2, '.', ''));
            }
            return 0;
        }
    }