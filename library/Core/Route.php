<?php
    /**
     * Route
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Route.php, v1.00 4/25/2023 1:28 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Closure;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Route
    {
        
        private string $verb;
        private string $path;
        private Closure|array $closure;
        private ?Closure $before = null;
        private array $onlyuse = [];
        
        /**
         * Create route
         *
         * @param string $verb Route http method
         * @param string $path Route path
         * @param closure $closure Route controller
         */
        public function __construct(string $verb, string $path, $closure)
        {
            $this->verb = $verb;
            $this->path = $path;
            $this->closure = $closure;
        }
        
        /**
         * getPath
         *
         * Return the route path
         * @return string
         */
        public function getPath(): string
        {
            return $this->path;
        }
        
        /**
         * getMethod
         *
         * Return the route method
         * @return string
         */
        public function getMethod(): string
        {
            return $this->verb;
        }
        
        /**
         * getController
         *
         * Return the controller
         * @return Closure|array
         */
        public function getController(): Closure|array
        {
            return $this->closure;
        }
        
        /**
         * before
         *
         * Add a hook to exec before the route controller
         * @param Closure $closure
         * @return $this
         */
        public function before(Closure $closure): Route
        {
            $this->before = $closure;
            return $this;
        }
        
        /**
         * getHookBefore
         *
         * Return the hook
         * @return Closure
         */
        public function getHookBefore(): Closure
        {
            return $this->before;
        }
        
        /**
         * hasHookBefore
         *
         * Return true if the route has a hook
         * @return bool
         */
        public function hasHookBefore(): bool
        {
            return null !== $this->before;
        }
        
        /**
         * use
         *
         * Specify the services to use in this route
         * @param string ...$names Service names separated by comma
         * @return $this
         */
        public function use(string ...$names): Route
        {
            $this->onlyuse = $names;
            return $this;
        }
        
        /**
         * getRouteServices
         *
         * Return the list of service names for this route
         * @return array
         */
        public function getRouteServices(): array
        {
            return $this->onlyuse;
        }
    }

