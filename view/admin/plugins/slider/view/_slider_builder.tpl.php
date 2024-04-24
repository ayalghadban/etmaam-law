<?php
   /**
    * _slider_builder
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _slider_builder.tpl.php, v1.00 5/18/2023 9:46 PM Gewa Exp $
    *
    */

   use Wojo\Debug\Debug;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Slider Builder</title>
   <meta name="viewport"
         content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <meta name="description" content="">
   <link href="<?php echo ADMINVIEW; ?>cache/master_main_ltr.css" rel="stylesheet" type="text/css">
   <link href="<?php echo ADMINVIEW; ?>plugins/slider/builder/builder.css" rel="stylesheet" type="text/css"/>
   <script src="<?php echo SITEURL; ?>assets/jquery.js" type="text/javascript"></script>
   <script src="<?php echo SITEURL; ?>assets/global.js" type="text/javascript"></script>
</head>
<body class="design">
<div id="master-loader">
   <div class="wanimation"></div>
   <div class="curtains left"></div>
   <div class="curtains right"></div>
</div>
<div id="builderHeader">
   <div class="row align-middle horizontal-gutters">
      <div class="columns auto">
         <a class="wojo small secondary icon button is_position" data-self="false" data-mode="">
            <i class="icon grid align top left"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="false" data-mode="align-middle">
            <i class="icon grid align top middle"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="false" data-mode="align-bottom">
            <i class="icon grid align top right"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="true" data-mode="justify-center"
            data-align-self="align-self-top">
            <i class="icon grid align center left"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="true" data-mode="justify-center align-middle"
            data-align-self="align-self-middle">
            <i class="icon grid align middle center"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="true" data-mode="justify-center align-bottom"
            data-align-self="align-self-bottom">
            <i class="icon grid align center right"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="false" data-mode="justify-end">
            <i class="icon grid align bottom left"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="false" data-mode="justify-end align-middle">
            <i class="icon grid align bottom middle"></i>
         </a>
         <a class="wojo small secondary icon button is_position" data-self="false" data-mode="justify-end align-bottom">
            <i class="icon grid align bottom right"></i>
         </a>
      </div>
      <div class="columns auto min-width200">
         <a data-wdropdown="#anipack" class="wojo small fluid secondary right button">
            <span class="text">Animations</span>
            <i class="icon chevron down"></i>
         </a>
         <div class="wojo small dropdown dark menu top-left nowrap" id="anipack">
            <div class="scrolling">
               <p class="text-size-tiny text-weight-500">Static Animations</p>
               <a class="item" data-html="None" data-value="none">None</a>
               <a class="item" data-html="ball" data-value="ball">ball</a>
               <a class="item" data-html="pulsate" data-value="pulsate">pulsate</a>
               <a class="item" data-html="blink" data-value="blink">blink</a>
               <a class="item" data-html="hitLeft" data-value="hitLeft">hitLeft</a>
               <a class="item" data-html="hitRight" data-value="hitRight">hitRight</a>
               <a class="item" data-html="shake" data-value="shake">shake</a>
               <p class="text-size-tiny text-weight-500">Pop Enter Animations</p>
               <a class="item" data-html="popIn" data-value="popIn">popIn</a>
               <a class="item" data-html="popInLeft" data-value="popInLeft">popInLeft</a>
               <a class="item" data-html="popInRight" data-value="popInRight">popInRight</a>
               <a class="item" data-html="popInTop" data-value="popInTop">popInTop</a>
               <a class="item" data-html="popInBottom" data-value="popInBottom">popInBottom</a>
               <p class="text-size-tiny text-weight-500">Pop Exit Animations</p>
               <a class="item" data-html="popOut" data-value="popOut">popOut</a>
               <a class="item" data-html="popOutLeft" data-value="popOutLeft">popOutLeft</a>
               <a class="item" data-html="popOutRight" data-value="popOutRight">popOutRight</a>
               <a class="item" data-html="popOutTop" data-value="popOutTop">popOutTop</a>
               <a class="item" data-html="popOutBottom" data-value="popOutBottom">popOutBottom</a>
               <p class="text-size-tiny text-weight-500">Flip Animations</p>
               <a class="item" data-html="flip" data-value="flip">flip</a>
               <a class="item" data-html="flipInX" data-value="flipInX">flipInX</a>
               <a class="item" data-html="flipInY" data-value="flipInY">flipInY</a>
               <a class="item" data-html="flipOutX" data-value="flipOutX">flipOutX</a>
               <a class="item" data-html="flipOutY" data-value="flipOutY">flipOutY</a>
               <p class="text-size-tiny text-weight-500">Jump Animations</p>
               <a class="item" data-html="jumpInLeft" data-value="jumpInLeft">jumpInLeft</a>
               <a class="item" data-html="jumpInRight" data-value="jumpInRight">jumpInRight</a>
               <a class="item" data-html="jumpOutLeft" data-value="jumpOutLeft">jumpOutLeft</a>
               <a class="item" data-html="jumpOutRight" data-value="jumpOutRight">jumpOutRight</a>
               <p class="text-size-tiny text-weight-500">Swoop Enter Animations</p>
               <a class="item" data-html="swoopInLeft" data-value="swoopInLeft">swoopInLeft</a>
               <a class="item" data-html="swoopInRight" data-value="swoopInRight">swoopInRight</a>
               <a class="item" data-html="swoopInTop" data-value="swoopInTop">swoopInTop</a>
               <a class="item" data-html="swoopInBottom" data-value="swoopInBottom">swoopInBottom</a>
               <p class="text-size-tiny text-weight-500">Swoop Exit Animations</p>
               <a class="item" data-html="swoopOutLeft" data-value="swoopOutLeft">swoopOutLeft</a>
               <a class="item" data-html="swoopOutRight" data-value="swoopOutRight">swoopOutRight</a>
               <a class="item" data-html="swoopOutTop" data-value="swoopOutTop">swoopOutTop</a>
               <a class="item" data-html="swoopOutBottom" data-value="swoopOutBottom">swoopOutBottom</a>
               <p class="text-size-tiny text-weight-500">Drive Enter Animations</p>
               <a class="item" data-html="driveInLeft" data-value="driveInLeft">driveInLeft</a>
               <a class="item" data-html="driveInRight" data-value="driveInRight">driveInRight</a>
               <a class="item" data-html="driveInTop" data-value="driveInTop">driveInTop</a>
               <a class="item" data-html="driveInBottom" data-value="driveInBottom">driveInBottom</a>
               <p class="text-size-tiny text-weight-500">Drive Exit Animations</p>
               <a class="item" data-html="driveOutBottom" data-value="driveOutBottom">driveOutBottom</a>
               <a class="item" data-html="driveOutTop" data-value="driveOutTop">driveOutTop</a>
               <a class="item" data-html="driveOutLeft" data-value="driveOutLeft">driveOutLeft</a>
               <a class="item" data-html="driveOutRight" data-value="driveOutRight">driveOutRight</a>
               <p class="text-size-tiny text-weight-500">Fade Enter Animations</p>
               <a class="item" data-html="fadeIn" data-value="fadeIn">fadeIn</a>
               <a class="item" data-html="fadeInLeft" data-value="fadeInLeft">fadeInLeft</a>
               <a class="item" data-html="fadeInRight" data-value="fadeInRight">fadeInRight</a>
               <a class="item" data-html="fadeInTop" data-value="fadeInTop">fadeInTop</a>
               <a class="item" data-html="fadeInBottom" data-value="fadeInBottom">fadeInBottom</a>
               <p class="text-size-tiny text-weight-500">Fade Exit Animations</p>
               <a class="item" data-html="fadeOut" data-value="fadeOut">fadeOut</a>
               <a class="item" data-html="fadeOutLeft" data-value="fadeOutLeft">fadeOutLeft</a>
               <a class="item" data-html="fadeOutRight" data-value="fadeOutRight">fadeOutRight</a>
               <a class="item" data-html="fadeOutTop" data-value="fadeOutTop">fadeOutTop</a>
               <a class="item" data-html="fadeOutBottom" data-value="fadeOutBottom">fadeOutBottom</a>
               <p class="text-size-tiny text-weight-500">Roll Enter Animations</p>
               <a class="item" data-html="rollInLeft" data-value="rollInLeft">rollInLeft</a>
               <a class="item" data-html="rollInLeft" data-value="rollInRight">rollInRight</a>
               <a class="item" data-html="rollInTop" data-value="rollInTop">rollInTop</a>
               <a class="item" data-html="rollInBottom" data-value="rollInBottom">rollInBottom</a>
               <p class="text-size-tiny text-weight-500">Roll Out Animations</p>
               <a class="item" data-html="rollOutLeft" data-value="rollOutLeft">rollOutLeft</a>
               <a class="item" data-html="rollOutRight" data-value="rollOutRight">rollOutRight</a>
               <a class="item" data-html="rollOutTop" data-value="rollOutTop">rollOutTop</a>
               <a class="item" data-html="rollOutBottom" data-value="rollOutBottom">rollOutBottom</a>
               <p class="text-size-tiny text-weight-500">Spin Animations</p>
               <a class="item" data-html="spin" data-value="spin">spin</a>
               <a class="item" data-html="spinIn" data-value="spinIn">spinIn</a>
               <a class="item" data-html="spinOut" data-value="spinOut">spinOut</a>
               <p class="text-size-tiny text-weight-500">Pull Animations</p>
               <a class="item" data-html="pullUp" data-value="pullUp">pullUp</a>
               <a class="item" data-html="pullDown" data-value="pullDown">pullDown</a>
               <a class="item" data-html="pullLeft" data-value="pullLeft">pullLeft</a>
               <a class="item" data-html="pullRight" data-value="pullRight">pullRight</a>
               <p class="text-size-tiny text-weight-500">Fold Animations</p>
               <a class="item" data-html="fold" data-value="fold">fold</a>
               <a class="item" data-html="unfold" data-value="unfold">unfold</a>
            </div>
         </div>
      </div>
      <div class="columns auto">
         <div class="wojo small icon input" style="width:100px" data-position="bottom"
              data-tooltip="duration in milliseconds max 5000">
            <input id="duration" type="text" name="time" value="0">
            <span class="wojo small simple label">ms</span>
         </div>
      </div>
      <div class="columns auto">
         <div class="wojo small action input" style="width:100px" data-position="bottom"
              data-tooltip="delay in milliseconds max 5000">
            <input id="delay" type="text" name="delay" value="0">
            <span class="wojo small simple label">ms</span>
         </div>
      </div>
      <div class="columns">
         <a id="play" class="wojo small secondary icon button">
            <i class="icon play"></i>
         </a>
      </div>
      <div class="columns auto reswitch">
         <a data-mode="screen" class="wojo small secondary icon button action">
            <i class="icon tv primary"></i>
         </a>
         <a data-mode="tablet" class="wojo small secondary icon button action">
            <i class="icon laptop"></i>
         </a>
         <a data-mode="phone" class="wojo small secondary icon button action">
            <i class="icon phone"></i>
         </a>
      </div>
      <div class="columns auto source">
         <a class="wojo small negative icon button"
            href="<?php echo Url::url('admin/plugins/slider/edit', $this->data->parent_id); ?>" data-position="bottom"
            data-tooltip="Exit">
            <i class="icon door closed"></i>
         </a>
         <a id="saveAll" class="wojo small primary icon button" data-position="bottom" data-tooltip="Save">
            <i class="icon save"></i>
         </a>
      </div>
   </div>
