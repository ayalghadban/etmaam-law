<?php
    /**
     * DatabaseException Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: DatabaseException.php, v1.00 4/25/2023 7:51 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Exception;
    
    use Exception;
    use PDOException;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class DatabaseException extends PDOException
    {
        
        /**
         * DatabaseException::__construct()
         *
         * @param $message
         * @param $code
         * @param Exception|null $previous
         */
        public function __construct($message, $code = 0, Exception $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
        
        /**
         * DatabaseException::__toString()
         *
         * @return string
         */
        public function __toString(): string
        {
            return sprintf("[%s](%s:%s): %s\n", __CLASS__, $this->getFile(), $this->getLine(), $this->getMessage());
        }
    }