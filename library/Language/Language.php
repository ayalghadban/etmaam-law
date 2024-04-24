<?php
    /**
     * Language Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Language.php, v1.00 4/25/2023 7:42 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Language;
    
    use stdClass;
    use Wojo\Container\Container;
    use Wojo\Core\Core;
    use Wojo\File\File;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    final class Language
    {
        const langdir = 'lang/';
        const lTable = 'language';
        public static string $language;
        public static stdClass $word;
        public static string $lang;
        public static array $main = [];
        public static array $section = [];
        public static array $plugins = [];
        public static array $modules = [];
        
        /**
         * run
         *
         * @return void
         */
        public static function run(): void
        {
            self::get();
        }
        
        /**
         * get
         *
         * @return void
         */
        private static function get(): void
        {
            $core = Container::instance()->get('core');
            if (isset($_COOKIE['LANG_CMSPRO'])) {
                $sel_lang = Validator::sanitize($_COOKIE['LANG_CMSPRO'], 'alpha', 2);
                $vlang = self::fetchLanguage();
                if (in_array($sel_lang, $vlang)) {
                    Core::$language = $sel_lang;
                } else {
                    Core::$language = $core->lang;
                }
                
                if (file_exists(BASEPATH . self::langdir . Core::$language . '/lang.json')) {
                    self::$word = self::set(BASEPATH . self::langdir . Core::$language . '/lang.json', Core::$language);
                } else {
                    self::$word = self::set(BASEPATH . self::langdir . $core->lang . '/lang.json', $core->lang);
                }
                
            } else {
                Core::$language = $core->lang;
                self::$word = self::set(BASEPATH . self::langdir . $core->lang . '/lang.json', $core->lang);
            }
            
            self::$lang = '_' . Core::$language;
        }
        
        /**
         * set
         *
         * @param string $lang
         * @param string $abbr
         * @return object
         */
        public static function set(string $lang, string $abbr): object
        {
            $data = json_decode(File::loadFile($lang), true);
            self::$section = array_keys($data);
            self::$main = array_reduce($data, 'array_merge', array());
            
            $countPlugins = glob(BASEPATH . "lang/$abbr/plugins/" . '*.plugin.json');
            $totalPlugins = count($countPlugins);
            
            if ($totalPlugins) {
                foreach ($countPlugins as $val) {
                    $pJson = File::loadFile($val);
                    foreach (json_decode($pJson, true) as $key => $pkey) {
                        $data[$key] = $pkey;
                        self::$plugins[] = $key;
                    }
                }
            }
            
            $countModules = glob(BASEPATH . "lang/$abbr/modules/" . '*.module.json');
            $totalModules = count($countModules);
            if ($totalModules) {
                foreach ($countModules as $val) {
                    $mJson = File::loadFile($val);
                    foreach (json_decode($mJson, true) as $key => $mkey) {
                        $data[$key] = $mkey;
                        self::$modules[] = $key;
                    }
                }
            }
            $newArray = array_reduce($data, 'array_merge', array());
            
            return (object) $newArray;
        }
        
        /**
         * fetchLanguage
         *
         * @return array|string|false
         */
        public static function fetchLanguage(): array|string|false
        {
            $directory = BASEPATH . self::langdir;
            if (!is_dir($directory)) {
                return false;
            } else {
                $lang_array = glob($directory . '*', GLOB_ONLYDIR);
                $lang_array = str_replace($directory, '', $lang_array);
                
            }
            return $lang_array;
        }
    }