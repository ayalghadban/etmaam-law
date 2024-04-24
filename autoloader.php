<?php
    /**
     * Autoloader
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: autoloader.php, v1.00 2023-04-05 10:12:05 gewa Exp $
     */
    
    namespace Wojo;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    /**
     *  use Wojo\Autoloader;
     *  require '/src/Core/Bootstrap.php';
     *  $autoloader = new Autoloader(__DIR__);
     *
     *  or get a singleton instance
     *
     *  $autoloader = Autoloader::instance();
     *  $autoloader->directory(ROOT);  // this sets the project folder
     *
     * Tell the Bootstrap where to find files for namespaces that you will use.
     *
     *  $autoloader->addNamespaces(array(
     *    'Core' => 'src',
     *    'Demo' => 'demo/src'
     *  ));
     *
     * $autoloader->register();
     */
    class Autoloader
    {
        /**
         * Singleton Instance of the Autoloader
         *
         * @var Autoloader|null
         */
        protected static ?Autoloader $instance = null;
        
        /**
         * @var array
         */
        protected array $prefixes = [];
        
        /**
         * Project directory
         *
         * @var string|null
         */
        protected ?string $directory = null;
        
        /**
         * Returns a single instance of the object
         *
         * @return Autoloader
         */
        public static function instance(): Autoloader
        {
            if (static::$instance === null) {
                static::$instance = new Autoloader();
            }
            
            return static::$instance;
        }
        
        /**
         * Constructor
         *
         * @param string|null $directory
         */
        public function __construct(string $directory = null)
        {
            $this->directory = $directory;
        }
        
        /**
         * Sets or gets the project directory
         *
         * @param string|null $directory
         * @return string|void
         */
        public function directory(string $directory = null)
        {
            if ($directory === null) {
                return $this->directory;
            }
            $this->directory = $directory;
        }
        
        /**
         * Register loader with SPL autoloader stack.
         *
         * @return boolean
         */
        public function register(): bool
        {
            return spl_autoload_register([$this, 'load']);
        }
        
        /**
         * Add a base directory for namespace prefix.
         *
         * $Autoloader->addNamespace('Wojo\Core','library');
         *
         * @param string $prefix
         * @param string $baseDirectory
         * @return void
         */
        public function addNamespace(string $prefix, string $baseDirectory): void
        {
            $prefix = trim($prefix, '\\') . '\\';
            $path = rtrim($baseDirectory, DIRECTORY_SEPARATOR) . '/';
            $this->prefixes[$prefix] = $this->directory . DIRECTORY_SEPARATOR . $path;
        }
        
        /**
         * Add base directories for namespace prefixes.
         *
         *  $Autoloader->addNamespaces(array(
         *      'Wojo' => 'wojo/src/'
         *      'Wojo\\Test' => 'wojo/tests/'
         *    ));
         *
         * @param array $namespaces
         * @return void
         */
        public function addNamespaces(array $namespaces): void
        {
            foreach ($namespaces as $namespace => $baseDirectory) {
                $this->addNamespace($namespace, $baseDirectory);
            }
        }
        
        /**
         * load
         *
         * Loads the class for the autoloader.
         * @param string $class
         * @return string|null
         */
        public function load(string $class): ?string
        {
            $prefix = $class;
            
            while (false !== $pos = strrpos($prefix, '\\')) {
                $prefix = substr($class, 0, $pos + 1);
                $relativeClass = substr($class, $pos + 1);
                
                if (isset($this->prefixes[$prefix])) {
                    $filename = $this->prefixes[$prefix] . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
                    if ($this->requireFile($filename)) {
                        return $filename;
                    }
                }
                
                $prefix = rtrim($prefix, '\\');
            }
            
            return null;
        }
        
        /**
         * requireFile
         *
         * Loads the required file
         * @param string $filename
         * @return bool
         */
        protected function requireFile(string $filename): bool
        {
            if (file_exists($filename)) {
                require $filename;
                
                return true;
            }
            
            return false;
        }
    }
