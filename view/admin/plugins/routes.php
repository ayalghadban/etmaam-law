<?php
    /**
     * Routes
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $group
     * @version $Id: routes.php, v1.00 2023-05-05 10:12:05 gewa Exp $
     */
    if (!defined('_WOJO'))
        die('Direct access to this location is not allowed.');
    
    //Poll
    $group->get('/plugins/poll', ['Wojo\Controller\Admin\Plugin\Poll\PollController', 'index']);
    $group->get('/plugins/poll/edit/(\d+)', ['Wojo\Controller\Admin\Plugin\Poll\PollController', 'edit']);
    $group->get('/plugins/poll/new', ['Wojo\Controller\Admin\Plugin\Poll\PollController', 'new']);
    $group->post('/plugins/poll/action', ['Wojo\Controller\Admin\Plugin\Poll\PollController', 'action']);
    
    //Donate
    $group->get('/plugins/donate', ['Wojo\Controller\Admin\Plugin\Donate\DonateController', 'index']);
    $group->get('/plugins/donate/edit/(\d+)', ['Wojo\Controller\Admin\Plugin\Donate\DonateController', 'edit']);
    $group->get('/plugins/donate/new', ['Wojo\Controller\Admin\Plugin\Donate\DonateController', 'new']);
    $group->match('/plugins/donate/action', ['Wojo\Controller\Admin\Plugin\Donate\DonateController', 'action']);
    
    //Twitter
    $group->get('/plugins/twitter', ['Wojo\Controller\Admin\Plugin\Twitter\TwitterController', 'index']);
    $group->post('/plugins/twitter/action', ['Wojo\Controller\Admin\Plugin\Twitter\TwitterController', 'action']);
    
    //Newsletter
    $group->get('/plugins/newsletter', ['Wojo\Controller\Admin\Plugin\Newsletter\NewsletterController', 'index']);
    $group->get('/plugins/newsletter/action', ['Wojo\Controller\Admin\Plugin\Newsletter\NewsletterController', 'action']);
    
    //Rss
    $group->get('/plugins/rss', ['Wojo\Controller\Admin\Plugin\Rss\RssController', 'index']);
    $group->get('/plugins/rss/edit/(\d+)', ['Wojo\Controller\Admin\Plugin\Rss\RssController', 'edit']);
    $group->get('/plugins/rss/new', ['Wojo\Controller\Admin\Plugin\Rss\RssController', 'new']);
    $group->post('/plugins/rss/action', ['Wojo\Controller\Admin\Plugin\Rss\RssController', 'action']);
    
    //Carousel Player
    $group->get('/plugins/carousel', ['Wojo\Controller\Admin\Plugin\Carousel\CarouselController', 'index']);
    $group->get('/plugins/carousel/edit/(\d+)', ['Wojo\Controller\Admin\Plugin\Carousel\CarouselController', 'edit']);
    $group->get('/plugins/carousel/new', ['Wojo\Controller\Admin\Plugin\Carousel\CarouselController', 'new']);
    $group->match('/plugins/carousel/action', ['Wojo\Controller\Admin\Plugin\Carousel\CarouselController', 'action']);
    
    //Background Video
    $group->get('/plugins/background', ['Wojo\Controller\Admin\Plugin\Background\BackgroundController', 'index']);
    $group->get('/plugins/background/edit/(\d+)', ['Wojo\Controller\Admin\Plugin\Background\BackgroundController', 'edit']);
    $group->get('/plugins/background/new', ['Wojo\Controller\Admin\Plugin\Background\BackgroundController', 'new']);
    $group->match('/plugins/background/action', ['Wojo\Controller\Admin\Plugin\Background\BackgroundController', 'action']);
    
    //Universal Slider
    $group->get('/plugins/slider', ['Wojo\Controller\Admin\Plugin\Slider\SliderController', 'index']);
    $group->get('/plugins/slider/edit/(\d+)', ['Wojo\Controller\Admin\Plugin\Slider\SliderController', 'edit']);
    $group->get('/plugins/slider/builder/(\d+)', ['Wojo\Controller\Admin\Plugin\Slider\SliderController', 'builder']);
    $group->get('/plugins/slider/new', ['Wojo\Controller\Admin\Plugin\Slider\SliderController', 'new']);
    $group->match('/plugins/slider/action', ['Wojo\Controller\Admin\Plugin\Slider\SliderController', 'action']);
    
    //Universal Slider
    $group->get('/plugins/event', ['Wojo\Controller\Admin\Plugin\Event\EventController', 'index']);
    $group->post('/plugins/event/action', ['Wojo\Controller\Admin\Plugin\Event\EventController', 'action']);