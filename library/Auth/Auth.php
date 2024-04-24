<?php
    /**
     * Auth Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Auth.php, v1.00 4/26/2023 3:12 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Auth;
    
    use Wojo\Container\Container;
    use Wojo\Core\Logger;
    use Wojo\Core\Session;
    use Wojo\Core\User;
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Auth
    {
        const cost = 10;
        public static object|bool $userdata;
        public static object|bool $udata;
        public ?int $logged_in = 0;
        public ?int $uid = 0;
        public ?string $username = null;
        public string $sesid;
        public ?string $email = null;
        public ?string $name = null;
        public ?string $fullname = null;
        public ?string $fname = null;
        public ?string $lname = null;
        public ?string $country = null;
        public ?string $avatar = null;
        public ?int $membership_id = 0;
        public ?string $mem_expire;
        public ?string $usertype = null;
        public ?int $userlevel = 0;
        public ?string $lastlogin;
        public ?string $lastip;
        public array|bool $acl = array();
        public array|bool $modaccess = array();
        public array|bool $plugaccess = array();
        
        /**
         * Auth::__construct()
         *
         */
        public function __construct()
        {
            $this->logged_in = $this->loginCheck();
            
            if (!$this->logged_in) {
                $this->username = null;
                $this->sesid = sha1(session_id());
                $this->userlevel = 0;
            }
            
        }
        
        /**
         * is_Admin
         *
         * @return bool
         */
        public function is_Admin(): bool
        {
            return (in_array($this->userlevel, [9, 8, 7]));
            
        }
        
        /**
         * is_User
         *
         * @return bool
         */
        public function is_User(): bool
        {
            return ($this->userlevel == 1 and $this->usertype == 'member');
            
        }
        
        /**
         * loginCheck
         *
         * @return bool
         */
        private function loginCheck(): bool
        {
            if (Session::isExists('CMSPRO_username') and Session::get('CMSPRO_username') != '') {
                $this->uid = Session::get('userid');
                $this->username = Session::get('CMSPRO_username');
                $this->email = Session::get('email');
                $this->fname = Session::get('fname');
                $this->lname = Session::get('lname');
                $this->name = Session::get('fname') . ' ' . Session::get('lname');
                $this->country = ($this->userlevel == 1) ? Session::get('country') : null;
                $this->avatar = Session::get('avatar');
                $this->lastlogin = Session::get('lastlogin');
                $this->lastip = Session::get('lastip');
                $this->sesid = sha1(session_id());
                $this->usertype = Session::get('type');
                $this->userlevel = Session::get('userlevel');
                $this->membership_id = ($this->userlevel == 1) ? Session::get('membership_id') : 0;
                $this->mem_expire = ($this->userlevel == 1) ? Session::get('mem_expire') : null;
                $this->acl = ($this->is_Admin()) ? Session::get('acl') : false;
                self::$userdata = Session::get('userdata');
                self::$udata = $this;
                
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * login
         *
         * @param string $username
         * @param string $password
         * @param bool $auto
         * @return void
         */
        public function login(string $username, string $password, bool $auto = false): void
        {
            $timeleft = null;
            $status = 'n';
            if (!Logger::loginAgain($timeleft)) {
                $minutes = ceil($timeleft / 60);
                Message::$msgs['username'] = str_replace('[MINUTES]', $minutes, Language::$word->LOGIN_R6);
                $json['message'] = str_replace('[MINUTES]', $minutes, Language::$word->LOGIN_R6);
            } else {
                if ($username == '' && $password == '') {
                    Message::$msgs['username'] = Language::$word->LOGIN_R5;
                    $json['message'] = Language::$word->LOGIN_R5;
                } else {
                    $status = $this->checkStatus($username, $password);
                    
                    switch ($status) {
                        case 'e':
                            Message::$msgs['username'] = Language::$word->LOGIN_R1;
                            $json['message'] = Language::$word->LOGIN_R1;
                            Logger::setFailedLogin();
                            break;
                        
                        case 'b':
                            Message::$msgs['username'] = Language::$word->LOGIN_R2;
                            $json['message'] = Language::$word->LOGIN_R2;
                            Logger::setFailedLogin();
                            break;
                        
                        case 'n':
                            Message::$msgs['username'] = Language::$word->LOGIN_R3;
                            $json['message'] = Language::$word->LOGIN_R3;
                            Logger::setFailedLogin();
                            break;
                        
                        case 't':
                            Message::$msgs['username'] = Language::$word->LOGIN_R4;
                            $json['message'] = Language::$word->LOGIN_R4;
                            Logger::setFailedLogin();
                            break;
                    }
                }
            }
            if (count(Message::$msgs) === 0 && $status == 'y') {
                $row = $this->getUserInfo($username);
                $this->uid = Session::set('userid', $row->id);
                $this->username = Session::set('CMSPRO_username', $row->username);
                $this->fullname = Session::set('fullname', $row->fname . ' ' . $row->lname);
                $this->fname = Session::set('fname', $row->fname);
                $this->lname = Session::set('lname', $row->lname);
                $this->email = Session::set('email', $row->email);
                $this->country = Session::set('country', $row->country);
                $this->userlevel = Session::set('userlevel', $row->userlevel);
                $this->usertype = Session::set('type', $row->type);
                $this->membership_id = Session::set('membership_id', $row->membership_id);
                $this->mem_expire = Session::set('mem_expire', $row->mem_expire);
                $this->avatar = Session::set('avatar', $row->avatar);
                $this->lastlogin = Session::set('lastlogin', Database::toDate());
                $this->lastip = Session::set('lastip', Url::getIP());
                
                if (in_array($row->userlevel, array(9, 8, 7))) {
                    $result = self::getAcl($row->type);
                    $privileges = array();
                    for ($i = 0; $i < count($result); $i++) {
                        $privileges[$result[$i]->code] = $result[$i]->active == 1;
                    }
                    $this->acl = Session::set('acl', $privileges);
                    $this->modaccess = Session::set('modaccess', Utility::jSonToArray($row->modaccess));
                    $this->plugaccess = Session::set('plugaccess', Utility::jSonToArray($row->plugaccess));
                }
                
                $data = array(
                    'lastlogin' => Database::toDate(), 'sesid' => $this->sesid, 'lastip' => Validator::sanitize(Url::getIP())
                );
                
                Database::Go()->update(User::mTable, $data)->where('id', $row->id, '=')->run();
                self::$userdata = Session::set('userdata', $row);
                self::setUserCookies(Session::get('CMSPRO_username'), Session::get('fullname'), Session::get('avatar'));
                
                $json['type'] = 'success';
                $json['user'] = $row->type;
                $json['title'] = Language::$word->SUCCESS;
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
            }
            
            if (!$auto) {
                print json_encode($json);
            }
        }
        
        /**
         * checkStatus
         *
         * @param string $username
         * @param string $pass
         * @return string|void
         */
        public function checkStatus(string $username, string $pass)
        {
            $username = Validator::sanitize($username, 'string', 60);
            $pass = Validator::sanitize($pass, 'string', 20);
            
            $row = Database::Go()->select(User::mTable, array('id', 'hash', 'active'))->where('username', $username, '=')->orWhere('email', $username, '=')->first()->run();
            
            if (!$row) {
                return 'e';
            }
            
            $validPass = password_verify($pass, $row->hash);
            
            if (!$validPass) {
                return 'e';
            }
            
            switch ($row->active) {
                case 'b':
                    return 'b';
                    break;
                
                case 'n':
                    return 'n';
                    break;
                
                case 't':
                    return 't';
                    break;
                
                case 'y' and $validPass == true:
                    if (password_needs_rehash($row->hash, PASSWORD_DEFAULT, array('cost' => self::cost))) {
                        $hash = password_hash($pass, PASSWORD_DEFAULT, array('cost' => self::cost));
                        
                        Database::Go()->update(User::mTable, array('hash' => $hash))->where('id', $row->id, '=')->run();
                    }
                    return 'y';
                    break;
            }
        }
        
        /**
         * getUserInfo
         *
         * @param string $username
         * @return mixed
         */
        public function getUserInfo(string $username): mixed
        {
            $username = Validator::sanitize($username, 'string');
            $row = Database::Go()->select(User::mTable)->where('username', $username, '=')->orWhere('email', $username, '=')->first()->run();
            
            return ($row) ? : 0;
        }
        
        /**
         * getAcl
         *
         * @param string $role
         * @return array
         */
        public static function getAcl(string $role): array
        {
            $sql = '
		    SELECT p.code, p.name, p.description, rp.active
		      FROM `' . User::rpTable . '` rp
		      INNER JOIN `' . User::rTable . '` r ON rp.rid = r.id
		      INNER JOIN `' . User::pTable . '` p ON rp.pid = p.id
		      WHERE r.code = ?
		    ';
            
            return Database::Go()->rawQuery($sql, array($role))->run();
        }
        
        /**
         * checkAcl
         *
         * @return bool
         */
        public static function checkAcl(): bool
        {
            $accTypes = func_get_args();
            $auth = Container::instance()->get('auth');
            foreach ($accTypes as $type) {
                $args = explode(',', $type);
                if (in_array($auth->usertype, $args)) {
                    return true;
                }
            }
            
            return false;
        }
        
        /**
         * checkModAcl
         *
         * @param string|null $section
         * @return bool
         */
        public function checkModAcl(string|null $section): bool
        {
            return !($this->usertype != 'owner') || in_array($section, $this->modaccess);
        }
        
        /**
         * checkPlugAcl
         *
         * @param string|null $section
         * @return bool
         */
        public function checkPlugAcl(string|null $section): bool
        {
            return !($this->usertype != 'owner') || in_array($section, $this->plugaccess);
        }
        
        /**
         * hasPrivileges
         *
         * @param string $code
         * @param string $val
         * @return bool
         */
        public static function hasPrivileges(string $code = '', string $val = ''): bool
        {
            $privileges_info = Session::get('acl');
            if (strlen($val) !== 0) {
                if (isset($privileges_info[$code]) && is_array($privileges_info[$code])) {
                    return in_array($val, $privileges_info[$code]);
                } else {
                    return ($privileges_info[$code] == $val);
                }
            } else {
                return isset($privileges_info[$code]) && $privileges_info[$code];
            }
        }
        
        /**
         * checkOwner
         *
         * @return void
         */
        public static function checkOwner(): void
        {
            if (!self::checkAcl('owner')) {
                print Message::msgError(Language::$word->NOACCESS);
                exit;
            }
        }
        
        /**
         * usernameExists
         *
         * @param string $username
         * @return false|int
         */
        public static function usernameExists(string $username): false|int
        {
            $username = Validator::sanitize($username, 'string');
            if (strlen($username) < 4) {
                return 1;
            }
            
            //Username should contain only alphabets, numbers, or underscores.Should be between 4 and 15 characters long
            $valid_uname = '/^[a-zA-Z0-9_]{4,15}$/';
            if (!preg_match($valid_uname, $username)) {
                return 2;
            }
            
            $row = Database::Go()->select(User::mTable, array('username'))->where('username', $username, '=')->first()->run();
            
            return ($row) ? 3 : false;
        }
        
        /**
         * emailExists
         *
         * @param string $email
         * @return mixed
         */
        public static function emailExists(string $email): mixed
        {
            return Database::Go()->select(User::mTable, array('email'))->where('email', $email, '=')->first()->run();
        }
        
        /**
         * setUserCookies
         *
         * @param string $username
         * @param string $name
         * @param string $avatar
         * @return void
         */
        public static function setUserCookies(string $username, string $name, string $avatar): void
        {
            $avatar = strlen($avatar) === 0 ? 'blank.png' : $avatar;
            setcookie('CMSPRO_loginData[0]', $username, strtotime('+30 days'));
            setcookie('CMSPRO_loginData[1]', $name, strtotime('+30 days'));
            setcookie('CMSPRO_loginData[2]', $avatar, strtotime('+30 days'));
        }
        
        /**
         * doHash
         *
         * @param string $password
         * @return string
         */
        public static function doHash(string $password): string
        {
            return password_hash($password, PASSWORD_DEFAULT, array('cost' => self::cost));
            
        }
        
        /**
         * generateToken
         *
         * @param int $length
         * @return string
         */
        public static function generateToken(int $length = 24): string
        {
            return bin2hex(openssl_random_pseudo_bytes($length));
            
        }
        
        /**
         * logout
         *
         * @return void
         */
        public function logout(): void
        {
            Session::endSession();
            $this->logged_in = false;
            $this->username = 'Guest';
            $this->userlevel = 0;
        }
    }