<?php
    /**
     * Url
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Url.php, v1.00 4/25/2023 1:28 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Url;
    
    use Wojo\Core\Core;
    use Wojo\Database\Database;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Url
    {
        
        public static string $basePath = '';
        
        /**
         * Url::Current()
         *
         * @param $dir
         * @return string
         */
        public static function SiteUrl($dir): string
        {
            $path = ($dir) ? '/' . $dir : '';
            $url = preg_replace('#/+#', '/', $_SERVER['HTTP_HOST'] . $path);
            return Url::protocol() . '://' . $url . '/';
        }
        
        /**
         * Url::protocol()
         *
         * @return string
         */
        public static function protocol(): string
        {
            if (isset($_SERVER['HTTPS'])) {
                $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                $protocol = 'https';
            } else {
                $protocol = 'http';
            }
            
            
            return $protocol;
        }
        
        /**
         * Url::redirect()
         *
         * @param mixed $location
         * @return void
         */
        public static function redirect(string $location): void
        {
            if (!headers_sent()) {
                header('Location: ' . $location);
                exit;
            } else {
                echo '<script type="text/javascript">';
            }
            echo 'window.location.href="' . $location . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
            echo '</noscript>';
        }
        
        /**
         * url
         *
         * @param string $path
         * @param string|null $pars
         * @return string
         */
        public static function url(string $path, string|null $pars = ''): string
        {
            $param = ($pars) ? : null;
            
            return SITEURL . str_replace('//', '/', $path) . '/' . $param;
        }
        
        /**
         * ascDesc
         *
         * @param string $var
         * @return string
         */
        public static function ascDesc(string $var): string
        {
            
            if (isset($_GET[$var]) and count(explode('|', $_GET[$var])) == 2) {
                $items = explode('|', $_GET[$var]);
                $order = Validator::sanitize($items[1], 'default', 5);
                $ord = ($order == 'DESC') ? 'up' : 'down';
            } else {
                $ord = 'down';
            }
            return $ord;
        }
        
        /**
         * uri
         *
         * @return string|null
         */
        public static function uri(): ?string
        {
            
            return File::_fixPath(str_replace(self::$basePath, '', $_SERVER['REQUEST_URI']));
        }
        
        
        /**
         * currentUrl
         *
         * @return string
         */
        public static function currentUrl(): string
        {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $host_url = $_SERVER['HTTP_REFERER'];
                $part_url = explode('?', $host_url);
                
                return $part_url[0];
            }
            return '';
        }
        
        
        /**
         * formatMeta
         *
         * @param string $title
         * @param string $company
         * @param string $name
         * @return string
         */
        public static function formatMeta(string $title, string $company, string $name = ''): string
        {
            return $title . ' - ' . (($name) ? $name . ' - ' : null) . $company;
        }
        
        /**
         * query
         *
         * @return string|null
         */
        public static function query(): ?string
        {
            $q = parse_url($_SERVER['REQUEST_URI']);
            
            return isset($q['query']) ? '&' . $q['query'] : null;
        }
        
        /**
         * segment
         *
         * @param array $segment
         * @param int $id
         * @return mixed|void
         */
        public static function segment(array $segment, int $id = 2)
        {
            if (isset($segment[$id])) {
                $action = $segment[$id] ? : false;
                
                if (!$action) {
                    Message::invalid('Action Method' . $action);
                } else {
                    return $action;
                }
            }
        }
        
        /**
         * crumbs
         *
         * @param array $url
         * @param string $pointer
         * @param string $home
         * @return array|string|string[]|null
         */
        public static function crumbs(array $url, string $pointer, string $home): array|string|null
        {
            
            $array_keys = array_keys($url);
            $last_key = end($array_keys);
            reset($url);
            $last_segment = '';
            $breadcrumbs = '';
            
            $only = count($url);
            if ($only == 1) {
                $breadcrumbs = '<div class="active section">' . $home . '</div>';
            } else {
                foreach ($url as $key => $part) {
                    if (is_array($part)) {
                        $last_segment .= '/' . $part[1];
                    } else {
                        $last_segment .= '/' . $part;
                    }
                    
                    if ($key != 0) {
                        $breadcrumbs .= '<span class="divider">' . $pointer . '</span>';
                    }
                    if (is_array($part)) {
                        if ($key == $last_key) {
                            $breadcrumbs .= '<div class="active section">' . ucwords($part[0]) . '</div>';
                        } else {
                            $breadcrumbs .= '<a href="' . SITEURL . '/' . substr($last_segment, 1) . '/" class="section">' . ucwords(str_replace($url[0], $home, $part[0])) . '</a>';
                        }
                    } else {
                        if ($key == $last_key) {
                            $breadcrumbs .= '<div class="active section">' . ucwords($part) . '</div>';
                        } else {
                            $breadcrumbs .= '<a href="' . SITEURL . '/' . substr($last_segment, 1) . '/" class="section">' . ucwords(str_replace($url[0], $home, $part)) . '</a>';
                        }
                    }
                    
                }
            }
            
            return preg_replace('/([^:])(\/{2,})/', '$1/', $breadcrumbs);
        }
        
        /**
         * builderUrl
         *
         * @param string $theme
         * @return string
         */
        public static function builderUrl(string $theme): string
        {
            return (is_dir(BUILDERBASE . '/themes/' . $theme)) ? BUILDERTHEME . $theme : BUILDERTHEME . 'default';
        }
        
        /**
         * isPartSet
         *
         * @param $segment
         * @param $part
         * @param $page
         * @return bool
         */
        public static function isPartSet($segment, $part, $page): bool
        {
            return isset($segment[$part]) and in_array($segment[$part], $page);
        }
        
        /**
         * in_url
         *
         * @param string|null $data
         * @return string
         */
        public static function in_url(string|null $data): string
        {
            return str_replace(array(SITEURL, '"{', '}"', 'url("', '");'), array('[SITEURL]', "'{", "}'", 'url(', ');'), $data ?? '');
        }
        
        /**
         * out_url
         *
         * @param string|null $data
         * @return string
         */
        public static function out_url(string|null $data): string
        {
            return str_replace(array('[SITEURL]'), array(SITEURL), $data ?? '');
        }
        
        /**
         * doSystemPageSlugs
         *
         * @return void
         */
        public static function doSystemPageSlugs(): void
        {
            $array = json_decode(Database::Go()->select(Core::sTable, array('lang_list'))->where('id', 1, '=')->one()->run());
            $array = json_decode(json_encode($array), true);
            $mapped = array_map(function ($k) {
                return 'slug_' . $k['abbr'];
            }, array_values($array));
            
            $final = array_merge(array('page_type'), $mapped);
            $data = Database::Go()->select('pages', $final)->where('is_system', 1, '=')->run();
            
            $result = Utility::groupToLoop($data, 'page_type');
            Database::Go()->update(Core::sTable, array('system_slugs' => json_encode($result)))->where('id', 1, '=')->run();
        }
        
        /**
         * getIP
         *
         * @return array|mixed|string|string[]|null
         */
        public static function getIP(): mixed
        {
            if (getenv('HTTP_CLIENT_IP')) {
                $ipaddress = getenv('HTTP_CLIENT_IP');
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
                } else {
                    if (getenv('HTTP_X_FORWARDED')) {
                        $ipaddress = getenv('HTTP_X_FORWARDED');
                    } else {
                        if (getenv('HTTP_FORWARDED_FOR')) {
                            $ipaddress = getenv('HTTP_FORWARDED_FOR');
                        } else {
                            if (getenv('HTTP_FORWARDED')) {
                                $ipaddress = getenv('HTTP_FORWARDED');
                            } else {
                                if (getenv('REMOTE_ADDR')) {
                                    $ipaddress = getenv('REMOTE_ADDR');
                                } else {
                                    $ipaddress = 'UNKNOWN';
                                }
                            }
                        }
                    }
                }
            }
            
            return Validator::sanitize($ipaddress);
        }
        
        /**
         * doSeo
         *
         * @param string $string
         * @param int $maxlen
         * @return array|string|string[]|null
         */
        public static function doSeo(string $string, int $maxlen = 0): array|string|null
        {
            $newStringTab = array();
            $string = Validator::cleanOut($string);
            $string = mb_strtolower(self::doChars($string));
            $stringTab = str_split($string);
            $numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-');
            
            foreach ($stringTab as $letter) {
                if (in_array($letter, range('a', 'z')) || in_array($letter, $numbers)) {
                    $newStringTab[] = $letter;
                } elseif ($letter == ' ') {
                    $newStringTab[] = '-';
                }
            }
            
            if (count($newStringTab)) {
                $newString = implode($newStringTab);
                if ($maxlen > 0) {
                    $newString = substr($newString, 0, $maxlen);
                }
                
                $newString = self::remDupes($newString);
            } else {
                $newString = '';
            }
            
            return $newString;
        }
        
        /**
         * doChars
         *
         * @param string $string
         * @return string
         */
        private static function doChars(string $string): string
        {
            $cyrylicFrom = array(
                'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц',
                'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
            );
            $cyrylicTo = array(
                'A', 'B', 'V', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', '', 'E', 'Iu', 'Ia', 'a', 'b', 'v', 'g', 'd', 'ie', 'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u',
                'f', 'ch', 'c', 'tch', 'sh', 'shtch', '', 'y', '', 'e', 'iu', 'ia'
            );
            
            $from = array(
                'Á', 'À', 'Â', 'Ä', 'Ă', 'Ā', 'Ã', 'Å', 'Ą', 'Æ', 'Ć', 'Ċ', 'Ĉ', 'Č', 'Ç', 'Ď', 'Đ', 'Ð', 'É', 'È', 'Ė', 'Ê', 'Ë', 'Ě', 'Ē', 'Ę', 'Ə', 'Ġ', 'Ĝ', 'Ğ', 'Ģ', 'á', 'à', 'â', 'ä', 'ă', 'ā', 'ã', 'å', 'ą', 'æ', 'ć', 'ċ', 'ĉ', 'č', 'ç', 'ď', 'đ', 'ð', 'é', 'è', 'ė', 'ê', 'ë', 'ě', 'ē', 'ę',
                'ə', 'ġ', 'ĝ', 'ğ', 'ģ', 'Ĥ', 'Ħ', 'I', 'Í', 'Ì', 'İ', 'Î', 'Ï', 'Ī', 'Į', 'Ĳ', 'Ĵ', 'Ķ', 'Ļ', 'Ł', 'Ń', 'Ň', 'Ñ', 'Ņ', 'Ó', 'Ò', 'Ô', 'Ö', 'Õ', 'Ő', 'Ø', 'Ơ', 'Œ', 'ĥ', 'ħ', 'ı', 'í', 'ì', 'i', 'î', 'ï', 'ī', 'į', 'ĳ', 'ĵ', 'ķ', 'ļ', 'ł', 'ń', 'ň', 'ñ', 'ņ', 'ó', 'ò', 'ô', 'ö', 'õ',
                'ő', 'ø', 'ơ', 'œ', 'Ŕ', 'Ř', 'Ś', 'Ŝ', 'Š', 'Ş', 'Ť', 'Ţ', 'Þ', 'Ú', 'Ù', 'Û', 'Ü', 'Ŭ', 'Ū', 'Ů', 'Ų', 'Ű', 'Ư', 'Ŵ', 'Ý', 'Ŷ', 'Ÿ', 'Ź', 'Ż', 'Ž', 'ŕ', 'ř', 'ś', 'ŝ', 'š', 'ş', 'ß', 'ť', 'ţ', 'þ', 'ú', 'ù', 'û', 'ü', 'ŭ', 'ū', 'ů', 'ų', 'ű', 'ư', 'ŵ', 'ý', 'ŷ', 'ÿ', 'ź', 'ż', 'ž'
            );
            $to = array(
                'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'C', 'C', 'C', 'C', 'D', 'D', 'D', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'G', 'G', 'G', 'G', 'G', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'c', 'c', 'c', 'c', 'd', 'd', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
                'e', 'g', 'g', 'g', 'g', 'g', 'H', 'H', 'I', 'I', 'I', 'I', 'I', 'I', 'I', 'I', 'IJ', 'J', 'K', 'L', 'L', 'N', 'N', 'N', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'CE', 'h', 'h', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'ij', 'j', 'k', 'l', 'l', 'n', 'n', 'n', 'n', 'o', 'o', 'o',
                'o', 'o', 'o', 'o', 'o', 'o', 'R', 'R', 'S', 'S', 'S', 'S', 'T', 'T', 'T', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'W', 'Y', 'Y', 'Y', 'Z', 'Z', 'Z', 'r', 'r', 's', 's', 's', 's', 'B', 't', 't', 'b', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'w', 'y', 'y', 'y', 'z',
                'z', 'z'
            );
            $from = array_merge($from, $cyrylicFrom);
            $to = array_merge($to, $cyrylicTo);
            
            return str_replace($from, $to, $string);
        }
        
        /**
         * remDupes
         *
         * @param string $sSubject
         * @return array|string|string[]|void
         */
        private static function remDupes(string $sSubject)
        {
            $i = 0;
            do {
                
                $sSubject = str_replace('--', '-', $sSubject);
                $pos = strpos($sSubject, '--');
                
                $i++;
                if ($i > 100) {
                    die('remDupes() loop error');
                }
            } while ($pos !== false);
            
            return $sSubject;
        }
        
        /**
         * sortItems
         *
         * @param string $url
         * @param string $action
         * @param bool $full
         * @return string|void
         */
        public static function sortItems(string $url, string $action, bool $full = false)
        {
            if (isset($_GET[$action])) {
                $data = explode('|', $_GET[$action]);
                if ($data && count($data) == 2) {
                    $result = ($data[1] == 'DESC') ? 'ASC' : 'DESC';
                    if ($full) {
                        $parts = parse_url($_SERVER['REQUEST_URI']);
                        if (isset($parts['query'])) {
                            parse_str($parts['query'], $qs);
                        } else {
                            $qs = array();
                        }
                        $qs[$action] = $data[0] . '|' . $result;
                        return $url . '?' . http_build_query($qs);
                    } else {
                        return $url . "?$action=" . $data[0] . '|' . $result;
                    }
                }
            }
        }
        
        /**
         * setActive
         *
         * @param string $action
         * @param string $name
         * @param string $class
         * @return string|void
         */
        public static function setActive(string $action, string $name, string $class = 'active')
        {
            if (isset($_GET[$action])) {
                $data = explode('|', $_GET[$action]);
                if ($data && count($data) == 2) {
                    return ($data[0] == $name) ? " $class" : '';
                }
            } elseif (!$name) {
                return " $class";
            }
        }
        
        /**
         * buildQuery
         *
         * @return string|null
         */
        public static function buildQuery(): string|null
        {
            $parts = parse_url($_SERVER['REQUEST_URI']);
            if (isset($parts['query'])) {
                parse_str($parts['query'], $qs);
                return '/?' . http_build_query($qs);
            } else {
                return null;
            }
        }
        
        /**
         * buildUrl
         *
         * @param string $key
         * @param string $value
         * @param string $option
         * @return string
         */
        public static function buildUrl(string $key, string $value, string $option = 'filter'): string
        {
            $parts = parse_url($_SERVER['REQUEST_URI']);
            if (isset($parts['query'])) {
                parse_str($parts['query'], $qs);
            } else {
                $qs = array();
            }
            if ($option == 'filter') {
                $qs[$option] = 'true';
            }
            //$option == 'filter' ? $qs[$option] = 'true' : null;
            $qs[$key] = $value;
            return '?' . http_build_query($qs);
        }
        
        /**
         * thisUrl
         *
         * @return string
         */
        public static function thisUrl(): string
        {
            return SITEURL . $_SERVER['REQUEST_URI'];
        }
        
        /**
         * invalidMethod
         *
         * @return void
         */
        public static function invalidMethod(): void
        {
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json; charset=UTF-8');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Max-Age: 3600');
            header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
            http_response_code(400);
            
            echo json_encode(array(
                'type' => 'error',
                'title' => Language::$word->ERROR,
                'message' => Language::$word->PROCESS_ERR2
            ));
        }
        
        /**
         * isActive
         *
         * @param string $val1
         * @param string $val2
         * @param string $class
         * @return string|void
         */
        public static function isActive(string $val1, string $val2, string $class = 'active')
        {
            if (isset($_GET[$val1]) and $_GET[$val1] == $val2) {
                return $class;
            }
        }
        
        /**
         * ssl
         *
         * @return bool
         */
        function ssl(): bool
        {
            if (isset($_SERVER['HTTPS'])) {
                if ('on' == strtolower($_SERVER['HTTPS'])) {
                    return true;
                }
                if ('1' == $_SERVER['HTTPS']) {
                    return true;
                }
            } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
                return true;
            }
            return false;
        }
    }