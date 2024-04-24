<?php
    /**
     * Utility Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Utility.php, v1.00 4/26/2023 4:03 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Utility;
    
    use NumberFormatter;
    use Wojo\Container\Container;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Utility
    {
        
        /**
         * status
         *
         * @param string $status
         * @param int $id
         * @return string|null
         */
        public static function status(string $status, int $id): ?string
        {
            switch ($status) {
                case 'y':
                    $display = '<span class="wojo small inverted positive label">' . Language::$word->ACTIVE . '</span>';
                    break;
                
                case 'n':
                    $icon = '<i class="icon email"></i> ';
                    $display = '<a data-set=\'{"option":[{"action":"resend","id": ' . $id . '}], "label":"' . Language::$word->M_SUB6 . '", "url":"users/action/", "parent":"#item_' . $id . '", "complete":"highlight", "modalclass":"normal"}\' class="wojo small inverted primary label action">' . $icon . Language::$word->INACTIVE . '</a>';
                    break;
                
                case 't':
                    $display = '<span class="wojo small dark inverted label">' . Language::$word->PENDING . '</span>';
                    break;
                
                case 'b':
                    $display = '<span class="wojo small inverted negative label">' . Language::$word->BANNED . '</span>';
                    break;
                    
                default:
                    $display = null;
                    break;
            }
            
            return $display;
        }
        
        /**
         * isActive
         *
         * @param string $name
         * @param array $array
         * @return string|null
         */
        public static function isActive(string $name, array $array): string|null
        {
            return in_array($name, $array) ? ' class="active"' : null;
        }
        
        /**
         * isActiveMulti
         *
         * @param array $names
         * @param array $array
         * @return string|null
         */
        public static function isActiveMulti(array $names, array $array): string|null
        {
            return Utility::in_array_any($names, $array) ? ' class="active"' : null;
        }
        
        /**
         * isPublished
         *
         * @param int $id
         * @return string
         */
        public static function isPublished(int $id): string
        {
            return ($id == 1) ? '<i class="icon positive check"></i>' : '<i class="icon negative ban"></i>';
        }
        
        /**
         * userType
         *
         * @param string $type
         * @return string|null
         */
        public static function userType(string $type): ?string
        {
            try {
                return match ($type) {
                    'owner' => '<span class="wojo small inverted secondary label">' . $type . '</span>',
                    'staff' => '<span class="wojo small inverted primary label">' . $type . '</span>',
                    'editor' => '<span class="wojo small inverted negative label">' . $type . '</span>',
                    'manager' => '<span class="wojo small inverted positive label">' . $type . '</span>',
                    'member' => '<span class="wojo small dark inverted label">' . $type . '</span>',
                    default => throw new NotFoundException(sprintf('The requested action "%s" don\'t match any type.', $type)),
                };
            } catch (NotFoundException) {
            }
            
        }
        
        /**
         * accountLevelToType
         *
         * @param int $level
         * @return string
         */
        public static function accountLevelToType(int $level): string
        {
            try {
                return match ($level) {
                    9 => '<span class="wojo small bold positive text">' . Language::$word->OWNER . '</span>',
                    8 => '<span class="wojo small bold primary text">' . Language::$word->STAFF . '</span>',
                    7 => '<span class="wojo small bold secondary text">' . Language::$word->EDITOR . '</span>',
                    5 => '<span class="wojo small bold negative text">' . Language::$word->MANAGER . '</span>',
                    1 => '<span class="wojo small bold black text">' . Language::$word->MEMBER . '</span>',
                    default => throw new NotFoundException(sprintf('The requested action "%s" don\'t match any level.', $level)),
                };
            } catch (NotFoundException) {
            }
        }
        
        /**
         * randName
         *
         * @param int $char
         * @return string
         */
        public static function randName(int $char = 6): string
        {
            $code = '';
            for ($x = 0; $x < $char; $x++) {
                $code .= '-' . substr(strtoupper(sha1(rand(0, 999999999999999))), 2, $char);
            }
            return substr($code, 1);
        }
        
        /**
         * randNumbers
         *
         * @param int $digits
         * @return int
         */
        public static function randNumbers(int $digits = 7): int
        {
            $min = pow(10, $digits - 1);
            $max = pow(10, $digits) - 1;
            return mt_rand($min, $max);
        }
        
        /**
         * randomString
         *
         * @param int $length
         * @return string
         */
        public static function randomString(int $length = 8): string
        {
            $keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
            $key = '';
            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[mt_rand(0, count($keys) - 1)];
            }
            return $key;
        }
        
        /**
         * getLogo
         *
         * @return string
         */
        public static function getLogo(): string
        {
            $core = Container::instance()->get('core');
            
            if ($core->logo) {
                $logo = '<img src="' . UPLOADURL . '/' . $core->logo . '" alt="' . $core->company . '" style="border:0;width:150px"/>';
            } else {
                $logo = $core->company;
            }
            
            return $logo;
        }
        
        /**
         * formatMoney
         *
         * @param float $amount
         * @param bool $currency
         * @param bool $decimal
         * @return false|string
         */
        public static function formatMoney(float $amount, bool $currency = false, bool $decimal = true): false|string
        {
            $core = Container::instance()->get('core');
            $code = $currency ? : $core->currency;
            
            $fmt = new NumberFormatter($core->locale, NumberFormatter::CURRENCY);
            if (!$decimal) {
                $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, $code);
                $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            }
            return $fmt->formatCurrency($amount, $code);
        }
        
        /**
         * currencySymbol
         *
         * @return false|string
         */
        public static function currencySymbol(): false|string
        {
            $core = Container::instance()->get('core');
            
            $fmt = new NumberFormatter($core->locale, NumberFormatter::CURRENCY);
            $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, $core->currency);
            
            return $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        }
        
        /**
         * formatNumber
         *
         * @param int|float $number
         * @return false|string
         */
        public static function formatNumber(int|float $number): false|string
        {
            $core = Container::instance()->get('core');
            $fmt = new NumberFormatter($core->locale, NumberFormatter::PATTERN_DECIMAL);
            
            $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
            $fmt->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
            $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
            $fmt->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 2);
            
            return $fmt->format($number);
        }
        
        /**
         * numberParse
         *
         * @param int|float $number
         * @return false|float|int|mixed
         */
        public static function numberParse(int|float $number): mixed
        {
            $fmt = new NumberFormatter('en_US', NumberFormatter::PATTERN_DECIMAL);
            
            return $fmt->parse($number);
        }
        
        /**
         * loopOptions
         *
         * @param array $array
         * @param string $key
         * @param string $value
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptions(array $array, string $key, string $value, string|bool|null $selected = false): false|string
        {
            $html = '';
            if ($array) {
                foreach ($array as $row) {
                    $html .= "<option value=\"" . $row->$key . "\"";
                    $html .= ($row->$key == $selected) ? ' selected="selected"' : '';
                    $html .= '>' . $row->$value . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * loopOptionsMultiple
         *
         * @param array $array
         * @param string $key
         * @param string $value
         * @param string|bool|null $selected
         * @param string $name
         * @param string $size
         * @return false|string
         */
        public static function loopOptionsMultiple(array $array, string $key, string $value, string|bool|null $selected, string $name, string $size = 'small'): false|string
        {
            $arr = array();
            if ($selected) {
                $arr = explode(',', $selected);
            }
            $html = '';
            if ($array) {
                foreach ($array as $k => $row) {
                    $html .= "<div class=\"columns\">\n";
                    $html .= "<div class=\"wojo " . $size . " checkbox\">\n";
                    $html .= "<input type=\"checkbox\" name=\"" . $name . "[]\" value=\"" . $row->$key . "\" id=\"" . $name . '_' . $k . "\"";
                    $html .= (in_array($row->$key, $arr)) ? ' checked="checked"' : '';
                    $html .= ">\n";
                    $html .= "<label for=\"" . $name . '_' . $k . "\">" . $row->$value . "</label>\n";
                    $html .= "</div>\n";
                    $html .= "</div>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * loopOptionsSimpleMultiple
         *
         * @param array $array
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptionsSimpleMultiple(array $array = [], string|bool|null $selected = false): false|string
        {
            $arr = array();
            if ($selected) {
                $arr = explode(',', $selected);
            }
            $html = '';
            if (is_array($array)) {
                foreach ($array as $row) {
                    $html .= "<option value=\"" . $row . "\"";
                    $html .= (in_array($row, $arr)) ? ' selected="selected"' : '';
                    $html .= '>' . $row . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * loopOptionsSimple
         *
         * @param array $array
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptionsSimple(array $array = [], string|bool|null $selected = false): false|string
        {
            $html = '';
            if (is_array($array)) {
                foreach ($array as $row) {
                    $html .= "<option value=\"" . $row . "\"";
                    $html .= ($row == $selected) ? ' selected="selected"' : '';
                    $html .= '>' . $row . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * loopOptionsSimpleAlt
         *
         * @param array $array
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptionsSimpleAlt(array $array = [], string|bool|null $selected = false): false|string
        {
            $html = '';
            if (is_array($array)) {
                foreach ($array as $key => $row) {
                    $html .= "<option value=\"" . $key . "\"";
                    $html .= ($key == $selected) ? ' selected="selected"' : '';
                    $html .= '>' . $row . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * loopSingleLine
         *
         * @param array $array
         * @return false|string
         */
        public static function loopSingleLine(array $array = []): false|string
        {
            $html = '';
            if (is_array($array)) {
                foreach ($array as $row) {
                    $html .= $row . PHP_EOL;
                }
                return $html;
            }
            return false;
        }
        
        /**
         * groupToLoop
         *
         * @param array $array
         * @param string $key
         * @return array
         */
        public static function groupToLoop(array $array, string $key): array
        {
            $result = array();
            if ($array) {
                foreach ($array as $val) {
                    $itemName = $val->{$key};
                    if (!array_key_exists($itemName, $result)) {
                        $result[$itemName] = array();
                    }
                    $result[$itemName][] = $val;
                }
            }
            return $result;
        }
        
        /**
         * implodeFields
         *
         * @param array $array
         * @param string $sep
         * @param bool $is_string
         * @return false|string
         */
        public static function implodeFields(array $array = [], string $sep = ',', bool $is_string = false): false|string
        {
            if (is_array($array)) {
                $result = array();
                foreach ($array as $row) {
                    if ($row != '') {
                        $result[] = Validator::sanitize($row);
                    }
                }
                return $is_string ? sprintf('"%s"', implode('","', $result)) : implode($sep, $result);
            }
            return false;
        }
        
        /**
         * implodeObject
         *
         * @param array $array
         * @param string $value
         * @return string
         */
        public static function implodeObject(array $array, string $value): string
        {
            $new_arr = array();
            foreach ($array as $row) {
                $new_arr[] = $row->$value;
            }
            
            return implode(', ', $new_arr);
        }
        
        /**
         * tableGrid
         *
         * @param array $array
         * @param int $size
         * @return string
         */
        public static function tableGrid(array $array, int $size): string
        {
            $table_width = 100;
            $width = intval($table_width / $size);
            $tr = '<tr>';
            $td = "<td style=\"width:$width%%\">%s</td>";
            $grid = "<table class=\"wojo table\">$tr";
            $i = 0;
            
            foreach ($array as $e) {
                $grid .= sprintf($td, $e);
                $i++;
                if (!($i % $size)) {
                    $grid .= "$tr";
                }
            }
            
            while ($i % $size) {
                $grid .= sprintf($td, ' ');
                $i++;
            }
            
            $end_tr_len = strlen($tr) * -1;
            if (substr($grid, $end_tr_len) != $tr) {
                $grid .= '</tr>';
            } else {
                $grid = substr($grid, 0, $end_tr_len);
            }
            $grid .= '</table>';
            
            return $grid;
        }
        
        
        /**
         * findInArray
         *
         * @param array|int $array
         * @param string $key
         * @param string $value
         * @return array|int|null
         */
        public static function findInArray(int|array $array, string $key, string $value): array|int|null
        {
            if ($array) {
                $result = array();
                foreach ($array as $val) {
                    if ((is_object($val) ? ($val->$key == $value) : ($val[$key] == $value))) {
                        $result[] = $val;
                    }
                }
                return $result;
            }
            return false;
        }
        
        /**
         * searchForValue
         *
         * @param string $key
         * @param string $value
         * @param array $array
         * @return string|false
         */
        public static function searchForValue(string $key, string $value, array $array = []): string|false
        {
            if (is_array($array)) {
                foreach ($array as $val) {
                    if ((is_object($val) ? ($val->$key == $value) : ($val[$key] == $value))) {
                        return $key;
                    }
                }
            }
            return false;
        }
        
        /**
         * searchForValueName
         *
         * @param string $key
         * @param string $value
         * @param string $return
         * @param array $array
         * @param bool $fullkey
         * @return false|mixed
         */
        public static function searchForValueName(string $key, string $value, string $return, array $array = [], bool $fullkey = false): mixed
        {
            
            if (is_array($array)) {
                foreach ($array as $val) {
                    if (is_object($array)) {
                        if ($val->$key == $value) {
                            return $fullkey ? $val : $val->$return;
                            //return $fullkey ? $array[$k] : $val->$return;
                        }
                    } else {
                        if ($val[$key] == $value) {
                            return $fullkey ? $val : $val[$return];
                            //return $fullkey ? $array[$k] : $val[$return];
                        }
                    }
                }
            }
            return false;
        }
        
        /**
         * countInArray
         *
         * @param array|int $array $array
         * @param string $key
         * @param string $value
         * @return int
         */
        public static function countInArray(array|int $array, string $key, string $value): int
        {
            $i = 0;
            if ($array) {
                foreach ($array as $v) {
                    if ((is_object($v) ? ($v->$key == $value) : ($v[$key] == $value))) {
                        $i++;
                    }
                }
            }
            return $i;
        }
        
        /**
         * sortArray
         *
         * @param array $data
         * @param string $field
         * @return array
         */
        public static function sortArray(array $data, string $field): array
        {
            $field = (array) $field;
            uasort($data, function ($a, $b) use ($field) {
                $retval = 0;
                foreach ($field as $fieldname) {
                    if ($retval == 0) {
                        $retval = strnatcmp($a[$fieldname], $b[$fieldname]);
                    }
                }
                return $retval;
            });
            return array_values($data);
        }
        
        /**
         * unserialToArray
         *
         * @param string $string
         * @return false|mixed
         */
        public static function unserialToArray(string $string): mixed
        {
            if ($string) {
                return unserialize($string);
            }
            return false;
        }
        
        /**
         * jSonMergeToArray
         *
         * @param array $array
         * @param string $jstring
         * @return array|false
         */
        public static function jSonMergeToArray(array $array, string $jstring): false|array
        {
            if ($array) {
                $allData = array();
                foreach ($array as $row) {
                    $data = self::jSonToArray($row->{$jstring});
                    if ($data != null) {
                        $allData = array_merge($allData, $data);
                    }
                }
                return $allData;
                
            }
            return false;
        }
        
        /**
         * jSonToArray
         *
         * @param string|null $string
         * @param bool $assoc
         * @return false|mixed
         */
        public static function jSonToArray(null|string $string, $assoc = false): mixed
        {
            if ($string) {
                return json_decode($string, $assoc);
            }
            return false;
        }
        
        /**
         * parseJsonArray
         *
         * @param array $jsonArray
         * @param int $parent_id
         * @return array
         */
        public static function parseJsonArray(array $jsonArray, int $parent_id = 0): array
        {
            $data = array();
            foreach ($jsonArray as $subArray) {
                $returnSubSubArray = array();
                if (isset($subArray['children'])) {
                    $returnSubSubArray = self::parseJsonArray($subArray['children'], $subArray['id']);
                }
                $data[] = array('id' => $subArray['id'], 'parent_id' => $parent_id);
                $data = array_merge($data, $returnSubSubArray);
            }
            
            return $data;
        }
        
        /**
         * mapFields
         *
         * @param array $values
         * @param string $name
         * @return array
         */
        public static function mapFields(array $values, string $name): array
        {
            $core = Container::instance()->get('core');
            
            $array = json_decode(json_encode($core->langlist), true);
            $mapped = array_map(function ($k) use ($name) {
                return $name . '_' . $k['abbr'];
            }, array_values($array));
            return array_merge($values, $mapped);
        }
        
        /**
         * getLangSlugs
         *
         * @param string $field
         * @return string[]
         */
        public static function getLangSlugs(string $field): array
        {
            $core = Container::instance()->get('core');
            
            $array = json_decode(json_encode($core->langlist), true);
            return array_map(function ($k) use ($field) {
                return $field . $k['abbr'];
            }, array_values($array));
        }
        
        /**
         * insertLangSlugs
         *
         * @param string $field
         * @param string $value
         * @return array
         */
        public static function insertLangSlugs(string $field, string $value): array
        {
            $array = json_decode(json_encode(Container::instance()->get('core')->langlist), true);
            $data = array();
            foreach ($array as $row) {
                $data[$field . '_' . $row['abbr']] = $value;
            }
            return $data;
        }
        
        /**
         * parseImageTags
         *
         * @param string|null $text
         * @return string[]
         */
        public static function parseImageTags(string|null $text): array
        {
            preg_match_all('%<img[^>]*src="([^"]+)"[^>]*>%', str_replace('[SITEURL]', SITEURL, $text ?? ''), $matches);
            return $matches[1];
        }
        
        /**
         * array_key_exists_wildcard
         *
         * @param array $array
         * @param string $search
         * @param string $return
         * @return array|false
         */
        public static function array_key_exists_wildcard(array $array, string $search, string $return = ''): false|array
        {
            $search = str_replace('\*', '.*?', preg_quote($search, '/'));
            $result = preg_grep('/^' . $search . '$/i', array_keys($array));
            if ($return == 'key-value') {
                return array_intersect_key($array, array_flip($result));
            }
            return $result;
        }
        
        /**
         * array_value_exists_wildcard
         *
         * @param array $array
         * @param string $search
         * @param string $return
         * @return array|false
         */
        public static function array_value_exists_wildcard(array $array, string $search, string $return = ''): false|array
        {
            $search = str_replace('\*', '.*?', preg_quote($search, '/'));
            $result = preg_grep('/^' . $search . '$/i', array_values($array));
            if ($return == 'key-value') {
                return array_intersect($array, $result);
            }
            return $result;
        }
        
        /**
         * encode
         *
         * @param string $string
         * @return string
         */
        public static function encode(string $string): string
        {
            return base64_encode(openssl_encrypt($string, 'AES-256-CBC', hash('sha256', INSTALL_KEY), 0, substr(hash('sha256', INSTALL_KEY), 0, 16)));
        }
        
        /**
         * decode
         *
         * @param string $string
         * @return false|string
         */
        public static function decode(string $string): false|string
        {
            return openssl_decrypt(base64_decode($string), 'AES-256-CBC', hash('sha256', INSTALL_KEY), 0, substr(hash('sha256', INSTALL_KEY), 0, 16));
        }
        
        /**
         * in_array_any
         *
         * @param array $needles
         * @param array $haystack
         * @return bool
         */
        public static function in_array_any(array $needles, array $haystack): bool
        {
            return !!array_intersect($needles, $haystack);
        }
        
        /**
         * in_array_all
         *
         * @param array $needles
         * @param array $haystack
         * @return bool
         */
        public static function in_array_all(array $needles, array $haystack): bool
        {
            return !array_diff($needles, $haystack);
        }
        
        /**
         * getInitials
         *
         * @param string|null $string $string
         * @return string
         */
        public static function getInitials(string|null $string = ''): string
        {
            
            $result = '';
            if (strlen($string ?? '') !== 0) {
                foreach (explode(' ', $string) as $word) {
                    $result .= strtoupper($word[0]);
                }
            }
            return $result;
        }
        
        /**
         * getColors
         *
         * @return string
         */
        public static function getColors(): string
        {
            
            static $colorCounter = -1;
            $colorArray = array('#f44336', '#673ab7', '#e91e63', '#3f51b5', '#9c27b0', '#2196f3', '#009688', '#03a9f4', '#4caf50', '#00bcd4', '#cddc39', '#8bc34a', '#ffc107', '#795548', '#607d8b');
            $colorCounter++;
            
            return $colorArray[$colorCounter % count($colorArray)];
        }
        
        /**
         * getCssClasses
         *
         * @return string
         */
        public static function getCssClasses(): string
        {
            
            static $colorCounter = -1;
            $colorArray = array('red', 'cyan', 'purple', 'indigo', 'blue', 'pink', 'teal', 'green', 'lime', 'amber', 'orange', 'brown', 'grey');
            $colorCounter++;
            
            return $colorArray[$colorCounter % count($colorArray)];
        }
        
        /**
         * getExplodedValue
         *
         * @param string $string
         * @param string $value
         * @param string $sep
         * @return string
         */
        public static function getExplodedValue(string $string, string $value, string $sep = ','): string
        {
            $result = explode($sep, $string);
            return $result[$value];
        }
        
        /**
         * doPercent
         *
         * @param int $number
         * @param int $total
         * @return float|int
         */
        public static function doPercent(int $number, int $total): float|int
        {
            
            return ($number > 0 and $total > 0) ? round(($number / $total) * 100) : 0;
        }
        
        /**
         * decimalToHour
         *
         * @param int $number
         * @return array|string|string[]
         */
        public static function decimalToHour(int $number): array|string
        {
            $number = number_format($number, 2);
            return str_replace('.', ':', $number);
        }
        
        /**
         * decimalToReadableHour
         *
         * @param int $number
         * @return array
         */
        public static function decimalToReadableHour(int $number): array
        {
            $data = explode('.', $number);
            $hour = $data[0] ?? 0;
            $min = $data[1] ?? 0;
            
            return [$hour, $min];
        }
        
        /**
         * shortName
         *
         * @param string $fname
         * @param string $lname
         * @return string
         */
        public static function shortName(string $fname, string $lname): string
        {
            
            return $fname . ' ' . substr($lname, 0, 1) . '.';
        }
        
        /**
         * decimalToHumanHour
         *
         * @param int $number
         * @return string
         */
        public static function decimalToHumanHour(int $number): string
        {
            $data = explode('.', $number);
            $hour = isset($data[0]) ? $data[0] . ' ' . mb_strtolower(Language::$word->_HOURS) : 0;
            $min = (isset($data[1]) and $data[1] > 0) ? $data[1] . ' ' . mb_strtolower(Language::$word->_MINUTES) : '';
            
            return $hour . ' ' . $min;
        }
        
        /**
         * splitCurrency
         *
         * @param string $currency
         * @return array
         */
        public static function splitCurrency(string $currency): array
        {
            $core = Container::instance()->get('core');
            
            $data = array();
            
            if (strlen($currency) !== 0) {
                $iso = explode(',', $currency);
                $data['currency'] = $iso[0];
                $data['country'] = $iso[1];
            } else {
                $data['currency'] = $core->currency;
                $data['country'] = isset($_POST['country']) ? Validator::sanitize($_POST['country'], 'string') : '';
            }
            
            return $data;
        }
        
        /**
         * getSnippets
         *
         * @param string $filename
         * @param array $data
         * @return false|string
         */
        public static function getSnippets(string $filename, array $data = []): false|string
        {
            if (File::is_File($filename)) {
                ob_start();
                if (is_array($data)) {
                    extract($data, EXTR_SKIP);
                }
                require($filename);
                $html = ob_get_contents();
                ob_end_clean();
                return $html;
            } else {
                return false;
            }
        }
        
        /**
         * arrayRotate
         *
         * @param array $data
         * @param int $steps
         * @return void
         */
        public static function arrayRotate(array &$data, int $steps): void
        {
            $count = count($data);
            if ($steps < 0) {
                $steps = $count + $steps;
            }
            $steps = $steps % $count;
            for ($i = 0; $i < $steps; $i++) {
                $data[] = array_shift($data);
            }
        }
        
        /**
         * doRange
         *
         * @param string|int|float $min
         * @param string|int|float $max
         * @param int|float $step
         * @param bool $selected
         * @return string
         */
        public static function doRange(string|int|float $min, string|int|float $max, int|float $step, string|bool $selected = false): string
        {
            $html = '';
            foreach (range($min, $max, $step) as $number) {
                $html .= "<option value=\"" . $number . "\"";
                $html .= ($number == $selected) ? ' selected="selected"' : '';
                $html .= '>' . $number . "</option>\n";
            }
            
            return $html;
        }
        
        /**
         * numberRange
         *
         * @param string|int|float $min
         * @param string|int|float $max
         * @param int|float $step
         * @return string
         */
        public static function numberRange(string|int|float $min, string|int|float $max, int|float $step = 1): string
        {
            
            return implode(',', range($min, $max, $step));
        }
        
        /**
         * sayHello
         *
         * @return string
         */
        public static function sayHello(): string
        {
            $welcome = Language::$word->HI . ' ';
            if (date('H') < 12) {
                $welcome .= Language::$word->HI_M;
            } else {
                if (date('H') > 11 && date('H') < 18) {
                    $welcome .= Language::$word->HI_A;
                } else {
                    if (date('H') > 17) {
                        $welcome .= Language::$word->HI_E;
                    }
                }
            }
            
            return $welcome;
        }
        
        /**
         * numberToWords
         *
         * @param int|string $number
         * @return false|string
         */
        public static function numberToWords(int|string $number): false|string
        {
            $words = array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen', 'twenty', 30 => 'thirty', 40 => 'fourty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety', 100 => 'hundred', 1000 => 'thousand');
            $number_in_words = '';
            if (is_numeric($number)) {
                $number = (int) round($number);
                if ($number < 0) {
                    $number = -$number;
                    $number_in_words = 'minus ';
                }
                if ($number > 1000) {
                    $number_in_words = $number_in_words . self::numberToWords(floor($number / 1000)) . ' ' . $words[1000];
                    $hundreds = $number % 1000;
                    $tens = $hundreds % 100;
                    if ($hundreds > 100) {
                        $number_in_words = $number_in_words . ', ' . self::numberToWords($hundreds);
                    } elseif ($tens) {
                        $number_in_words = $number_in_words . ' and ' . self::numberToWords($tens);
                    }
                } elseif ($number > 100) {
                    $number_in_words = $number_in_words . self::numberToWords(floor($number / 100)) . ' ' . $words[100];
                    $tens = $number % 100;
                    if ($tens) {
                        $number_in_words = $number_in_words . ' and ' . self::numberToWords($tens);
                    }
                } elseif ($number > 20) {
                    $number_in_words = $number_in_words . ' ' . $words[10 * floor($number / 10)];
                    $units = $number % 10;
                    if ($units) {
                        $number_in_words = $number_in_words . self::numberToWords($units);
                    }
                } else {
                    $number_in_words = $number_in_words . ' ' . $words[$number];
                }
                return $number_in_words;
            }
            return false;
        }
        
        /**
         * parseColors
         *
         * @return array
         */
        public static function parseColors(): array
        {
            $css = File::loadFile(THEMEBASE . 'css/color.css');
            $result = array();
            if ($css) {
                $data = str_replace(array(':root', '{', '}'), array('', '', ''), $css);
                $data = trim($data);
                $data = explode(PHP_EOL, $data);
                $data = array_map('trim', str_replace(array(' ', ';', '--'), array('', '', ''), $data));
                foreach ($data as $colors) {
                    $row = explode(':', $colors);
                    $result[$row[0]] = $row[1];
                }
            }
            return $result;
        }
        
        /**
         * colorToWord
         *
         * @param string $string
         * @return string
         */
        public static function colorToWord(string $string): string
        {
            return match (strtolower($string)) {
                '#1abc9c' => 'meadow',
                '#16a085' => 'meadow-dark',
                '#2ecc71' => 'shamrok',
                '#27ae60' => 'jungle',
                '#3498db' => 'blue',
                '#2980b9' => 'mariner',
                '#9b59b6' => 'wisteria',
                '#8e44ad' => 'studio',
                '#34495e' => 'bluewood',
                '#2c3e50' => 'bluewood-dark',
                '#f1c40f' => 'buttercup',
                '#f39c12' => 'buttercup-dark',
                '#e67e22' => 'zest',
                '#d35400' => 'orange',
                '#e74c3c' => 'cinnabar',
                '#c0392b' => 'poppy',
                '#ecf0f1' => 'porcelain',
                '#bdc3c7' => 'sand',
                '#95a5a6' => 'cascade',
                '#7f8c8d' => 'grey',
                default => 'blank-normal',
            };
        }
        
        /**
         * getColumnSize
         *
         * @param array $size
         * @return int|mixed
         */
        public static function getColumnSize(array $size = array(40, 60, 50, 50, 60, 40, 50, 50, 100, 50, 50)): mixed
        {
            static $colorCounter = -1;
            $colorArray = $size;
            $colorCounter++;
            
            return $colorArray[$colorCounter % count($colorArray)];
        }
        
        /**
         * getHeaderBg
         *
         * @return string
         */
        public static function getHeaderBg(): string
        {
            return isset($_COOKIE['headerBgColor']) ? ' style="background-color:' . $_COOKIE['headerBgColor'] . '"' : '';
        }
        
        /**
         * getSidearrBg
         *
         * @return string
         */
        public static function getSidearrBg(): string
        {
            return isset($_COOKIE['sidebarBgColor']) ? ' style="background-color:' . $_COOKIE['sidebarBgColor'] . '"' : '';
        }
        
        /**
         * getImageUrl
         *
         * @param string $ext
         * @param string $name
         * @return string
         */
        public static function getImageUrl(string $ext, string $name): string
        {
            return ($ext == 'jpg' || $ext == 'png' || $ext == 'gif') ? UPLOADURL . 'files/' . $name : UPLOADURL . 'mime/' . $ext . '.png';
        }
    }