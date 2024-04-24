<?php
    /**
     * iFrame
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: iframe.tpl.php, v1.00 2023-02-05 10:12:05 gewa Exp $
     */
    
    use Wojo\Container\Container;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    
    const _WOJO = true;
    
    require_once '../../../../../init.php';
    if (!Container::instance()->get('auth')->checkPlugAcl('slider')): print Message::msgError(Language::$word->NOACCESS);
        return; endif;
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Slider Builder</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <link href="<?php echo THEMEURL; ?>cache/master_main_ltr.css" rel="stylesheet" type="text/css">
    <link href="<?php echo ADMINVIEW; ?>plugins/slider/builder/iframe.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo SITEURL; ?>assets/jquery.js"></script>
    <script src="<?php echo SITEURL; ?>assets/global.js"></script>
</head>
<body id="builderFrame"></body>
<script src="<?php echo SITEURL; ?>assets/editor/editor.js"></script>
<script src="<?php echo SITEURL; ?>assets/editor/fontcolor.js"></script>
<script src="<?php echo SITEURL; ?>assets/editor/alignment.js"></script>
<script src="<?php echo SITEURL; ?>assets/editor/fontsize.js"></script>
<script type="text/javascript">
   $(document).ready(function ($) {
      $(parent.document).on('click', '.is_edit.editor', function () {
         $("#builderFrame .ws-layer.active").redactor({
            air: true,
            plugins: ['alignment', 'fontcolor', 'fontsize'],
            buttons: ['html', 'format', 'fontsize', 'fontcolor', 'bold', 'italic', 'deleted', 'link', 'alignment']
         });
      });
      $(parent.document).on('click', '#builderAside .save', function () {
         $("#builderFrame .ws-layer.active").redactor('destroy');
      });
   });
</script>
</html>