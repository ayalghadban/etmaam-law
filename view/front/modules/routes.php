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
    
    use Wojo\Core\Group;
    use Wojo\File\File;
    use Wojo\Language\Language;
    
    $mod_name = $services->core->modname;
    $account = $services->core->system_slugs->account[0]->{'slug' . Language::$lang};
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    //Gallery
    $router->group('/' . $mod_name['gallery'], function (Group $group) use ($mod_name, $router) {
        $group->get('/', ['Wojo\Controller\Front\Module\Gallery\GalleryController', 'index']);
        $group->get('/' . $mod_name['gallery-album'] . '/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Gallery\GalleryController', 'render']);
    });
    $router->match('/gallery/action', ['Wojo\Controller\Front\Module\Gallery\GalleryController', 'action']);
    
    //adblock
    $router->post('/adblock/action', ['Wojo\Controller\Front\Module\Adblock\AdblockController', 'action']);
    
    //comments
    $router->match('/comments/action', ['Wojo\Controller\Front\Module\Comment\CommentController', 'action']);
    
    //timeline
    $router->post('/timeline/action', ['Wojo\Controller\Front\Module\Timeline\TimelineController', 'action']);
    
    //Portfolio
    if (File::is_File(FMODPATH . 'portfolio/index.tpl.php')) {
        $router->group('/' . $mod_name['portfolio'], function (Group $group) use ($mod_name, $router) {
            $group->get('/', ['Wojo\Controller\Front\Module\Portfolio\PortfolioController', 'index']);
            $group->get('/' . $mod_name['portfolio-cat'] . '/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Portfolio\PortfolioController', 'category']);
            $group->get('/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Portfolio\PortfolioController', 'render']);
        });
        $router->post('/portfolio/action', ['Wojo\Controller\Front\Module\Portfolio\PortfolioController', 'action']);
    }
    
    //Digishop
    if (File::is_File(FMODPATH . 'digishop/index.tpl.php')) {
        $router->group('/' . $mod_name['digishop'], function (Group $group) use ($mod_name, $router) {
            $group->get('/', ['Wojo\Controller\Front\Module\Digishop\DigishopController', 'index']);
            $group->get('/' . $mod_name['digishop-cat'] . '/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Digishop\DigishopController', 'category']);
            $group->get('/' . $mod_name['digishop-checkout'], ['Wojo\Controller\Front\Module\Digishop\DigishopController', 'checkout']);
            $group->get('/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Digishop\DigishopController', 'render']);
        });
        
        $router->match('/digishop/action', ['Wojo\Controller\Front\Module\Digishop\DigishopController', 'action']);
        //Digishop history
        $router->get('/' . $account . '/digishop', ['Wojo\Controller\Front\Module\Digishop\DigishopController', 'dashboard']);
    }
    
    //Blog
    if (File::is_File(FMODPATH . 'blog/index.tpl.php')) {
        $router->group('/' . $mod_name['blog'], function (Group $group) use ($mod_name, $router) {
            $group->get('/', ['Wojo\Controller\Front\Module\Blog\BlogController', 'index']);
            $group->get('/' . $mod_name['blog-cat'] . '/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Blog\BlogController', 'category']);
            $group->get('/' . $mod_name['blog-archive'] . '/([0-9]+)-([0-9]+)', ['Wojo\Controller\Front\Module\Blog\BlogController', 'archive']);
            $group->get('/' . $mod_name['blog-tag'] . '/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Blog\BlogController', 'tag']);
            $group->get('/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Blog\BlogController', 'render']);
        });
        
        $router->post('/blog/action', ['Wojo\Controller\Front\Module\Blog\BlogController', 'action']);
    }
    
    //Shop
    if (File::is_File(FMODPATH . 'shop/index.tpl.php')) {
        $router->group('/' . $mod_name['shop'], function (Group $group) use ($mod_name, $router) {
            $group->get('/', ['Wojo\Controller\Front\Module\Shop\ShopController', 'index']);
            $group->get('/' . $mod_name['shop-cat'] . '/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Shop\ShopController', 'category']);
            $group->get('/' . $mod_name['shop-cart'], ['Wojo\Controller\Front\Module\Shop\ShopController', 'cart']);
            $group->get('/' . $mod_name['shop-checkout'], ['Wojo\Controller\Front\Module\Shop\ShopController', 'checkout']);
            $group->get('/([a-z0-9_-]+)', ['Wojo\Controller\Front\Module\Shop\ShopController', 'render']);
        });
        
        $router->match('/shop/action', ['Wojo\Controller\Front\Module\Shop\ShopController', 'action']);
        
        //Shop history
        $router->get('/' . $account . '/shop', ['Wojo\Controller\Front\Module\Shop\ShopController', 'dashboard']);
        //Shop wishlist
        $router->get('/' . $account . '/shop/wishlist', ['Wojo\Controller\Front\Module\Shop\ShopController', 'wishlist']);
    }
    
    //Forms
    if (File::is_File(FMODPATH . 'forms/index.tpl.php')) {
        $router->match('/forms/action', ['Wojo\Controller\Front\Module\Form\FormController', 'action']);
    }