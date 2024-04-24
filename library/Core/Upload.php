<?php
    /**
     * Upload Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Upload.php, v1.00 5/7/2023 3:22 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Debug\Debug;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Utility\Utility;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Upload
    {
        private static ?Upload $instance = null;
        public array $fileInfo = array();
        private int $maxSize;
        private string $allowedExt;
        
        /**
         * @param string|int $maxSize
         * @param string $allowedExt
         */
        private function __construct(string|int $maxSize, string $allowedExt)
        {
            $this->maxSize = $maxSize;
            $this->allowedExt = $allowedExt;
        }
        
        /**
         * instance
         *
         * @param string|int|null $maxSize
         * @param string|null $allowedExt
         * @return Upload
         */
        public static function instance(string|int $maxSize = null, string $allowedExt = null): Upload
        {
            if (!self::$instance) {
                self::$instance = new Upload($maxSize, $allowedExt);
            }
            
            return self::$instance;
        }
        
        /**
         * check
         *
         * @param string $uploadName
         * @return bool
         */
        public function check(string $uploadName): bool
        {
            if (array_key_exists($uploadName, $_FILES)) {
                $this->fileInfo['ext'] = substr(strrchr($_FILES[$uploadName]['name'], '.'), 1);
                $this->fileInfo['name'] = basename($_FILES[$uploadName]['name']);
                $this->fileInfo['xame'] = substr($_FILES[$uploadName]['name'], 0, strrpos($_FILES[$uploadName]['name'], '.'));
                $this->fileInfo['size'] = $_FILES[$uploadName]['size'];
                $this->fileInfo['temp'] = $_FILES[$uploadName]['tmp_name'];
                $this->fileInfo['type'] = $_FILES[$uploadName]['type'];
                $type = explode('/', $_FILES[$uploadName]['type']);
                $this->fileInfo['type_short'] = $type[0];
                
                if ($this->fileInfo['size'] > $this->maxSize) {
                    Message::$msgs['name'] = Language::$word->FU_ERROR10 . ' ' . File::getSize($this->maxSize);
                    Debug::addMessage('errors', '<i>Error</i>', 'Uploaded file is larger than allowed.', 'session');
                    return false;
                }
                
                if (strlen($this->allowedExt) == 0) {
                    Message::$msgs['name'] = Language::$word->FU_ERROR9; //no extension specified
                    Debug::addMessage('errors', '<i>Error</i>', 'Invalid file extension specified.', 'session');
                    return false;
                }
                
                $extensions = explode(',', $this->allowedExt);
                if (!in_array(strtolower($this->fileInfo['ext']), $extensions)) {
                    Message::$msgs['name'] = Language::$word->FU_ERROR8 . $this->allowedExt; //no extension specified
                    Debug::addMessage('errors', '<i>Error</i>', 'Invalid file extension specified.', 'session');
                    return false;
                }
                
                if (in_array(strtolower($this->fileInfo['ext']), array('jpg', 'png', 'bmp', 'gif', 'jpeg'))) {
                    if (!getimagesize($this->fileInfo['temp'])) {
                        Message::$msgs['name'] = Language::$word->FU_ERROR7; //invalid image
                        Debug::addMessage('errors', '<i>Error</i>', 'Invalid image detected.', 'session');
                        return false;
                    }
                }
                
                return true;
            }
            Message::$msgs['name'] = Language::$word->FU_ERROR11; //Either form not submitted or file/s not found
            Debug::addMessage('errors', '<i>Error</i>', 'Temp file could not be found.', 'session');
            return false;
            
        }
        
        /**
         * process
         *
         * @param string $name
         * @param string $dir
         * @param string $prefix
         * @param string|bool $file_name
         * @param bool $replace
         * @return void
         */
        public function process(string $name, string $dir, string $prefix = 'SOURCE_', string|bool $file_name = false, bool $replace = true): void
        {
            if (!is_dir($dir)) {
                Message::$msgs['dir'] = Language::$word->FU_ERROR12; //Directory doesn't exist!
            }
            if ($this->check($name)) {
                if (!$file_name) {
                    $this->fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $this->fileInfo['ext'];
                } else {
                    $this->fileInfo['fname'] = $file_name . '.' . $this->fileInfo['ext'];
                }
                if ($replace) {
                    while (file_exists($dir . $this->fileInfo['fname'])) {
                        $this->fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $this->fileInfo['ext'];
                    }
                }
                if (!move_uploaded_file($this->fileInfo['temp'], $dir . $this->fileInfo['fname'])) {
                    Message::$msgs['name'] = Language::$word->FU_ERROR13; //File not moved
                    Debug::addMessage('errors', '<i>Error</i>', 'File could not be moved from temp directory', 'session');
                }
            }
        }
    }