<?php
    /**
     * Response
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: Response.php, v1.00 2023-04-05 10:12:05 gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Response
    {
        
        
        private mixed $content;
        private int $status;
        private array $headers;
        
        /**
         * Create a response
         *
         * @param string $content Response content
         * @param int $status Response status
         * @param array $headers Headers array
         */
        public function __construct(string $content = '', int $status = 200, array $headers = [])
        {
            $this->content = $content;
            $this->status = $status;
            $this->headers = $headers;
        }
        
        /**
         * status
         *
         * Set the response status
         * @param int $code
         * @return $this
         */
        public function status(int $code): Response
        {
            $this->status = $code;
            return $this;
        }
        
        /**
         * header
         *
         * Add a http header to response
         * @param string $name
         * @param string $content
         * @return $this
         */
        public function header(string $name, string $content): Response
        {
            $this->headers[trim($name)] = trim($content);
            return $this;
        }
        
        /**
         * headers
         *
         * Add multiples http headers to respons
         * @param array $headers
         * @return $this
         */
        public function headers(array $headers): Response
        {
            foreach ($headers as $name => $content) {
                $this->header($name, $content);
            }
            
            return $this;
        }
        
        /**
         * write
         *
         * Add content to the response body
         * @param $content
         * @return $this
         */
        public function write($content): Response
        {
            $this->content .= $content;
            return $this;
        }
        
        /**
         * send
         *
         * Send response content
         * @param $content
         * @return void
         */
        public function send($content = ''): void
        {
            if ('' !== $content) {
                $this->write($content);
            }
            
            if (!headers_sent()) {
                $this->sendHeaders();
            }
            
            echo $this->content;
        }
        
        /**
         * json
         *
         * Send json content
         * @param mixed $data Response data
         * @param bool $encode If true, the data in encode to json
         * @return void
         */
        public function json(mixed $data, bool $encode = true): void
        {
            $this->header('Content-Type', 'application/json;charset=UTF-8');
            $data = $encode ? json_encode($data, JSON_PRETTY_PRINT) : $data;
            $this->send($data);
        }
        
        /**
         * clear
         *
         * Reset the response to default values
         * @return $this
         */
        public function clear(): Response
        {
            $this->content = '';
            $this->status = 200;
            $this->headers = [];
            
            return $this;
        }
        
        /**
         * sendHeaders
         *
         * Send the http headers
         * @return void
         */
        private function sendHeaders(): void
        {
            $http_status = new HttpStatus($this->status);
            $http_status->sendHttpStatus();
            
            foreach ($this->headers as $name => $content) {
                if (is_array($content)) {
                    foreach ($content as $key => $value) {
                        header(sprintf('%s: %s', $key, $value), false, $this->status);
                    }
                }
                header(sprintf('%s: %s', $name, $content), false, $this->status);
            }
        }
        
        /**
         * redirect
         *
         * Response a redirect
         * @param string $uri
         * @return void
         */
        public function redirect(string $uri): void
        {
            if (!strpos($uri, 'http://') && !strpos($uri, 'https://') && !strpos($uri, 'www.')) {
                $uri = '/' . trim($uri, '/\\');
            }
            $this->header('location', trim($uri, '/\\'))->status(302)->send();
        }
    }