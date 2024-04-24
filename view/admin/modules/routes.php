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
    
    use Wojo\File\File;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    //Adblock
    $group->get('/modules/adblock', ['Wojo\Controller\Admin\Module\Adblock\AdblockController', 'index']);
    $group->get('/modules/adblock/edit/(\d+)', ['Wojo\Controller\Admin\Module\Adblock\AdblockController', 'edit']);
    $group->get('/modules/adblock/new', ['Wojo\Controller\Admin\Module\Adblock\AdblockController', 'new']);
    $group->post('/modules/adblock/action', ['Wojo\Controller\Admin\Module\Adblock\AdblockController', 'action']);
    
    //Gallery
    $group->get('/modules/gallery', ['Wojo\Controller\Admin\Module\Gallery\GalleryController', 'index']);
    $group->get('/modules/gallery/edit/(\d+)', ['Wojo\Controller\Admin\Module\Gallery\GalleryController', 'edit']);
    $group->get('/modules/gallery/new', ['Wojo\Controller\Admin\Module\Gallery\GalleryController', 'new']);
    $group->get('/modules/gallery/photos/(\d+)', ['Wojo\Controller\Admin\Module\Gallery\GalleryController', 'photos']);
    $group->match('/modules/gallery/action', ['Wojo\Controller\Admin\Module\Gallery\GalleryController', 'action']);
    
    //Comments
    $group->get('/modules/comments', ['Wojo\Controller\Admin\Module\Comment\CommentController', 'index']);
    $group->get('/modules/comments/settings', ['Wojo\Controller\Admin\Module\Comment\CommentController', 'settings']);
    $group->match('/modules/comments/action', ['Wojo\Controller\Admin\Module\Comment\CommentController', 'action']);
    
    //Events
    $group->get('/modules/events', ['Wojo\Controller\Admin\Module\Event\EventController', 'index']);
    $group->get('/modules/events/grid', ['Wojo\Controller\Admin\Module\Event\EventController', 'index']);
    $group->get('/modules/events/edit/(\d+)', ['Wojo\Controller\Admin\Module\Event\EventController', 'edit']);
    $group->get('/modules/events/new', ['Wojo\Controller\Admin\Module\Event\EventController', 'new']);
    $group->post('/modules/events/action', ['Wojo\Controller\Admin\Module\Event\EventController', 'action']);
    
    //Faq
    $group->get('/modules/faq', ['Wojo\Controller\Admin\Module\Faq\FaqController', 'index']);
    $group->get('/modules/faq/edit/(\d+)', ['Wojo\Controller\Admin\Module\Faq\FaqController', 'edit']);
    $group->get('/modules/faq/new', ['Wojo\Controller\Admin\Module\Faq\FaqController', 'new']);
    $group->get('/modules/faq/categories', ['Wojo\Controller\Admin\Module\Faq\FaqController', ' ']);
    $group->get('/modules/faq/category/(\d+)', ['Wojo\Controller\Admin\Module\Faq\FaqController', 'categoryEdit']);
    $group->post('/modules/faq/action', ['Wojo\Controller\Admin\Module\Faq\FaqController', 'action']);
    
    //Maps
    $group->get('/modules/maps', ['Wojo\Controller\Admin\Module\Map\MapController', 'index']);
    $group->get('/modules/maps/edit/(\d+)', ['Wojo\Controller\Admin\Module\Map\MapController', 'edit']);
    $group->get('/modules/maps/new', ['Wojo\Controller\Admin\Module\Map\MapController', 'new']);
    $group->match('/modules/maps/action', ['Wojo\Controller\Admin\Module\Map\MapController', 'action']);
    
    //Timeline
    $group->get('/modules/timeline', ['Wojo\Controller\Admin\Module\Timeline\TimelineController', 'index']);
    $group->get('/modules/timeline/edit/(\d+)', ['Wojo\Controller\Admin\Module\Timeline\TimelineController', 'edit']);
    $group->get('/modules/timeline/new', ['Wojo\Controller\Admin\Module\Timeline\TimelineController', 'new']);
    $group->get('/modules/timeline/items/(\d+)', ['Wojo\Controller\Admin\Module\Timeline\TimelineController', 'customItems']);
    $group->get('/modules/timeline/inew/(\d+)', ['Wojo\Controller\Admin\Module\Timeline\TimelineController', 'customNew']);
    $group->get('/modules/timeline/iedit/(\d+)/(\d+)', ['Wojo\Controller\Admin\Module\Timeline\TimelineController', 'customEdit']);
    $group->post('/modules/timeline/action', ['Wojo\Controller\Admin\Module\Timeline\TimelineController', 'action']);
    
    //Forms
    if (File::is_File(FMODPATH . 'forms/index.tpl.php')) {
        $group->get('/modules/forms', ['Wojo\Controller\Admin\Module\Form\FormController', 'index']);
        $group->get('/modules/forms/edit/(\d+)', ['Wojo\Controller\Admin\Module\Form\FormController', 'edit']);
        $group->get('/modules/forms/new', ['Wojo\Controller\Admin\Module\Form\FormController', 'new']);
        $group->get('/modules/forms/design/(\d+)', ['Wojo\Controller\Admin\Module\Form\FormController', 'design']);
        $group->get('/modules/forms/view/(\d+)', ['Wojo\Controller\Admin\Module\Form\FormController', 'view']);
        $group->match('/modules/forms/action', ['Wojo\Controller\Admin\Module\Form\FormController', 'action']);
    }
    
    //Blog
    if (File::is_File(FMODPATH . 'blog/index.tpl.php')) {
        $group->match('/modules/blog', ['Wojo\Controller\Admin\Module\Blog\BlogController', 'index']);
        $group->get('/modules/blog/edit/(\d+)', ['Wojo\Controller\Admin\Module\Blog\BlogController', 'edit']);
        $group->get('/modules/blog/new', ['Wojo\Controller\Admin\Module\Blog\BlogController', 'new']);
        $group->get('/modules/blog/settings', ['Wojo\Controller\Admin\Module\Blog\BlogController', 'settings']);
        $group->get('/modules/blog/categories', ['Wojo\Controller\Admin\Module\Blog\BlogController', 'categoryNew']);
        $group->get('/modules/blog/category/(\d+)', ['Wojo\Controller\Admin\Module\Blog\BlogController', 'categoryEdit']);
        $group->match('/modules/blog/action', ['Wojo\Controller\Admin\Module\Blog\BlogController', 'action']);
    }
    
    //Portfolio
    if (File::is_File(FMODPATH . 'portfolio/index.tpl.php')) {
        $group->match('/modules/portfolio', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'index']);
        $group->match('/modules/portfolio/list', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'index']);
        $group->get('/modules/portfolio/edit/(\d+)', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'edit']);
        $group->get('/modules/portfolio/new', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'new']);
        $group->get('/modules/portfolio/settings', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'settings']);
        $group->get('/modules/portfolio/categories', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'categoryNew']);
        $group->get('/modules/portfolio/category/(\d+)', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'categoryEdit']);
        $group->match('/modules/portfolio/action', ['Wojo\Controller\Admin\Module\Portfolio\PortfolioController', 'action']);
    }
    
    //Digishop
    if (File::is_File(FMODPATH . 'digishop/index.tpl.php')) {
        $group->match('/modules/digishop', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'index']);
        $group->match('/modules/digishop/list', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'index']);
        $group->get('/modules/digishop/edit/(\d+)', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'edit']);
        $group->get('/modules/digishop/new', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'new']);
        $group->get('/modules/digishop/settings', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'settings']);
        $group->get('/modules/digishop/history/(\d+)', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'history']);
        $group->get('/modules/digishop/payments', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'payments']);
        $group->get('/modules/digishop/categories', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'categoryNew']);
        $group->get('/modules/digishop/category/(\d+)', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'categoryEdit']);
        $group->match('/modules/digishop/action', ['Wojo\Controller\Admin\Module\Digishop\DigishopController', 'action']);
    }
    
    //Shop
    if (File::is_File(FMODPATH . 'shop/index.tpl.php')) {
        $group->match('/modules/shop', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'index']);
        $group->match('/modules/shop/grid', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'index']);
        $group->get('/modules/shop/edit/(\d+)', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'edit']);
        $group->get('/modules/shop/new', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'new']);
        $group->get('/modules/shop/settings', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'settings']);
        $group->get('/modules/shop/history/(\d+)', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'history']);
        $group->get('/modules/shop/payments', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'payments']);
        $group->get('/modules/shop/categories', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'categoryNew']);
        $group->get('/modules/shop/category/(\d+)', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'categoryEdit']);
        $group->get('/modules/shop/variations', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'variations']);
        $group->get('/modules/shop/variations/new', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'variationNew']);
        $group->get('/modules/shop/variations/edit/(\d+)', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'variationEdit']);
        $group->get('/modules/shop/shipping', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'shipping']);
        $group->get('/modules/shop/shipping/view/(\d+)', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'shippingView']);
        $group->match('/modules/shop/action', ['Wojo\Controller\Admin\Module\Shop\ShopController', 'action']);
    }