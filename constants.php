<?php
    /**
     * Constants
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * * @var object $services
     * @copyright 2023
     * @version $Id: Constants.php, v1.00 4/26/2023 3:28 PM Gewa Exp $
     *
     */
    
    define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    
    const IS_DEMO = 0; //place site in demo mode
    
    const UPLOADURL = SITEURL . 'uploads/';
    const UPLOADS = BASEPATH . 'uploads/';
    const ADMINURL = SITEURL . 'admin/';
    const ADMINVIEW = SITEURL . 'view/admin/';
    const ADMINBASE = BASEPATH . 'view/admin/';
    
    const FRONTVIEW = SITEURL . 'view/front/';
    const FRONTBASE = BASEPATH . 'view/front/';
    
    const FCONTROLER = BASEPATH . 'library/Controller/Front/';
    const ACONTROLER = BASEPATH . 'library/Controller/Admin/';

    const BUILDERVIEW = ADMINVIEW . 'builder/';
    const BUILDERBASE = ADMINBASE . 'builder/';
    
    const BUILDERTHEME = BUILDERVIEW . 'themes/';
    
    const AMODPATH = ADMINBASE . 'modules/';
    const AMODULEURL = ADMINVIEW . 'modules/';
    const APLUGPATH = ADMINBASE . 'plugins/';
    const APLUGINURL = ADMINVIEW . 'plugins/';
    
    const FMODPATH = FRONTBASE . 'modules/';
    const FMODULEURL = FRONTVIEW . 'modules/';
    const FPLUGPATH = FRONTBASE . 'plugins/';
    const FPLUGINURL = FRONTVIEW . 'plugins/';

    define('THEMEURL', FRONTVIEW . 'themes/' . $services->core->theme . '/');
    define('THEMEBASE', FRONTBASE . 'themes/' . $services->core->theme . '/');