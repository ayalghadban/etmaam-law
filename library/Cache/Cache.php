<?php
    /**
     * Cache.php
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Cache.php, v1.00 4/27/2023 11:35 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Cache;
    
    use Wojo\Core\Core;
    use Wojo\Debug\Debug;
    use Wojo\File\File;
    use Wojo\Message\Message;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Cache
    {
        const CACHE_LIMIT = 100;
        const CACHE_TIME = 86400; //24 hours;
        const prefix = 'master_';
        const css_suffix = '.css';
        const js_suffix = '.js';
        const file_suffix = '.cached';
        private static string $_cacheFile = '';
        private static string $_cacheLifetime = '';
        
        /**
         * cssCache
         *
         * @param array $source
         * @param string $path
         * @return string
         */
        public static function cssCache(array $source, string $path): string
        {
            $ldir = in_array(Core::$language, array('ae', 'he', 'ir')) ? '_rtl' : '_ltr';
            $target = $path . '/cache/';
            $last_change = self::lastChange($source, $path, 'css');
            $temp = $target . self::prefix . 'main' . $ldir . self::css_suffix;
            
            if (!file_exists($temp) || $last_change > filemtime($temp)) {
                if (!self::writeCssCache($source, $temp, $path)) {
                    Message::msgError("Minify:: - Writing the file to <$target> failed!");
                    Debug::addMessage('errors', '<i>Exception</i>', 'Minify:: - Writing the file to <' . $target . '> failed!', 'session');
                }
            }
            
            return basename($temp);
        }
        
        /**
         * lastChange
         *
         * @param array $files
         * @param string $path
         * @param string $type
         * @return int|string|null
         */
        protected static function lastChange(array $files, string $path, string $type): int|string|null
        {
            foreach ($files as $key => $file) {
                $files[$key] = filemtime($path . "/$type/" . $file);
            }
            
            sort($files);
            $files = array_reverse($files);
            
            return $files[key($files)];
        }
        
        /**
         * writeCssCache
         *
         * @param array $files
         * @param string $target
         * @param string $path
         * @return false|int
         */
        protected static function writeCssCache(array $files, string $target, string $path): false|int
        {
            
            $content = '';
            
            foreach ($files as $file) {
                $content .= file_get_contents($path . '/css/' . $file);
            }
            
            
            $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
            $content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $content);
            $content = str_replace(array(': ', ' {', ';}'), array(':', '{', '}'), $content);
            
            if (!file_exists($path . '/cache/')) {
                mkdir($path . '/cache/');
            }
            
            return file_put_contents($target, $content);
        }
        
        /**
         * pluginCssCache
         *
         * @param string $path
         * @return string
         */
        public static function pluginCssCache(string $path): string
        {
            $source = in_array(Core::$language, array('ae', 'he', 'ir')) ?
                File::findFiles(THEMEBASE . 'plugins/css/rtl/', array('fileTypes' => array('css'), 'level' => 0)) :
                File::findFiles(THEMEBASE . 'plugins/css/', array('fileTypes' => array('css'), 'level' => 0));
            $ldir = in_array(Core::$language, array('ae', 'he', 'ir')) ? '_rtl' : '_ltr';
            $target = $path . '/cache/';
            $last_change = self::lastChange($source, $path, 'css');
            $temp = $target . self::prefix . 'plugins_main' . $ldir . self::css_suffix;
            
            if (!file_exists($temp) || $last_change > filemtime($temp)) {
                if (!self::writeCssCache($source, $temp, $path)) {
                    Message::msgError("Minify:: - Writing the file to <$target> failed!");
                    Debug::addMessage('errors', '<i>Exception</i>', 'Minify:: - Writing the file to <' . $target . '> failed!', 'session');
                }
            }
            
            return basename($temp);
        }
        
        /**
         * moduleCssCache
         *
         * @param string $path
         * @return string
         */
        public static function moduleCssCache(string $path): string
        {
            $source = in_array(Core::$language, array('ae', 'he', 'ir')) ?
                File::findFiles(THEMEBASE . '/modules/css/rtl/', array('fileTypes' => array('css'), 'level' => 0)) :
                File::findFiles(THEMEBASE . '/modules/css/', array('fileTypes' => array('css'), 'level' => 0));
            $ldir = in_array(Core::$language, array('ae', 'he', 'ir')) ? '_rtl' : '_ltr';
            
            $target = $path . '/cache/';
            $last_change = self::lastChange($source, $path, 'css');
            $temp = $target . self::prefix . 'modules_main' . $ldir . self::css_suffix;
            
            if (!file_exists($temp) || $last_change > filemtime($temp)) {
                if (!self::writeCssCache($source, $temp, $path)) {
                    Message::msgError("Minify:: - Writing the file to <$target> failed!");
                    Debug::addMessage('errors', '<i>Exception</i>', 'Minify:: - Writing the file to <' . $target . '> failed!', 'session');
                }
            }
            
            return basename($temp);
        }
        
        /**
         * pluginJsCache
         *
         * @param string $path
         * @return string
         */
        public static function pluginJsCache(string $path): string
        {
            $source = File::findFiles(THEMEBASE . '/plugins/js/', array('fileTypes' => array('js'), 'level' => 0));
            $target = $path . '/cache/';
            $last_change = self::lastChange($source, $path, 'js');
            $temp = $target . self::prefix . 'plugins_main' . self::js_suffix;
            
            if (!file_exists($temp) || $last_change > filemtime($temp)) {
                if (!self::writeJsCache($source, $temp, $path)) {
                    Message::msgError("Minify:: - Writing the file to <$target> failed!");
                    Debug::addMessage('errors', '<i>Exception</i>', 'Minify:: - Writing the file to <' . $target . '> failed!', 'session');
                }
            }
            
            return basename($temp);
        }
        
        /**
         * writeJsCache
         *
         * @param array $files
         * @param string $target
         * @param string $path
         * @return false|int
         */
        protected static function writeJsCache(array $files, string $target, string $path): false|int
        {
            
            $content = '';
            
            foreach ($files as $file) {
                $content .= file_get_contents($path . '/js/' . $file);
            }
            
            //$content = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $content);
            $content = preg_replace('/\/\*(?:[^*]|\*+[^*\/])*\*+\/|(?<![:\'"])\/\/.*/', '', $content);
            $content = str_replace(array(
                "\r\n",
                "\r",
                "\n",
                "\t",
                '  ',
                '    ',
                '    '
            ), '', $content);
            
            if (!file_exists($path . '/cache/')) {
                mkdir($path . '/cache/');
            }
            
            return file_put_contents($target, $content);
        }
        
        /**
         * moduleJsCache
         *
         * @param $path
         * @return string
         */
        public static function moduleJsCache($path): string
        {
            $source = File::findFiles(THEMEBASE . '/modules/js/', array('fileTypes' => array('js'), 'level' => 0));
            $target = $path . '/cache/';
            $last_change = self::lastChange($source, $path, 'js');
            $temp = $target . self::prefix . 'modules_main' . self::js_suffix;
            
            if (!file_exists($temp) || $last_change > filemtime($temp)) {
                if (!self::writeJsCache($source, $temp, $path)) {
                    Message::msgError("Minify:: - Writing the file to <$target> failed!");
                    Debug::addMessage('errors', '<i>Exception</i>', 'Minify:: - Writing the file to <' . $target . '> failed!', 'session');
                }
            }
            
            return basename($temp);
        }
        
        /**
         * getCacheFile
         *
         * @return string
         */
        public static function getCacheFile(): string
        {
            return self::$_cacheFile;
        }
        
        /**
         * setCacheFile
         *
         * @param string $cacheFile
         * @return void
         */
        public static function setCacheFile(string $cacheFile = ''): void
        {
            self::$_cacheFile = strlen($cacheFile) !== 0 ? $cacheFile : '';
        }
        
        /**
         * getCacheLifetime
         *
         * @return string
         */
        public static function getCacheLifetime(): string
        {
            return self::$_cacheLifetime;
        }
        
        /**
         * setCacheLifetime
         *
         * @param int $cacheLifetime
         * @return void
         */
        public static function setCacheLifetime(int $cacheLifetime = 0): void
        {
            self::$_cacheLifetime = !empty($cacheLifetime) ? $cacheLifetime : 0;
        }
        
        /**
         * setContent
         *
         * @param string $content
         * @param string $cacheDir
         * @return void
         */
        public static function setContent(string $content = '', string $cacheDir = ''): void
        {
            if (strlen(self::$_cacheFile) !== 0) {
                // remove oldest file if the limit of cache is reached
                if (File::getDirectoryFilesNumber($cacheDir) >= self::CACHE_LIMIT) {
                    File::removeDirectoryOldestFile($cacheDir);
                }
                
                // save the content to the cache file
                File::writeToFile(self::$_cacheFile, serialize($content));
            }
        }
        
        /**
         * getContent
         *
         * @param string $cacheFile
         * @param int $cacheLifetime
         * @return false|mixed|string
         */
        public static function getContent(string $cacheFile = '', int $cacheLifetime = 0): mixed
        {
            $result = '';
            $cacheContent = '';
            
            if (strlen($cacheFile) !== 0) {
                self::setCacheFile($cacheFile);
            }
            if (strlen($cacheLifetime) !== 0) {
                self::setCacheLifetime($cacheLifetime);
            }
            
            if (strlen(self::$_cacheFile) !== 0 && strlen(self::$_cacheLifetime) !== 0) {
                if (file_exists(self::$_cacheFile)) {
                    $cacheTime = self::$_cacheLifetime * 60;
                    if ((filesize(self::$_cacheFile) > 0) && ((time() - $cacheTime) < filemtime(self::$_cacheFile))) {
                        ob_start();
                        include self::$_cacheFile;
                        $cacheContent = ob_get_contents();
                        ob_end_clean();
                    }
                    $result = !empty($cacheContent) ? unserialize($cacheContent) : $cacheContent;
                }
            }
            return $result;
        }
    }