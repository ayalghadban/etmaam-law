<?php
    /**
     * routes
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $router
     * @var object $services
     * @version $Id: routes.php, v1.00 6/13/2023 9:15 AM Gewa Exp $
     *
     */
    
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    $router->post('/plugin/newsletter/action', ['Wojo\Controller\Front\Plugin\Newsletter\NewsletterController', 'action']);
    // Poll
    $router->post('/plugin/poll/action', ['Wojo\Controller\Front\Plugin\Poll\PollController', 'action']);