</div>
<div id="builderAside">
   <a class="wojo small secondary icon button editHtml" data-content="Canvas Html">
      <i class="icon code"></i>
   </a>
   <a class="wojo small secondary icon button disabled is_edit element">
      <i class="icon positive pencil"></i>
   </a>
   <a class="wojo small secondary icon button disabled is_edit editor">
      <i class="icon wysiwyg fonts"></i>
   </a>
   <a class="wojo small secondary icon button disabled is_edit html">
      <i class="icon code alt"></i>
   </a>
   <a class="wojo small secondary icon button disabled save">
      <i class="icon check"></i>
   </a>
   <a class="wojo small secondary icon button disabled is_edit is_trash">
      <i class="icon negative trash"></i>
   </a>
</div>
<div id="builder">
   <iframe src="<?php echo ADMINVIEW; ?>plugins/slider/builder/iframe.php" style="width:100%;height:100%;border:none" id="builderViewer"></iframe>
</div>
<?php include BASEPATH . 'view/admin/plugins/slider/snippets/_source_helper.tpl.php'; ?>
<script src="<?php echo SITEURL; ?>assets/ace/src/ace.js"></script>
<script src="<?php echo ADMINVIEW; ?>plugins/slider/builder/builder.js"></script>
<script type="text/javascript">
   $(window).on('load', function () {
      $.get("<?php echo ADMINURL . 'plugins/slider/action/';?>", {
         action: 'loadSlide',
         id: <?php echo $this->data->id;?>,
      }, function (json) {
         let jsonObj = JSON.parse(json);
         let $bview = $('#builderViewer');
         $bview.contents().find('body').html(jsonObj.html);
         $bview.contents().find('.uimage, .ucontent').css('minHeight', jsonObj.height);
         $bview.contents().find('body')
           .Builder({
              url: "<?php echo ADMINVIEW;?>",
              purl: "<?php echo ADMINURL;?>plugins/slider/",
              surl: "<?php echo SITEURL;?>",
              slidename: "<?php echo htmlspecialchars($this->data->title);?>",
           });

      }).always(function () {
         setTimeout(function () {
            $('body').addClass('loaded');
         }, 200);
      }, 'json');
   });
</script>
<div id="tempData" class="hidden"></div>
<?php include BASEPATH . 'view/admin/plugins/slider/snippets/_canvas_helper.tpl.php'; ?>
<?php include BASEPATH . 'view/admin/plugins/slider/snippets/_section_helper.tpl.php'; ?>
<?php include BASEPATH . 'view/admin/plugins/snippets/_element_helper.tpl.php'; ?>
<?php include BASEPATH . 'view/admin/plugins/snippets/_icon_helper.tpl.php'; ?>
<?php Debug::displayInfo(); ?>
</body>
</html>