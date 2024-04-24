<?php
    /**
     * ContainerInterface interface
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: ContainerInterface.php, v1.00 4/26/2023 11:14 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Psr;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    interface ContainerInterface
    {
        /**
         * get
         *
         * @param string $id
         * @return mixed
         */
        public function get(string $id): mixed;
        
        /**
         * has
         *
         * @param string $id
         * @return mixed
         */
        public function has(string $id): mixed;
    }