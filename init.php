<?php
    /**
     * init
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: init.php, v1.00 2023-04-05 10:12:05 gewa Exp $
     *
     */
    
    use Wojo\Auth\Auth;
    use Wojo\Autoloader;
    use Wojo\Container\Container;
    use Wojo\Core\Core;
    use Wojo\Core\Error;
    use Wojo\Core\Filter;
    use Wojo\Core\Router;
    use Wojo\Core\Services;
    use Wojo\Core\Session;
    use Wojo\Database\Database;
    use Wojo\Debug\Debug;
    use Wojo\Exception\BadNameException;
    use Wojo\Exception\DuplicityException;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    $BASEPATH = str_replace('init.php', '', realpath(__FILE__));
    define('BASEPATH', $BASEPATH);
    
    $configFile = BASEPATH . 'config.ini.php';
    
    if (file_exists($configFile)) {
        require_once($configFile);
        if (file_exists(BASEPATH . 'setup/')) {
            print '<div style="position:absolute;width:50%;top:50%;left:50%;transform: translate(-50%, -50%);padding:2rem;color:#fff;font-family:arial,sans-serif;background-color: #ef5350;box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(244, 67, 54, 0.4);">Please remove <strong>/setup/</strong> directory first!</div>';
            exit;
        }
    } else {
        header('Location: setup/');
        exit;
    }
    require __DIR__ . '/autoloader.php';
    $autoloader = new Autoloader(__DIR__);
    
    $autoloader->addNamespaces(array(
        'Wojo\Core' => 'library/Core',
        'Wojo\Container' => 'library/Container',
        'Wojo\Psr' => 'library/Psr',
        'Wojo\Controller\Front' => 'library/Controller/Front',
        'Wojo\Controller\Admin' => 'library/Controller/Admin',
        'Wojo\Auth' => 'library/Auth',
        'Wojo\Language' => 'library/Language',
        'Wojo\Message' => 'library/Message',
        'Wojo\Validator' => 'library/Validator',
        'Wojo\Debug' => 'library/Debug',
        'Wojo\Url' => 'library/Url',
        'Wojo\File' => 'library/File',
        'Wojo\Image' => 'library/Image',
        'Wojo\Cache' => 'library/Cache',
        'Wojo\Utility' => 'library/Utility',
        'Wojo\Date' => 'library/Date',
        'Wojo\Stats' => 'library/Stats',
        'Wojo\Database' => 'library/Database',
        'Wojo\Exception' => 'library/Exception',
        'Wojo\Module\Gallery' => 'library/Module/Gallery',
        'Wojo\Module\Faq' => 'library/Module/Faq',
        'Wojo\Module\Event' => 'library/Module/Event',
        'Wojo\Module\Adblock' => 'library/Module/Adblock',
        'Wojo\Module\Comment' => 'library/Module/Comment',
        'Wojo\Module\Map' => 'library/Module/Map',
        'Wojo\Module\Timeline' => 'library/Module/Timeline',
        'Wojo\Module\Digishop' => 'library/Module/Digishop',
        'Wojo\Module\Portfolio' => 'library/Module/Portfolio',
        'Wojo\Module\Blog' => 'library/Module/Blog',
        'Wojo\Module\Shop' => 'library/Module/Shop',
        'Wojo\Module\Form' => 'library/Module/Form',
        'Wojo\Plugin\Poll' => 'library/Plugin/Poll',
        'Wojo\Plugin\Donate' => 'library/Plugin/Donate',
        'Wojo\Plugin\Twitter' => 'library/Plugin/Twitter',
        'Wojo\Plugin\Newsletter' => 'library/Plugin/Newsletter',
        'Wojo\Plugin\Rss' => 'library/Plugin/Rss',
        'Wojo\Plugin\Carousel' => 'library/Plugin/Carousel',
        'Wojo\Plugin\Background' => 'library/Plugin/Background',
        'Wojo\Plugin\Slider' => 'library/Plugin/Slider',
        'Wojo\Plugin\Event' => 'library/Plugin/Event',
        'Wojo\Plugin\Blog' => 'library/Plugin/Blog',
    ));
    
    $autoloader->register();

    //Set containers
    $container = new Container();
    $container->set('core', $container->service(function () {
        return new Core();
    }));
    $container->set('database', $container->service(function () {
        return new Database();
    }));
    $container->set('auth', $container->service(function () {
        return new Auth();
    }));
    
    new Session;
    Debug::run();
    Error::run();
    Filter::run();
    Language::run();
    
    //Start router
    $router = new Router;
    
    //Start services
    $services = new Services;
    try {
        $services->register('core', function () use ($container) {
            return $container->get('core');
        });
        $services->register('database', function () use ($container) {
            return $container->get('database');
        });
        $services->register('auth', function () use ($container) {
            return $container->get('auth');
        });
    } catch (BadNameException|DuplicityException $e) {
    
    }
    
    $router->useServices($services);
    
    define('SITEURL', Url::SiteUrl($services->core->site_dir));
    include_once BASEPATH . 'constants.php';