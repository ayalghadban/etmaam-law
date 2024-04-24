<?php
    /**
     * ContainerExceptionInterface interface
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: ContainerExceptionInterface.php, v1.00 4/26/2023 11:14 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Psr;
    
    use Throwable;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    

    interface ContainerExceptionInterface extends Throwable{}