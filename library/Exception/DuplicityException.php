<?php
    /**
     * DuplicityException
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: DuplicityException.php, v1.00 4/25/2023 12:40 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Exception;
    
    use Exception;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    /**
     * Throws an exception when exists a duplicate symbol declaration
     */
    class DuplicityException extends Exception
    {
        /**
         * @param $message
         * @param $code
         * @param  Exception|null $previous
         */
        public function __construct($message, $code = 0, Exception $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
        
        /**
         * __toString
         *
         * @return string
         */
        public function __toString(): string
        {
            return sprintf("[%s](%s:%s): %s\n", __CLASS__, $this->getFile(), $this->getLine(), $this->getMessage());
        }
    }