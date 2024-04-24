<?php
    /**
     * Request Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Request.php, v1.00 4/25/2023 1:28 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Request
    {
        
        private array $query;
        private array $body;
        private array $server;
        private array $cookies;
        private array $files;
        private array $params;
        private array $matches;
        public array $segments;
        public string $route;
        public string $controller;
        
        
        /**
         * @param array $query
         * @param array $body
         * @param array $server
         * @param array $cookies
         * @param array $files
         * @param array $params
         * @param array $matches
         * @param array $segments
         * @param string $route
         * @param string $controller
         */
        public function __construct(array $query, array $body, array $server, array $cookies, array $files, array $params, array $matches, array $segments, string $route, string $controller)
        {
            $this->query = $query;
            $this->body = $body;
            $this->server = $server;
            $this->cookies = $cookies;
            $this->files = $files;
            $this->params = $params;
            $this->matches = $matches;
            $this->segments = $segments;
            $this->route = $route;
            $this->controller = $controller;
        }
        
        /**
         * fromGlobals
         *
         * Create a Request object from default global params
         * @return Request
         */
        public static function fromGlobals(): Request
        {
            return new Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, [], [], [], '', '');
        }
        
        /**
         * getQuery
         *
         * @return array
         */
        public function getQuery(): array
        {
            return $this->query;
        }
        
        /**
         * getBody
         *
         * @return array
         */
        public function getBody(): array
        {
            return $this->body;
        }
        
        /**
         * getServer
         *
         * @return array
         */
        public function getServer(): array
        {
            return $this->server;
        }
        
        /**
         * getCookies
         *
         * @return array
         */
        public function getCookies(): array
        {
            return $this->cookies;
        }
        
        /**
         * getFiles
         *
         * @return array
         */
        public function getFiles(): array
        {
            return $this->files;
        }
        
        /**
         * getSegments
         *
         * @return array
         */
        public function getSegments(): array
        {
            return $this->segments;
        }
        
        /**
         * getRoute
         *
         * @return string
         */
        public function getRoute(): string
        {
            return $this->route;
        }
        
        /**
         * getParams
         *
         * @return array
         */
        public function getParams(): array
        {
            return $this->params;
        }
        
        /**
         * getParam
         *
         * @param string $name
         * @param $default
         * @return mixed
         */
        public function getParam(string $name, $default = null): mixed
        {
            return $this->params[$name] ?? $default;
        }
        
        
        /**
         * getMatches
         *
         * @return string|array
         */
        public function getMatches(): string|array
        {
            if ($this->matches and count($this->matches) == 1) {
                $match = $this->matches[0];
            } else {
                $match = $this->matches;
            }
            return $match;
        }
        
        /**
         * setQuery
         *
         * @param array $query
         * @return void
         */
        public function setQuery(array $query): void
        {
            $this->query = $query;
        }
        
        /**
         * setBody
         *
         * @param array $body
         * @return void
         */
        public function setBody(array $body): void
        {
            $this->body = $body;
        }
        
        /**
         * setServer
         *
         * @param array $server
         * @return void
         */
        public function setServer(array $server): void
        {
            $this->server = $server;
        }
        
        /**
         * setCookies
         *
         * @param array $cookies
         * @return void
         */
        public function setCookies(array $cookies): void
        {
            $this->cookies = $cookies;
        }
        
        /**
         * setFiles
         *
         * @param array $files
         * @return void
         */
        public function setFiles(array $files): void
        {
            $this->files = $files;
        }
        
        /**
         * setParams
         *
         * @param array $params
         * @return void
         */
        public function setParams(array $params): void
        {
            $this->params = $params;
        }
        
        /**
         * setParam
         *
         * @param string $name
         * @param $value
         * @return void
         */
        public function setParam(string $name, $value): void
        {
            $this->params[$name] = $value;
        }
        
        /**
         * setSegments
         *
         * @param $segments
         * @return void
         */
        public function setSegments($segments): void
        {
            $this->segments = $segments;
        }
        
        /**
         * setRoute
         *
         * @param $route
         * @return void
         */
        public function setRoute($route): void
        {
            $this->route = $route;
        }
        
        /**
         * setController
         *
         * @param $controller
         * @return void
         */
        public function setController($controller): void
        {
            $this->controller = $controller;
        }
        
        /**
         * unsetParam
         *
         * @param string $name
         * @return void
         */
        public function unsetParam(string $name): void
        {
            unset($this->params[$name]);
        }
        
        /**
         * setMatches
         *
         * @param mixed $matches
         * @return void
         */
        public function setMatches(mixed $matches): void
        {
            $this->matches = $matches;
        }
        
        /**
         * buildQuery
         *
         * @param string $uri
         * @param array $params
         * @return string
         */
        public function buildQuery(string $uri, array $params): string
        {
            return rtrim($uri, '/\\') . '/?' . http_build_query($params);
        }
        
    }