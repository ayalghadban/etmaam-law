<?php
    /**
     * Cron
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: Cron.php, v1.00 6/29/2023 9:52 AM Gewa Exp $
     *
     */
    
    use Stripe\Exception\ApiErrorException;
    use Wojo\Core\Cron;
    use Wojo\Exception\NotFoundException;
    
    const _WOJO = true;
    require_once '../init.php';
    
    try {
        Cron::run(1);
    } catch (\PHPMailer\PHPMailer\Exception|ApiErrorException|NotFoundException $e) {
        error_log($e->getMessage(), 3, 'cron.log');
    }