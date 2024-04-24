<?php
    /**
     * Group
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: Group.php, v1.00 2023-05-05 10:12:05 gewa Exp $
     */
    
    namespace Wojo\Core;
    
    use Closure;
    use Wojo\Exception\RequestMethodException;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Group
    {
        
        private Router $router;
        private string $prefix;
        private Closure $closure;
        private ?Closure $before = null;
        private array $onlyuse = [];
        
        /**
         * @param string $prefix Route group prefix
         * @param Closure $closure Group definition
         * @param Router $router Router object
         */
        public function __construct(string $prefix, Closure $closure, Router $router)
        {
            $this->prefix = '/' . trim($prefix, '/\\');
            $this->closure = $closure;
            $this->router = $router;
        }
        
        /**
         * route
         *
         * @param string $verb The allowed route http method
         * @param string $path The route path
         * @param Closure $closure The route controller
         * @return Route
         * @throws RequestMethodException
         */
        public function route(string $verb, string $path, Closure $closure): Route
        {
            $route = $this->router->route($verb, $this->prefix . $path, $closure);
            
            // Set the hook
            if (null !== $this->before) {
                $route->before($this->before);
            }
            
            // Set the specific services to use
            if ([] !== $this->onlyuse) {
                $route->use(...$this->onlyuse);
            }
            
            return $route;
        }
        
        /**
         * get
         *
         * Shortcut to add route with GET method
         *
         * @param string $path The route path
         * @param array|Closure $closure The route controller
         * @return Route
         */
        public function get(string $path, array|Closure $closure): Route
        {
            $route = $this->router->get($this->prefix . $path, $closure);
            
            // Set the hook
            if (null !== $this->before) {
                $route->before($this->before);
            }
            
            // Set the specific services to use
            if ([] !== $this->onlyuse) {
                $route->use(...$this->onlyuse);
            }
            
            return $route;
        }
        
        /**
         * post
         *
         * Shortcut to add route with POST method
         *
         * @param string $path The route path
         * @param array|Closure $closure The route controller
         * @return Route
         */
        public function post(string $path, array|Closure $closure): Route
        {
            $route = $this->router->post($this->prefix . $path, $closure);
            
            // Set the hook
            if (null !== $this->before) {
                $route->before($this->before);
            }
            
            // Set the specific services to use
            if ([] !== $this->onlyuse) {
                $route->use(...$this->onlyuse);
            }
            
            return $route;
        }
        
        /**
         * match
         *
         * @param string $path
         * @param array|Closure $closure
         * @return void
         */
        public function match(string $path, array|Closure $closure): void
        {
            $this->get($path, $closure);
            $this->post($path, $closure);
        }
        
        /**
         * before
         *
         * Add a hook to exec before each route into the group
         *
         * @param Closure $closure Middleware closure
         * @return $this
         */
        public function before(Closure $closure): Group
        {
            $this->before = $closure;
            return $this;
        }
        
        /**
         * use
         *
         * Specify the services to use in this route
         *
         * @param string ...$names Service names separated by comma
         * @return $this
         */
        public function use(string ...$names): Group
        {
            $this->onlyuse = $names;
            return $this;
        }
        
        /**
         * __invoke
         *
         * Allow exec the class like a function
         * @return void
         */
        public function __invoke(): void
        {
            ($this->closure)($this);
        }
        
    }

