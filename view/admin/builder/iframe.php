<?php
    /**
     * iframe
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: iframe.php, v1.00 6/1/2023 10:26 AM Gewa Exp $
     *
     */
    
    use Wojo\Auth\Auth;
    use Wojo\Cache\Cache;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    
    const _WOJO = true;
    
    require_once '../../../init.php';
    if (!Auth::hasPrivileges('manage_pages')): print Message::msgError(Language::$word->NOACCESS);
        return; endif;
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Page Builder</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <meta name="description" content="">
   <link href="<?php echo THEMEURL . 'cache/master_main_ltr.css'; ?>" rel="stylesheet" type="text/css">
   <link href="<?php echo THEMEURL . 'plugins/cache/' . Cache::pluginCssCache(THEMEBASE . 'plugins'); ?>" rel="stylesheet" type="text/css">
   <link href="<?php echo THEMEURL . 'modules/cache/' . Cache::moduleCssCache(THEMEBASE . 'modules'); ?>" rel="stylesheet" type="text/css">
   <link href="<?php echo SITEURL . 'assets/builder/iframe.css'; ?>" rel="stylesheet" type="text/css"/>
   <script src="<?php echo SITEURL . 'assets/jquery.js'; ?>"></script>
   <script src="<?php echo SITEURL . 'assets/global.js'; ?>"></script>
   <script src="<?php echo THEMEURL . 'plugins/cache/' . Cache::pluginJsCache(THEMEBASE . 'plugins'); ?>"></script>
   <script src="<?php echo THEMEURL . 'modules/cache/' . Cache::moduleJsCache(THEMEBASE . 'modules'); ?>"></script>
</head>
<body id="builderFrame" class="expanded"></body>
</html>
