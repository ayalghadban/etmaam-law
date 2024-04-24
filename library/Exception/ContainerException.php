<?php
    /**
     * ContainerException Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: ContainerException.php, v1.00 4/28/2023 8:09 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Exception;
    
    use RuntimeException;
    use Wojo\Psr\ContainerExceptionInterface;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class ContainerException extends RuntimeException implements ContainerExceptionInterface{}