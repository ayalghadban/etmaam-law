<?php
    /**
     * Index
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $router
     * @var object $services
     * @version 6.20: index.php, v1.00 2023-06-05 10:12:05 gewa Exp $
     */
    const _WOJO = true;
    include_once 'init.php';
    
    use Wojo\Core\Group;
    use Wojo\Core\Request;
    use Wojo\Core\Response;
    use Wojo\Core\Services;
    use Wojo\Core\View;
    use Wojo\Exception\RequestMethodException;
    use Wojo\Exception\RouteNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    
    // Admin
    $router->group('/admin', function (Group $group) {
        $group->get('/', ['Wojo\Controller\Admin\IndexController', 'index']);
        
        // admin menus
        $group->get('/menus', ['Wojo\Controller\Admin\MenuController', 'index']);
        $group->get('/menus/edit/(\d+)', ['Wojo\Controller\Admin\MenuController', 'edit']);
        $group->post('/menus/action', ['Wojo\Controller\Admin\MenuController', 'action']);
        
        // admin pages
        $group->get('/pages', ['Wojo\Controller\Admin\PageController', 'index']);
        $group->get('/pages/edit/(\d+)', ['Wojo\Controller\Admin\PageController', 'edit']);
        $group->get('/pages/new', ['Wojo\Controller\Admin\PageController', 'new']);
        $group->match('/pages/action', ['Wojo\Controller\Admin\PageController', 'action']);
        
        // admin coupons
        $group->get('/coupons', ['Wojo\Controller\Admin\CouponController', 'index']);
        $group->get('/coupons/edit/(\d+)', ['Wojo\Controller\Admin\CouponController', 'edit']);
        $group->get('/coupons/new', ['Wojo\Controller\Admin\CouponController', 'new']);
        $group->post('/coupons/action', ['Wojo\Controller\Admin\CouponController', 'action']);
        
        // admin languages
        $group->get('/languages', ['Wojo\Controller\Admin\LanguageController', 'index']);
        $group->get('/languages/edit/(\d+)', ['Wojo\Controller\Admin\LanguageController', 'edit']);
        $group->get('/languages/translate/(\d+)', ['Wojo\Controller\Admin\LanguageController', 'translate']);
        $group->get('/languages/new', ['Wojo\Controller\Admin\LanguageController', 'new']);
        $group->match('/languages/action', ['Wojo\Controller\Admin\LanguageController', 'action']);
        
        // admin custom fields
        $group->get('/fields', ['Wojo\Controller\Admin\CustomFieldController', 'index']);
        $group->get('/fields/edit/(\d+)', ['Wojo\Controller\Admin\CustomFieldController', 'edit']);
        $group->get('/fields/new', ['Wojo\Controller\Admin\CustomFieldController', 'new']);
        $group->post('/fields/action', ['Wojo\Controller\Admin\CustomFieldController', 'action']);
        
        // admin email templates
        $group->get('/templates', ['Wojo\Controller\Admin\EmailTemplateController', 'index']);
        $group->get('/templates/edit/(\d+)', ['Wojo\Controller\Admin\EmailTemplateController', 'edit']);
        $group->post('/templates/action', ['Wojo\Controller\Admin\EmailTemplateController', 'action']);
        
        // admin users
        $group->match('/users', ['Wojo\Controller\Admin\UserController', 'index']);
        $group->match('/users/grid', ['Wojo\Controller\Admin\UserController', 'index']);
        $group->get('/users/edit/(\d+)', ['Wojo\Controller\Admin\UserController', 'edit']);
        $group->get('/users/new', ['Wojo\Controller\Admin\UserController', 'new']);
        $group->get('/users/history/(\d+)', ['Wojo\Controller\Admin\UserController', 'history']);
        $group->match('/users/action', ['Wojo\Controller\Admin\UserController', 'action']);
        
        // admin memberships
        $group->get('/memberships', ['Wojo\Controller\Admin\MembershipController', 'index']);
        $group->get('/memberships/edit/(\d+)', ['Wojo\Controller\Admin\MembershipController', 'edit']);
        $group->get('/memberships/new', ['Wojo\Controller\Admin\MembershipController', 'new']);
        $group->get('/memberships/history/(\d+)', ['Wojo\Controller\Admin\MembershipController', 'history']);
        $group->match('/memberships/action', ['Wojo\Controller\Admin\MembershipController', 'action']);
        
        //admin account
        $group->get('/account', ['Wojo\Controller\Admin\ProfileController', 'index']);
        $group->get('/password', ['Wojo\Controller\Admin\ProfileController', 'password']);
        $group->post('/account', ['Wojo\Controller\Admin\ProfileController', 'action']);
        $group->post('/password', ['Wojo\Controller\Admin\ProfileController', 'action']);
        
        //admin roles
        $group->get('/roles', ['Wojo\Controller\Admin\RoleController', 'index']);
        $group->get('/roles/edit/(\d+)', ['Wojo\Controller\Admin\RoleController', 'edit']);
        $group->match('/roles/action', ['Wojo\Controller\Admin\RoleController', 'action']);
        
        //admin gateways
        $group->get('/gateways', ['Wojo\Controller\Admin\GatewayController', 'index']);
        $group->get('/gateways/edit/(\d+)', ['Wojo\Controller\Admin\GatewayController', 'edit']);
        $group->post('/gateways/action', ['Wojo\Controller\Admin\GatewayController', 'action']);
        
        //admin system
        $group->get('/system', ['Wojo\Controller\Admin\SystemController', 'index']);
        $group->post('/system', ['Wojo\Controller\Admin\SystemController', 'action']);
        
        //admin utilities
        $group->get('/utilities', ['Wojo\Controller\Admin\UtilityController', 'index']);
        $group->post('/utilities', ['Wojo\Controller\Admin\UtilityController', 'action']);
        
        //admin transactions
        $group->get('/transactions', ['Wojo\Controller\Admin\TransactionController', 'index']);
        $group->post('/transactions', ['Wojo\Controller\Admin\TransactionController', 'index']);
        $group->get('/transactions/action', ['Wojo\Controller\Admin\TransactionController', 'action']);
        
        //admin configuration
        $group->get('/configuration', ['Wojo\Controller\Admin\ConfigurationController', 'index']);
        $group->post('/configuration/action', ['Wojo\Controller\Admin\ConfigurationController', 'action']);
        
        //admin backup
        $group->get('/backup', ['Wojo\Controller\Admin\BackupController', 'index']);
        $group->match('/backup/action', ['Wojo\Controller\Admin\BackupController', 'action']);
        
        //admin file manager
        $group->get('/manager', ['Wojo\Controller\Admin\FileManagerController', 'index']);
        $group->match('/manager/action', ['Wojo\Controller\Admin\FileManagerController', 'action']);
        
        //admin mailer
        $group->get('/mailer', ['Wojo\Controller\Admin\MailerController', 'index']);
        $group->post('/mailer/action', ['Wojo\Controller\Admin\MailerController', 'action']);
        
        //admin countries
        $group->get('/countries', ['Wojo\Controller\Admin\CountryController', 'index']);
        $group->get('/countries/edit/(\d+)', ['Wojo\Controller\Admin\CountryController', 'edit']);
        $group->post('/countries/action', ['Wojo\Controller\Admin\CountryController', 'action']);
        
        //admin layout
        $group->get('/layout', ['Wojo\Controller\Admin\LayoutController', 'index']);
        $group->match('/layout/action', ['Wojo\Controller\Admin\LayoutController', 'action']);
        
        //admin trash
        $group->get('/trash', ['Wojo\Controller\Admin\TrashController', 'index']);
        $group->post('/trash/action', ['Wojo\Controller\Admin\TrashController', 'action']);
        
        //admin page builder
        $group->get('/builder/(\w+)/(\d+)', ['Wojo\Controller\Admin\BuilderController', 'index']);
        $group->match('/builder/action', ['Wojo\Controller\Admin\BuilderController', 'action']);
        
        // admin plugins
        $group->get('/plugins', ['Wojo\Controller\Admin\PluginController', 'index']);
        $group->get('/plugins/edit/(\d+)', ['Wojo\Controller\Admin\PluginController', 'edit']);
        $group->get('/plugins/new', ['Wojo\Controller\Admin\PluginController', 'new']);
        $group->post('/plugins/action', ['Wojo\Controller\Admin\PluginController', 'action']);
        
        //admin individual plugins
        include_once APLUGPATH . 'routes.php';
        
        // admin modules
        $group->get('/modules', ['Wojo\Controller\Admin\ModuleController', 'index']);
        $group->get('/modules/edit/(\d+)', ['Wojo\Controller\Admin\ModuleController', 'edit']);
        $group->post('/modules/action', ['Wojo\Controller\Admin\ModuleController', 'action']);
        
        //admin individual modules
        include_once AMODPATH . 'routes.php';
        
        //ajax calls
        $group->match('/ajax', ['Wojo\Controller\Admin\AjaxController', 'action']);
        
        //logout
        $group->get('/logout', function () {
        })->before(function (Request $request, Response $response, Services $services) {
            if ($services->auth->logged_in) {
                $services->auth->logout();
            }
            $response->redirect(ADMINURL);
        });
    })->before(function (Request $request, Response $response, Services $services) {
        if (!$services->auth->is_Admin()) {
            $response->redirect(SITEURL . 'login');
        }
    });
    
    // Front
    try {
        $router->get('/login', ['Wojo\Controller\Front\LoginController', 'index'])
            ->before(function (Request $request, Response $response, Services $services) {
                if ($services->auth->logged_in) {
                    $response->redirect($services->auth->usertype == 'member' ? SITEURL . 'dashboard/' : ADMINURL);
                }
            });
        $router->post('/login', ['Wojo\Controller\Front\LoginController', 'login']);
        $router->post('/login/action', ['Wojo\Controller\Front\LoginController', 'action']);
        
        //home page
        $router->get('/', ['Wojo\Controller\Front\IndexController', 'index']);
        //pages
        $router->match('/' . $services->core->pageslug . '/([a-z0-9_-]+)', ['Wojo\Controller\Front\IndexController', 'page']);
        
        //register page
        $router->get('/' . $services->core->system_slugs->register[0]->{'slug' . Language::$lang}, ['Wojo\Controller\Front\IndexController', 'register'])
            ->before(function (Request $request, Response $response, Services $services) {
                if ($services->auth->logged_in) {
                    $response->redirect($services->auth->usertype == 'member' ? SITEURL . 'dashboard/' : ADMINURL);
                }
            });
        
        //search page
        $router->get('/' . $services->core->system_slugs->search[0]->{'slug' . Language::$lang}, ['Wojo\Controller\Front\IndexController', 'search']);
        
        //sitemap page
        $router->get('/' . $services->core->system_slugs->sitemap[0]->{'slug' . Language::$lang}, ['Wojo\Controller\Front\IndexController', 'sitemap']);
        
        //privacy page
        $router->get('/' . $services->core->system_slugs->policy[0]->{'slug' . Language::$lang}, ['Wojo\Controller\Front\IndexController', 'privacy']);
        
        //password reset page
        $router->get('/password/([a-z0-9_-]+)', ['Wojo\Controller\Front\IndexController', 'password']);
        
        //user profile page
        $router->get('/' . $services->core->system_slugs->profile[0]->{'slug' . Language::$lang} . '/([a-zA-Z0-9_-]+)', ['Wojo\Controller\Front\IndexController', 'profile']);
        
        //activation page
        $router->get('/' . $services->core->system_slugs->activate[0]->{'slug' . Language::$lang}, ['Wojo\Controller\Front\IndexController', 'activation'])
            ->before(function (Request $request, Response $response, Services $services) {
                if ($services->auth->logged_in) {
                    $response->redirect($services->auth->usertype == 'member' ? SITEURL . 'dashboard/' : ADMINURL);
                }
            });
        
        //dashboard page
        $router->group($services->core->system_slugs->account[0]->{'slug' . Language::$lang}, function (Group $group) {
            $group->match('/', ['Wojo\Controller\Front\DashboardController', 'index']);
            $group->match('/action', ['Wojo\Controller\Front\DashboardController', 'action']);
            $group->get('/history', ['Wojo\Controller\Front\DashboardController', 'history']);
            $group->get('/settings', ['Wojo\Controller\Front\DashboardController', 'settings']);
            $group->match('/validate', ['Wojo\Controller\Front\DashboardController', 'validate']);
        })->before(function (Request $request, Response $response, Services $services) {
            if (!$services->auth->is_User()) {
                $response->redirect(SITEURL . 'login');
            }
        });
        
        //front individual modules
        include_once FMODPATH . 'routes.php';
        
        //front individual plugins
        include_once FPLUGPATH . 'routes.php';
        
        //ajax calls
        $router->match('/ajax', ['Wojo\Controller\Front\AjaxController', 'action']);
        
        //logout
        $router->get('/logout', function () {
        })->before(function (Request $request, Response $response, Services $services) {
            if ($services->auth->logged_in) {
                $services->auth->logout();
            }
            $response->redirect(SITEURL);
        });
        
    } catch (RequestMethodException $e) {
        printf('<h1>Not Allowed</h1><p>%s</p>', $e->getMessage());
    }
    
    // default 404
    $router->default(function (Request $request, Response $response, Services $services) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        $tpl = new View();
        
        $tpl->crumbs = [];
        $tpl->caption = null;
        $tpl->title = Language::$word->META_ERROR;
        $tpl->subtitle = null;
        $tpl->keywords = null;
        $tpl->description = null;
        $tpl->meta = null;
        
        $tpl->request = $request;
        $tpl->core = $services->core;
        $tpl->auth = $services->auth;
        $tpl->segments = $request->segments;
        
        if (in_array('admin', $request->segments)) {
            $tpl->render('404', 'view/admin/');
        } else {
            $tpl->render('404', THEMEBASE);
        }
    });
    
    // Maintenance mode
    if ($services->core->offline) {
        if (false === str_contains($_SERVER['REQUEST_URI'], 'login/') && false === str_contains($_SERVER['REQUEST_URI'], 'admin/') && false === $services->auth->is_Admin()) {
            Url::redirect(SITEURL . 'maintenance.php');
            exit;
        }
    }
    
    try {
        $router->run(Request::fromGlobals());
    } catch (RequestMethodException|RouteNotFoundException $e) {
        $message = printf('<h1>Not Allowed</h1><p>%s</p>', $e->getMessage());
        (new Response($message, 405))->send();
    }