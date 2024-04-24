<?php
    
    namespace Wojo\Exception;
    
    use Exception;
    
    class ModelException extends Exception
    {
        /**
         * ModelException::__construct()
         *
         * @param $message
         * @param $code
         * @param  Exception|null  $previous
         */
        public function __construct($message, $code = 0, Exception $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
        
        /**
         * ModelException::__construct()
         *
         * @return string
         */
        public function __toString(): string
        {
            return sprintf("[%s](%s:%s): %s\n", __CLASS__, $this->getFile(), $this->getLine(), $this->getMessage());
        }
    }