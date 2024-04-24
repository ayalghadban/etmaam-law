<?php
    
    /**
     * File.php
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: File.php, v1.00 4/26/2023 4:13 PM Gewa Exp $
     *
     */
    
    namespace Wojo\File;
    
    use DirectoryIterator;
    use FilesystemIterator;
    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;
    use Wojo\Date\Date;
    use Wojo\Debug\Debug;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Utility\Utility;
    use ZipArchive;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class File
    {
        /**
         * deleteMulti
         *
         * @param string $dir
         * @return void
         */
        public static function deleteMulti(string $dir): void
        {
            if (is_dir($dir)) {
                self::deleteRecursive($dir, true);
            } else {
                self::deleteFile($dir);
            }
        }
        
        /**
         * deleteRecursive
         *
         * @param string $dir
         * @param bool $removeParent
         * @return true
         */
        public static function deleteRecursive(string $dir = '', bool $removeParent = false): true
        {
            if (is_dir($dir)) {
                $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
                $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($ri as $file) {
                    $file->isDir() ? rmdir($file) : unlink($file);
                }
                if ($removeParent) {
                    self::deleteDirectory($dir);
                }
            }
            return true;
        }
        
        /**
         * deleteDirectory
         *
         * @param string $dir
         * @return bool
         */
        public static function deleteDirectory(string $dir = ''): bool
        {
            self::emptyDirectory($dir);
            return rmdir($dir);
        }
        
        /**
         * emptyDirectory
         *
         * @param string $dir
         * @return true
         */
        public static function emptyDirectory(string $dir = ''): true
        {
            foreach (glob($dir . '/*') as $file) {
                if (is_dir($file)) {
                    self::emptyDirectory($file);
                } else {
                    unlink($file);
                }
            }
            return true;
        }
        
        /**
         * deleteFile
         *
         * @param string $file
         * @return bool
         */
        public static function deleteFile(string $file = ''): bool
        {
            $result = false;
            if (is_file($file)) {
                $result = unlink($file);
            }
            self::_errorHandler('file-deleting-error', 'An error occurred while deleting the file {file}. ' . $file);
            return $result;
        }
        
        /**
         * makeDirectory
         *
         * @param string $dir
         * @return true|void
         */
        public static function makeDirectory(string $dir = '')
        {
            if (!file_exists($dir)) {
                if (false === mkdir($dir, 0755, true)) {
                    self::_errorHandler('directory-error', 'Directory not writable {dir} .' . $dir);
                }
                return true;
            }
        }
        
        /**
         * renameDirectory
         *
         * @param string $old
         * @param string $new
         * @return void
         */
        public static function renameDirectory(string $old = '', string $new = ''): void
        {
            if (file_exists($old)) {
                if (false === rename($old, $new)) {
                    self::_errorHandler('directory-error', 'Can\'t rename {dir}. ' . $new);
                }
            }
        }
        
        /**
         * isThemeDir
         *
         * @param string $theme_dir
         * @param string $default_dir
         * @return string
         */
        public static function isThemeDir(string $theme_dir, string $default_dir): string
        {
            
            return is_dir($theme_dir) ? $theme_dir : $default_dir;
        }
        
        /**
         * copyDirectory
         *
         * @param string $source
         * @param string $dest
         * @param int $permissions
         * @return bool
         */
        public static function copyDirectory(string $source, string $dest, int $permissions = 0755): bool
        {
            if (is_link($source)) {
                return symlink(readlink($source), $dest);
            }
            
            if (is_file($source)) {
                return copy($source, $dest);
            }
            
            if (!is_dir($dest)) {
                mkdir($dest, $permissions, true);
            }
            
            $dir = dir($source);
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                self::copyDirectory("$source/$entry", "$dest/$entry", $permissions);
            }
            
            $dir->close();
            return true;
        }
        
        /**
         * isDirectoryEmpty
         *
         * @param string $dir
         * @return bool
         */
        public static function isDirectoryEmpty(string $dir = ''): bool
        {
            if ($dir == '' || !is_readable($dir)) {
                return false;
            }
            $hd = opendir($dir);
            while (false !== ($entry = readdir($hd))) {
                if ($entry !== '.' && $entry !== '..') {
                    return false;
                }
            }
            closedir($hd);
            return true;
        }
        
        /**
         * getDirectoryFilesNumber
         *
         * @param string $dir
         * @return int
         */
        public static function getDirectoryFilesNumber(string $dir = ''): int
        {
            return count(glob($dir . '*'));
        }
        
        /**
         * removeDirectoryOldestFile
         *
         * @param string $dir
         * @return void
         */
        public static function removeDirectoryOldestFile(string $dir = ''): void
        {
            $oldestFileTime = date('Y-m-d H:i:s');
            $oldestFileName = '';
            if ($hdir = opendir($dir)) {
                while (false !== ($obj = readdir($hdir))) {
                    if ($obj == '.' || $obj == '..' || $obj == '.htaccess') {
                        continue;
                    }
                    $fileTime = date('Y-m-d H:i:s', filectime($dir . $obj));
                    if ($fileTime < $oldestFileTime) {
                        $oldestFileTime = $fileTime;
                        $oldestFileName = $obj;
                    }
                }
            }
            if (!empty($oldestFileName)) {
                self::deleteFile($dir . $oldestFileName);
            }
        }
        
        /**
         * findSubDirectories
         *
         * @param string $dir
         * @param bool $fullPath
         * @return array
         */
        public static function findSubDirectories(string $dir = '.', bool $fullPath = false): array
        {
            $subDirectories = array();
            $folder = dir($dir);
            while ($entry = $folder->read()) {
                if ($entry != '.' && $entry != '..' && is_dir($dir . $entry)) {
                    $subDirectories[] = ($fullPath ? $dir : '') . $entry;
                }
            }
            $folder->close();
            return $subDirectories;
        }
        
        /**
         * scanDirectory
         *
         * @param string $directory
         * @param array|null $options
         * @param string|null $sorting
         * @return false|array
         */
        public static function scanDirectory(string $directory, null|array $options, null|string $sorting): false|array
        {
            
            if (str_ends_with($directory, '/')) {
                $directory = substr($directory, 0, -1);
            }
            $base = UPLOADS;
            
            if (!file_exists($directory) || !is_dir($directory)) {
                self::_errorHandler('directory-error', 'Invalid directory selected {dir}. ' . $directory);
                return false;
                
            } elseif (is_readable($directory)) {
                $dirs = array();
                $files = array();
                
                $exclude = array(
                    'htaccess',
                    'git',
                    'php'
                );
                
                $dirfiles = new DirectoryIterator($directory);
                foreach ($dirfiles as $file) {
                    $path = $directory . '/' . $file->getBasename();
                    $real_path = (isset($options['showpath'])) ? $path : str_replace(UPLOADS, '', $path);
                    if ($file->isDot() or in_array($file, array('thumbs', 'backups'))) {
                        continue;
                    }
                    
                    if ($file->isDir()) {
                        $dirs[] = array(
                            'path' => $real_path,
                            'url' => self::_fixPath(str_replace(UPLOADS, '', $file->getBasename())),
                            'name' => str_replace('_', ' ', $file->getBasename()),
                            'kind' => 'directory',
                            'total' => iterator_count(new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS))
                        );
                    }
                    
                    if ($file->isFile()) {
                        if (isset($options['include'])) {
                            $filter = in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $options['include']);
                        } else {
                            $filter = !in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $exclude);
                        }
                        
                        if ($file->getBasename() != '.' && $file->getBasename() != '..' && $filter) {
                            $url = str_replace(self::_fixPath($base), '', self::_fixPath($file->getPathname()));
                            $files[] = array(
                                'path' => $real_path,
                                'url' => $url,
                                'name' => $file->getBasename(),
                                'simple' => pathinfo($file->getBasename(), PATHINFO_FILENAME),
                                'extension' => $file->getExtension(),
                                'dir' => pathinfo($real_path, PATHINFO_DIRNAME),
                                'mime' => self::getMimeType($file->getPathname()),
                                'is_image' => in_array(strtolower($file->getExtension()), array(
                                    'jpg',
                                    'jpeg',
                                    'svg',
                                    'png',
                                    'gif',
                                    'webp',
                                    'bmp'
                                )),
                                'ftime' => Date::doDate('short_date', date('d-m-Y', $file->getMTime())),
                                'size' => File::getSize($file->getSize()),
                                'kind' => 'file'
                            );
                        }
                    }
                }
                
                $data['directory'] = $dirs;
                $data['dirsize'] = count($dirs);
                $data['filesize'] = count($files);
                
                $data['files'] = match ($sorting) {
                    'date' => Utility::sortArray($files, 'ftime'),
                    'size' => Utility::sortArray($files, 'size'),
                    'name' => Utility::sortArray($files, 'name'),
                    'type' => Utility::sortArray($files, 'extension'),
                    default => $files,
                };
                
                return $data;
            } else {
                self::_errorHandler('directory-error', 'Directory not readable {dir}. ' . $directory);
                return false;
            }
        }
        
        /**
         * _fixPath
         *
         * @param string|null $path
         * @return string|null
         */
        public static function _fixPath(string|null $path): string|null
        {
            $path = str_replace('\\', '/', $path);
            return preg_replace('#/+#', '/', $path);
        }
        
        /**
         * getExtension
         *
         * @param string $path
         * @return string
         */
        public static function getExtension(string $path): string
        {
            return strtolower(pathinfo($path, PATHINFO_EXTENSION));
        }
        
        /**
         * getMimeType
         *
         * @param string $file
         * @return false|string
         */
        public static function getMimeType(string $file): false|string
        {
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $file);
            finfo_close($finfo);
            
            return $mtype;
        }
        
        /**
         * getSize
         *
         * @param string|int|float $size
         * @param int $precision
         * @return string
         */
        public static function getSize(string|int|float $size, int $precision = 2): string
        {
            $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            $step = 1024;
            $i = 0;
            while (($size / $step) > 0.9) {
                $size = $size / $step;
                $i++;
            }
            return round($size, $precision) . $units[$i];
        }
        
        /**
         * scanDirectoryRecursively
         *
         * @param string $directory
         * @param array $options
         * @return false|array
         */
        public static function scanDirectoryRecursively(string $directory, array $options = []): false|array
        {
            if (str_ends_with($directory, '/')) {
                $directory = substr($directory, 0, -1);
            }
            
            if (!file_exists($directory) || !is_dir($directory)) {
                self::_errorHandler('directory-error', 'Invalid directory selected {dir}. ' . $directory);
                return false;
                
            } elseif (is_readable($directory)) {
                $iterator = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
                $all_files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                
                $dirs = array();
                $files = array();
                $exclude = array(
                    'htaccess',
                    'git',
                    'php'
                );
                
                foreach ($all_files as $file) {
                    $path = $directory . '/' . $file->getBasename();
                    $real_path = isset($options['showpath']) ? $path : str_replace(UPLOADS, '', $path);
                    
                    if ($file->isDir()) {
                        $dirs[] = array(
                            'path' => $real_path,
                            'url' => str_replace(BASEPATH, '', $file->getPathname()) . '/',
                            'name' => str_replace('_', ' ', $file->getBasename()),
                            'kind' => 'directory',
                            'total' => iterator_count(new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS))
                        );
                    }
                    
                    if ($file->isFile()) {
                        if (isset($options['include'])) {
                            $filter = in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $options['include']);
                        } else {
                            $filter = !in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $exclude);
                        }
                        
                        if ($file->getBasename() != '.' && $file->getBasename() != '..' && $filter) {
                            $files[] = array(
                                'path' => $path,
                                'url' => self::_fixPath(str_replace(BASEPATH, '', $file->getPathname())),
                                'name' => $file->getBasename(),
                                'extension' => $file->getExtension(),
                                'is_image' => in_array($file->getExtension(), array(
                                    'jpg',
                                    'jpeg',
                                    'png',
                                    'gif',
                                    'webp',
                                    'bmp'
                                )),
                                'ftime' => Date::doDate('short_date', date('d-m-Y', $file->getMTime())),
                                'size' => File::getSize($file->getSize()),
                                'kind' => 'file'
                            );
                        }
                        
                    }
                }
                
                $data['directory'] = $dirs;
                $data['files'] = $files;
                return $data;
            } else {
                self::_errorHandler('directory-error', 'Directory not readable {dir}. ' . $directory);
                return false;
            }
        }
        
        /**
         * is_File
         *
         * @param string $file
         * @return bool
         */
        public static function is_File(string $file = ''): bool
        {
            return file_exists($file);
            
        }
        
        /**
         * getFile
         *
         * @param string $file
         * @return string|void
         */
        public static function getFile(string $file = '')
        {
            if (file_exists($file)) {
                return $file;
            } else {
                self::_errorHandler('file-loading-error', 'An error occurred while fetching file {file}. ' . $file);
            }
            
        }
        
        /**
         * loadFile
         *
         * @param string $file
         * @return false|string
         */
        public static function loadFile(string $file = ''): string|false
        {
            $content = file_get_contents($file);
            self::_errorHandler('file-loading-error', 'An error occurred while loading file {file}. ' . $file);
            return $content;
        }
        
        /**
         * copyFile
         *
         * @param string $src
         * @param string $dest
         * @return bool
         */
        public static function copyFile(string $src = '', string $dest = ''): bool
        {
            $result = copy($src, $dest);
            self::_errorHandler('file-coping-error', 'An error occurred while copying the file {source ' . $src . '} to {destination - ' . $dest . '}');
            return $result;
        }
        
        /**
         * findFiles
         *
         * @param string $dir
         * @param array $options
         * @return array
         */
        public static function findFiles(string $dir, array $options = []): array
        {
            $fileTypes = $options['fileTypes'] ?? array();
            $exclude = $options['exclude'] ?? array();
            $level = $options['level'] ?? -1;
            $returnType = $options['returnType'] ?? 'fileOnly';
            $filesList = self::_findFilesRecursive($dir, '', $fileTypes, $exclude, $level, $returnType);
            sort($filesList);
            return $filesList;
        }
        
        /**
         * _findFilesRecursive
         *
         * @param string $dir
         * @param $base
         * @param $fileTypes
         * @param $exclude
         * @param $level
         * @param string $returnType
         * @return array
         */
        protected static function _findFilesRecursive(string $dir, $base, $fileTypes, $exclude, $level, $returnType = 'fileOnly'): array
        {
            $list = array();
            if ($hdir = opendir($dir)) {
                while (($file = readdir($hdir)) !== false) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    $path = $dir . '/' . $file;
                    $isFile = is_file($path);
                    if (self::_validatePath($base, $file, $isFile, $fileTypes, $exclude)) {
                        if ($isFile) {
                            $list[] = ($returnType == 'fileOnly') ? $file : $path;
                        } else {
                            if ($level) {
                                $list = array_merge($list, self::_findFilesRecursive($path, $base . '/' . $file, $fileTypes, $exclude, $level - 1, $returnType));
                            }
                        }
                    }
                }
            }
            closedir($hdir);
            return $list;
        }
        
        /**
         * _validatePath
         *
         * @param string $base
         * @param string $file
         * @param $isFile
         * @param array $fileTypes
         * @param array $exclude
         * @return bool
         */
        protected static function _validatePath(string $base, string $file, $isFile, array $fileTypes = [], array $exclude = []): bool
        {
            foreach ($exclude as $e) {
                if ($file === $e || str_starts_with($base . '/' . $file, $e)) {
                    return false;
                }
            }
            if (!$isFile || count($fileTypes) === 0) {
                return true;
            }
            if (($type = pathinfo($file, PATHINFO_EXTENSION)) !== '') {
                return in_array($type, $fileTypes);
            } else {
                return false;
            }
        }
        
        /**
         * scanFiles
         *
         * @param string $dir
         * @param string $extension
         * @return false|array
         */
        public static function scanFiles(string $dir, string $extension): false|array
        {
            $dirs = glob($dir . '*', GLOB_ONLYDIR);
            $files = array();
            foreach ($dirs as $d) {
                $file = glob($d . '/' . $extension);
                if (count($file)) {
                    $files = array_merge($files, $file);
                }
            }
            return $files;
        }
        
        /**
         * getThemes
         *
         * @param string $dir
         * @return array
         */
        public static function getThemes(string $dir): array
        {
            $directories = glob($dir . '/*', GLOB_ONLYDIR);
            $themes = [];
            if ($directories) {
                foreach ($directories as $row) {
                    $themes[] = basename($row);
                }
            }
            return $themes;
        }
        
        /**
         * getMailerTemplates
         *
         * @return false|array
         */
        public static function getMailerTemplates(): false|array
        {
            $path = BASEPATH . 'themes/mailer/';
            return glob($path . '*.{tpl.php}', GLOB_BRACE);
        }
        
        /**
         * getFileType
         *
         * @param string $filename
         * @return string
         */
        public static function getFileType(string $filename): string
        {
            $ext = File::getExtension($filename);
            
            return match ($ext) {
                'mp3', 'wav', 'aiff', 'ogg', 'wma', 'flac', 'm4a', 'm4b', 'm4p' => 'audio.svg',
                'jpg', 'png', 'jpeg', 'bmp', 'ai', 'psd' => 'images.svg',
                'txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf' => 'documents.svg',
                'mov', 'avi', 'flv', 'mp4', 'mpeg', 'wmv' => 'videos.svg',
                'zip', 'rar' => 'compressed.svg',
                default => 'default.svg',
            };
        }
        
        /**
         * getFileSize
         *
         * @param string $file
         * @param string $units
         * @param bool $print
         * @return int|string
         */
        public static function getFileSize(string $file, string $units = 'kb', bool $print = false): int|string
        {
            if (!$file || !is_file($file)) {
                return 0;
            }
            $showunit = $print ? $units : null;
            $filesSize = filesize($file);
            return match (strtolower($units)) {
                'g', 'gb' => number_format($filesSize / (1024 * 1024 * 1024), 2) . $showunit,
                'm', 'mb' => number_format($filesSize / (1024 * 1024), 2) . $showunit,
                'k', 'kb' => number_format($filesSize / 1024, 2) . $showunit,
                default => number_format($filesSize, 2) . $showunit,
            };
        }
        
        /**
         * directorySize
         *
         * @param string $dir
         * @param bool $format
         * @return int|string
         */
        public static function directorySize(string $dir, bool $format = false): int|string
        {
            $btotal = 0;
            $dir = realpath($dir);
            if ($dir !== false) {
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)) as $obj) {
                    $btotal += $obj->getSize();
                }
            }
            return $format ? self::getSize($btotal) : $btotal;
        }
        
        /**
         * unzip
         *
         * @param string $archive
         * @param string $dir
         * @return bool
         */
        public static function unzip(string $archive, string $dir): bool
        {
            if (!class_exists('ZipArchive')) {
                self::_errorHandler('zip-error', 'Your PHP version does not support unzip functionality. ' . $archive);
                return false;
            }
            
            if (str_ends_with($dir, '/')) {
                $dir = substr($dir, 0, -1);
            }
            
            if (!file_exists($archive) || !is_dir($dir)) {
                self::_errorHandler('directory-error', 'Invalid directory or file selected {dir}. ' . $dir);
                return false;
                
            } elseif (is_writeable($dir . '/')) {
                $zip = new ZipArchive;
                if ($zip->open($archive) === true) {
                    $zip->extractTo($dir);
                    $zip->close();
                } else {
                    self::_errorHandler('zip-error', 'Cannot read .zip archive. ' . $archive);
                }
                
                return true;
            } else {
                self::_errorHandler('directory-error', 'Directory not writeable {dir}. ' . $dir);
                return false;
            }
        }
        
        /**
         * upload
         *
         * @param string $uploadName
         * @param string|int|null $maxSize
         * @param string|null $allowedExt
         * @return false|array
         */
        public static function upload(string $uploadName, string|int $maxSize = null, string $allowedExt = null): false|array
        {
            if (array_key_exists($uploadName, $_FILES)) {
                $fileInfo['ext'] = substr(strrchr($_FILES[$uploadName]['name'], '.'), 1);
                $fileInfo['name'] = basename($_FILES[$uploadName]['name']);
                $fileInfo['xame'] = substr($_FILES[$uploadName]['name'], 0, strrpos($_FILES[$uploadName]['name'], '.'));
                $fileInfo['size'] = $_FILES[$uploadName]['size'];
                $fileInfo['temp'] = $_FILES[$uploadName]['tmp_name'];
                
                if ($fileInfo['size'] > $maxSize) {
                    Message::$msgs['name'] = Language::$word->FU_ERROR10 . ' ' . File::getSize($maxSize);
                    return false;
                }
                if (strlen($allowedExt) == 0) {
                    Message::$msgs['name'] = Language::$word->FU_ERROR9; //no extension specified
                    return false;
                }
                $exts = explode(',', $allowedExt);
                if (!in_array(strtolower($fileInfo['ext']), $exts)) {
                    Message::$msgs['name'] = Language::$word->FU_ERROR8 . $allowedExt; //no extension specified
                    return false;
                }
                if (in_array(strtolower($fileInfo['ext']), array('jpg', 'png', 'bmp', 'gif', 'jpeg'))) {
                    if (!getimagesize($fileInfo['temp'])) {
                        Message::$msgs['name'] = Language::$word->FU_ERROR7; //invalid image
                        return false;
                    }
                }
                return $fileInfo;
            }
            return false;
        }
        
        /**
         * process
         *
         * @param array $result
         * @param string $dir
         * @param string $prefix
         * @param string|bool $filename
         * @param bool $replace
         * @param bool $ext
         * @return array|void
         */
        public static function process(array $result, string $dir, string $prefix = 'SOURCE_', string|bool $filename = false, bool $replace = true, bool $ext = false)
        {
            if (!is_dir($dir)) {
                Message::$msgs['dir'] = Language::$word->FU_ERROR12; //Directory doesn't exist!
            }
            $is_ext = $ext ? '.' . $result['ext'] : '';
            if (!$filename) {
                $fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $result['ext'];
            } else {
                $fileInfo['fname'] = $filename . $is_ext;
            }
            if ($replace) {
                while (file_exists($dir . $fileInfo['fname'])) {
                    $fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $result['ext'];
                }
            }
            if (move_uploaded_file($result['temp'], $dir . $fileInfo['fname'])) {
                return array_merge($result, $fileInfo);
            } else {
                Debug::addMessage('errors', '<i>Error</i>', 'File could not be moved from temp directory', 'session');
            }
        }
        
        /**
         * createShortenName
         *
         * @param string $file
         * @param int $lengthFirst
         * @param int $lengthLast
         * @return array|string|null
         */
        public static function createShortenName(string $file, int $lengthFirst = 10, int $lengthLast = 10): array|string|null
        {
            return preg_replace("/(?<=.{{$lengthFirst}})(.+)(?=.{{$lengthLast}})/", '...', $file);
        }
        
        /**
         * readIni
         *
         * @param string|null $file
         * @return mixed
         */
        public static function readIni(string $file = null): mixed
        {
            if (strlen($file) === 0) {
                self::_errorHandler('directory-error', 'File does not exists. ' . $file);
                return false;
            }
            $result = parse_ini_file(realpath($file), true);
            $result = json_encode($result);
            return json_decode($result);
        }
        
        /**
         * writeIni
         *
         * @param string|null $file
         * @param array $data
         * @param bool $sections
         * @return true
         */
        public static function writeIni(string $file = null, array $data = array(), bool $sections = true): true
        {
            $content = null;
            
            if ($sections) {
                foreach ($data as $section => $rows) {
                    $content .= '[' . $section . ']' . PHP_EOL;
                    $content = self::writeIniData($rows, $content);
                    $content .= PHP_EOL;
                }
            } else {
                $content = self::writeIniData($data, $content);
            }
            
            return self::writeToFile($file, trim($content));
        }
        
        /**
         * writeIniData
         *
         * @param mixed $rows
         * @param string $content
         * @return string
         */
        private static function writeIniData(mixed $rows, string $content): string
        {
            foreach ($rows as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $v) {
                        $content .= $key . '[] = ' . (is_numeric($v) ? $v : '"' . $v . '"') . PHP_EOL;
                    }
                } elseif (strlen($val) === 0) {
                    $content .= $key . ' = ' . PHP_EOL;
                } else {
                    $content .= $key . ' = ' . (is_numeric($val) ? $val : '"' . $val . '"') . PHP_EOL;
                }
            }
            return $content;
        }
        
        /**
         * writeToFile
         *
         * @param string $file
         * @param string $content
         * @return true
         */
        public static function writeToFile(string $file = '', string $content = ''): true
        {
            file_put_contents($file, $content);
            self::_errorHandler('file-writing-error', 'An error occurred while writing to file {file}. ' . $file);
            return true;
        }
        
        /**
         * download
         *
         * @param string $fileLocation
         * @param string $fileName
         * @param int $maxSpeed
         * @return false|void
         */
        public static function download(string $fileLocation, string $fileName, int $maxSpeed = 1024)
        {
            if (connection_status() != 0) {
                return (false);
            }
            $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
            $fileTypes = array();
            $fileTypes['pdf'] = 'application/pdf';
            $fileTypes['txt'] = 'text/plain';
            $fileTypes['exe'] = 'application/octet-stream';
            $fileTypes['zip'] = 'application/zip';
            $fileTypes['doc'] = 'application/msword';
            $fileTypes['xls'] = 'application/vnd.ms-excel';
            $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
            $fileTypes['gif'] = 'image/gif';
            $fileTypes['png'] = 'image/png';
            $fileTypes['jpeg'] = 'image/jpg';
            $fileTypes['jpg'] = 'image/jpg';
            $fileTypes['rar'] = 'application/rar';
            
            $fileTypes['ra'] = 'audio/x-pn-realaudio';
            $fileTypes['ram'] = 'audio/x-pn-realaudio';
            $fileTypes['ogg'] = 'audio/x-pn-realaudio';
            
            $fileTypes['wav'] = 'video/x-msvideo';
            $fileTypes['wmv'] = 'video/x-msvideo';
            $fileTypes['avi'] = 'video/x-msvideo';
            $fileTypes['asf'] = 'video/x-msvideo';
            $fileTypes['divx'] = 'video/x-msvideo';
            
            $fileTypes['mp3'] = 'audio/mpeg';
            $fileTypes['mp4'] = 'audio/mpeg';
            $fileTypes['mpeg'] = 'video/mpeg';
            $fileTypes['mpg'] = 'video/mpeg';
            $fileTypes['mpe'] = 'video/mpeg';
            $fileTypes['mov'] = 'video/quicktime';
            $fileTypes['swf'] = 'video/quicktime';
            $fileTypes['3gp'] = 'video/quicktime';
            $fileTypes['m4a'] = 'video/quicktime';
            $fileTypes['aac'] = 'video/quicktime';
            $fileTypes['m3u'] = 'video/quicktime';
            
            $contentType = $fileTypes[$extension];
            
            header('Cache-Control: public');
            header("Content-Transfer-Encoding: binary\n");
            header('Content-Type: ' . $contentType);
            
            $contentDisposition = 'attachment';
            
            if (str_contains($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
                $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
            }
            header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
            
            header('Accept-Ranges: bytes');
            $range = 0;
            $size = filesize($fileLocation);
            
            if (isset($_SERVER['HTTP_RANGE'])) {
                list(, $range) = explode('=', $_SERVER['HTTP_RANGE']);
                str_replace($range, '-', $range);
                $size2 = $size - 1;
                $new_length = $size - $range;
                header('HTTP/1.1 206 Partial Content');
                header("Content-Length: $new_length");
                header("Content-Range: bytes $range$size2/$size");
            } else {
                $size2 = $size - 1;
                header("Content-Range: bytes 0-$size2/$size");
                header('Content-Length: ' . $size);
            }
            
            if ($size == 0) {
                die('Zero byte file! Aborting download');
            }
            
            $fp = fopen("$fileLocation", 'rb');
            
            fseek($fp, $range);
            
            while (!feof($fp) and (connection_status() == 0)) {
                set_time_limit(0);
                print (fread($fp, 1024 * $maxSpeed));
                flush();
                ob_flush();
                sleep(1);
            }
            fclose($fp);
            exit;
            
            return ((connection_status() == 0) and !connection_aborted());
        }
        
        /**
         * parseSQL
         *
         * @param string $content
         * @return array
         */
        public static function parseSQL(string $content): array
        {
            
            $sqlList = array();
            $lines = explode("\n", file_get_contents($content));
            $query = '';
            
            foreach ($lines as $sql_line) {
                $sql_line = trim($sql_line);
                if ($sql_line === '') {
                    continue;
                } else {
                    if (str_starts_with($sql_line, '--')) {
                        continue;
                    } else {
                        if (str_starts_with($sql_line, '#')) {
                            continue;
                        }
                    }
                    $query .= $sql_line;
                    if (preg_match('/(.*);/', $sql_line)) {
                        $query = trim($query);
                        $query = substr($query, 0, strlen($query) - 1);
                        $sqlList[] = $query . ';';
                        $query = '';
                    }
                }
                
            }
            return $sqlList;
        }
        
        /**
         * exists
         *
         * @param string $file
         * @return bool
         */
        public static function exists(string $file): bool
        {
            return file_exists($file);
        }
        
        /**
         * validateDirectory
         *
         * @param string $basepath
         * @param string|null $userpath
         * @return string
         */
        public static function validateDirectory(string $basepath, string|null $userpath): string
        {
            
            $realBase = realpath($basepath);
            $userpath = $basepath . $userpath;
            $realUserPath = realpath($userpath);
            
            return ($realUserPath === false || !str_starts_with($realUserPath, $realBase)) ? $basepath : $userpath;
        }
        
        /**
         * _errorHandler
         *
         * @param string $msgType
         * @param string $msg
         * @return void
         */
        private static function _errorHandler(string $msgType = '', string $msg = ''): void
        {
            
            $err = error_get_last();
            if (isset($err['message']) && $err['message'] != '') {
                $lastError = $err['message'] . ' | file: ' . $err['file'] . ' | line: ' . $err['line'];
                $errorMsg = ($lastError) ? : $msg;
                Debug::addMessage('errors', $msgType, $errorMsg, 'session');
            }
        }
    }