<?php
    /**
     * PayPal Class
     *
     * @package CMS Pro
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: class_security.php, v2.00 2023-04-20 18:20:24 gewa Exp $
     */
    
    use Wojo\Container\Container;
    use Wojo\Exception\NotFoundException;
    
    if (!defined('_VALID_PHP')) {
        die('Direct access to this location is not allowed.');
    }
    
    class PayPal
    {

        public bool $follow_location = false;
        public bool $use_live = false;
        public int $timeout = 30;
        
        private $post_data = array();
        private $post_uri = '';
        private $response_status = '';
        private $response = '';
        
        const PAYPAL_HOST = 'www.paypal.com';
        const SANDBOX_HOST = 'www.sandbox.paypal.com';
        
        /**
         * curlPost
         *
         * @param $encoded_data
         * @return void
         * @throws NotFoundException
         * @throws Exception
         */
        protected function curlPost($encoded_data): void
        {
            $company = Container::instance()->get('core')->company;
            
            $uri = 'https://' . $this->getPaypalHost() . '/cgi-bin/webscr';
            $this->post_uri = $uri;
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            //curl_setopt($ch, CURLOPT_CAINFO, dirname(__file__) . "/cert/api_cert_chain.crt");
            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_USERAGENT, $company);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: ' . $company));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->follow_location);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            
            $this->response = curl_exec($ch);
            $this->response_status = strval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
            
            if ($this->response === false || $this->response_status == '0') {
                $errno = curl_errno($ch);
                $errstr = curl_error($ch);
                throw new Exception("cURL error: [$errno] $errstr");
            }
        }
        
        /**
         * getPaypalHost
         *
         * @return string
         */
        private function getPaypalHost(): string
        {
            return $this->use_live ? self::PAYPAL_HOST : self::SANDBOX_HOST;
        }
        
        /**
         * getPostUri
         *
         * @return string
         */
        public function getPostUri(): string
        {
            return $this->post_uri;
        }
        
        /**
         * getResponse
         *
         * @return string
         */
        public function getResponse(): string
        {
            return $this->response;
        }
        
        /**
         * getResponseStatus
         *
         * @return string
         */
        
        public function getResponseStatus(): string
        {
            return $this->response_status;
        }
        
        /**
         * getTextReport
         *
         * @return string
         */
        public function getTextReport(): string
        {
            // date and POST url
            $r = str_repeat('-', 80);
            $r .= "\n[" . date('m/d/Y g:i A') . '] - ' . $this->getPostUri();
            if ($this->use_curl) {
                $r .= " (curl)\n";
            } else {
                $r .= " (fsockopen)\n";
            }
            
            // HTTP Response
            $r .= str_repeat('-', 80);
            $r .= "\n{$this->getResponse()}\n";
            
            // POST vars
            $r .= str_repeat('-', 80);
            $r .= "\n";
            
            foreach ($this->post_data as $key => $value) {
                $r .= str_pad($key, 25) . "$value\n";
            }
            $r .= "\n\n";
            
            return $r;
        }
        
        /**
         * processIpn
         *
         * @param $post_data
         * @return bool
         * @throws NotFoundException
         * @throws Exception
         */
        
        public function processIpn($post_data = null): bool
        {
            $encoded_data = 'cmd=_notify-validate';
            
            if ($post_data === null) {
                // use raw POST data
                if (!empty($_POST)) {
                    $this->post_data = $_POST;
                    $encoded_data .= '&' . file_get_contents('php://input');
                } else {
                    throw new Exception('No POST data found.');
                }
            } else {
                // use provided data array
                $this->post_data = $post_data;
                
                foreach ($this->post_data as $key => $value) {
                    $encoded_data .= "&$key=" . urlencode($value);
                }
            }
            
            $this->curlPost($encoded_data);
            
            if (!str_contains($this->response_status, '200')) {
                throw new Exception('Invalid response status: ' . $this->response_status);
            }
            
            if (str_contains($this->response, 'VERIFIED')) {
                return true;
            } elseif (str_contains($this->response, 'INVALID')) {
                return false;
            } else {
                throw new Exception('Unexpected response from PayPal.');
            }
        }
        
        /**
         * requirePostMethod
         *
         * @return void
         * @throws Exception
         */
        public function requirePostMethod(): void
        {
            // require POST requests
            if ($_SERVER['REQUEST_METHOD'] && $_SERVER['REQUEST_METHOD'] != 'POST') {
                header('Allow: POST', true, 405);
                throw new Exception('Invalid HTTP request method.');
            }
        }
    }
