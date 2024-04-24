<?php
    /**
     * Services
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Services.php, v1.00 4/25/2023 1:28 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Closure;
    use InvalidArgumentException;
    use Wojo\Exception\BadNameException;
    use Wojo\Exception\DuplicityException;
    use Wojo\Exception\NotFoundException;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Services
    {
        
        private array $services = [];
        
        /**
         * register
         *
         * @param string $name Service name
         * @param Closure $closure Service definition
         * @return $this
         * @throws BadNameException
         * @throws DuplicityException
         */
        public function register(string $name, Closure $closure): Services
        {
            if (strpos($name, ' ')) {
                throw new BadNameException(sprintf('Whitespaces not allowed in name definition for service "%s".', $name));
            }
            
            if (in_array($name, get_class_methods($this))) {
                throw new InvalidArgumentException(sprintf('"%s" is a reserved name for an existent property of %s and can\'t be overwritten.', $name, __CLASS__));
            }
            
            if (array_key_exists($name, $this->services)) {
                throw new DuplicityException(sprintf('Already exists a service with name "%s".', $name));
            }
            
            $this->services[$name] = $closure;
            
            return $this;
        }
        
        /**
         * unregister
         *
         * Unregister one or multiple services by name
         * @param string ...$names
         * @return $this
         */
        public function unregister(string ...$names): Services
        {
            foreach ($names as $name) {
                unset($this->services[$name]);
            }
            
            return $this;
        }
        
        /**
         * has
         *
         * Return true if a service exists
         * @param string $name
         * @return bool
         */
        public function has(string $name): bool
        {
            return array_key_exists($name, $this->services);
        }
        
        /**
         * all
         *
         * Return all services array
         * @return array
         */
        public function all(): array
        {
            return $this->services;
        }
        
        /**
         * keys
         *
         * Return key names of available services
         * @return array
         */
        public function keys(): array
        {
            return array_keys($this->services);
        }
        
        /**
         * count
         *
         * Return the count of services
         * @return int
         */
        public function count(): int
        {
            return count($this->services);
        }
        
        /**
         * __call
         *
         * Allow to access the private services
         * @param string $name
         * @param array $params
         * @return mixed
         * @throws NotFoundException
         */
        public function __call(string $name, array $params)
        {
            if (!isset($this->services[$name]) && !is_callable($this->services[$name])) {
                throw new NotFoundException(sprintf('The request service "%s" wasn\'t found.', $name));
            }
            
            return call_user_func($this->services[$name], ...$params);
        }
        
        /**
         * __get
         *
         * Allow to access services in object context
         * @param string $name
         * @return mixed
         * @throws NotFoundException
         */
        public function __get(string $name)
        {
            if (!isset($this->services[$name])) {
                throw new NotFoundException(sprintf('The request service "%s" was not found.', $name));
            }
            
            $service = $this->services[$name];
            
            return $service();
        }
    }