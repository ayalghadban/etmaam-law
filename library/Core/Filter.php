<?php
    /**
     * Filter CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Filter.php, v1.00 4/29/2023 10:44 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Filter
    {
        public static ?int $id = 0;
        public static array $get = array();
        public static array $post = array();
        public static array $cookie = array();
        public static array $files = array();
        public static array $server = array();
        
        /**
         * run
         *
         * @return void
         */
        public static function run(): void
        {
            $_GET = self::clean($_GET);
            $_POST = self::clean($_POST);
            $_COOKIE = self::clean($_COOKIE);
            $_FILES = self::clean($_FILES);
            $_SERVER = self::clean($_SERVER);
            
            self::$get = $_GET;
            self::$post = $_POST;
            self::$cookie = $_COOKIE;
            self::$files = $_FILES;
            self::$server = $_SERVER;
            
            self::$id = self::getId();
        }
        
        /**
         * clean
         *
         * @param array|string|null $data
         * @return array|string|string[]|null
         */
        public static function clean(array|string|null $data): array|string|null
        {
            if (is_array($data)) {
                return array_map(array('Wojo\Core\Filter', 'clean'), $data);
            }
            
            // Fix &entity
            $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
            
            //$data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
            $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
            $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
            
            // Remove any attribute starting with "on" or xmlns
            $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on)[^>]*+>#iu', '$1>', $data);
            
            // Remove javascript: and vbscript: protocols
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
            
            // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
            
            // Remove namespaced elements (we do not need them)
            $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
            
            do {
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed(?:set)?|i(?:layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
            } while ($old_data !== $data);
            
            // we are done...
            return $data;
        }
        
        /**
         * getId
         *
         * @return int|void|null
         */
        private static function getId()
        {
            if (isset($_REQUEST['id'])) {
                self::$id = intval($_REQUEST['id']);
                return self::$id;
            }
        }
    }