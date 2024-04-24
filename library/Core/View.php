<?php
    /**
     * View Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: View.php, v1.00 4/25/2023 1:28 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Debug\Debug;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\GeneralException;
    use Wojo\File\File;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class View
    {
        public array $properties;
        public string $template;
        public string $path;
        public string|null $meta;
        public string $ext = '.tpl.php';
        
        /**
         * View::__construct()
         *
         */
        public function __construct()
        {
            $this->properties = array();
        }
        
        /**
         * render
         *
         * @param string $viewName
         * @param string $path
         * @param bool $is_full
         * @param string $alt_path
         * @return void
         * @throws FileNotFoundException
         */
        public function render(string $viewName, string $path, bool $is_full = true, string $alt_path = ''): void
        {
            $this->template = $path . $viewName . $this->ext;
            try {
                if (!file_exists($this->template)) {
                    Debug::addMessage('errors', '<i>Exception</i>', 'filename ' . File::_fixPath($this->template) . ' not found', 'session');
                    throw new FileNotFoundException($this->template . ' template was not found');
                }
                Debug::addMessage('params', 'template', File::_fixPath($this->template), 'session');
                
                if ($is_full) {
                    $newPath = ($alt_path) ?: $path;
                    if (!file_exists($newPath . 'header' . $this->ext)) {
                        Debug::addMessage('errors', '<i>Exception</i>', 'filename ' . File::_fixPath($path . 'header' . $this->ext) . ' not found', 'session');
                        throw new FileNotFoundException($newPath . ' header was not found');
                    }
                    include_once($newPath . 'header' . $this->ext);
                }
                include_once($this->template);
                if ($is_full) {
                    $newPath = ($alt_path) ?: $path;
                    if (!file_exists($newPath . 'footer' . $this->ext)) {
                        Debug::addMessage('errors', '<i>Exception</i>', 'filename ' . File::_fixPath($path . 'footer' . $this->ext) . ' not found', 'session');
                        throw new FileNotFoundException($newPath . ' footer was not found');
                    }
                    include_once($newPath . 'footer' . $this->ext);
                }
            } catch (GeneralException $e) {
                Debug::addMessage('errors', '<i>Exception</i>', $e->getMessage(), 'session');
            }
        }
        
        /**
         * snippet
         *
         * @param string $viewName
         * @param string $path
         * @return false|string
         * @throws FileNotFoundException
         */
        public function snippet(string $viewName, string $path): false|string
        {
            $this->template = $path . $viewName . $this->ext;
            try {
                if (!file_exists($this->template)) {
                    Debug::addMessage('errors', '<i>Exception</i>', 'filename ' . File::_fixPath($this->template) . ' not found', 'session');
                    throw new FileNotFoundException($this->template . ' template was not found');
                }
                Debug::addMessage('params', 'template', File::_fixPath($this->template), 'session');
                ob_start();
                require($this->template);
                
            } catch (GeneralException $e) {
                Debug::addMessage('errors', '<i>Exception</i>', $e->getMessage(), 'session');
            }
            return ob_get_clean();
        }
        
        /**
         * View::__set()
         *
         * @param $key
         * @param $value
         * @return void
         */
        public function __set($key, $value)
        {
            $this->properties[$key] = $value;
        }
        
        /**
         * View::__get()
         *
         * @param $key
         * @return mixed
         */
        public function __get($key)
        {
            return $this->properties[$key];
        }
    }