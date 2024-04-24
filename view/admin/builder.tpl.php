<?php
   /**
    * builder
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: builder.tpl.php, v1.00 6/1/2023 10:19 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Cache\Cache;
   use Wojo\Debug\Debug;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
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
   <link href="<?php echo ADMINVIEW; ?>cache/master_main_ltr.css" rel="stylesheet" type="text/css">
   <link href="<?php echo SITEURL; ?>assets/builder/builder.css" rel="stylesheet" type="text/css"/>
   <script src="<?php echo SITEURL; ?>assets/jquery.js"></script>
   <script src="<?php echo SITEURL; ?>assets/global.js"></script>
   <script src="<?php echo SITEURL; ?>assets/sortable.js"></script>
   <script data-pace-options='{"eventLag": false,"restartOnRequestAfter": false}' src="<?php echo SITEURL; ?>assets/pace.js"></script>
</head>
<body class="design" id="mainFrame">
<div id="builderHeader">
   <div class="row align-middle horizontal-gutters">
      <div class="columns">
         <h5 class="basic"><?php echo $this->data->{'title' . Language::$lang}; ?></h5>
      </div>
      <div class="columns auto">
         <a data-wdropdown="#dropdown-sizeMenu" class="wojo small simple right button">
            <span>Screen 1440px</span>
            <i class="icon chevron down"></i>
         </a>
         <div class="wojo small dropdown menu top-right" id="dropdown-sizeMenu">
            <a data-value="1920" class="item">Screen 1920px</a>
            <a data-value="1440" class="item active">Screen 1440px</a>
            <a data-value="1280" class="item">Screen 1280px</a>
            <a data-value="1024" class="item">Tablet 1024px</a>
            <a data-value="768" class="item">Mobile 768px</a>
            <a data-value="640" class="item">Phone 640px</a>
         </div>
      </div>
      <div class="columns auto">
         <a class="wojo small icon basic button scale">
            <i class="icon zoom out"></i>
         </a>
      </div>
      <?php if (count($this->langlist) > 1) : ?>
         <div class="columns auto">
            <a data-wdropdown="#dropdown-langMenu" class="wojo small primary icon button">
               <i class="icon flag"></i>
            </a>
            <div class="wojo dropdown menu top-right" id="dropdown-langMenu">
               <?php foreach ($this->langlist as $lang) : ?>
                  <?php if ($lang->abbr == $this->segments[2]) : ?>
                     <a data-value="<?php echo $lang->abbr; ?>" class="item active"><span
                          class="flag icon <?php echo $lang->abbr; ?>"></span>
                        <span><?php echo $lang->name; ?></span>
                     </a>
                  <?php else : ?>
                     <a data-value="<?php echo $lang->abbr; ?>" class="item"
                        href="<?php echo Url::url('admin/builder/' . $lang->abbr, $this->segments[3]); ?>"><span
                          class="flag icon <?php echo $lang->abbr; ?>"></span>
                        <?php echo $lang->name; ?></a>
                  <?php endif; ?>
               <?php endforeach; ?>
            </div>
         </div>
      <?php endif; ?>
      <div class="columns auto">
         <a class="wojo small negative icon button spaced"
            href="<?php echo Url::url('admin/pages/edit', $this->segments[3]); ?>" data-position="bottom" data-tooltip="Exit">
            <i class="icon door closed"></i>
         </a>
         <a id="saveAll" class="wojo small positive icon button" data-position="bottom" data-tooltip="Save">
            <i class="icon save"></i>
         </a>
      </div>
      <?php if (count($this->langlist) > 1) : ?>
         <div class="columns auto">
            <div class="wojo checkbox toggle fitted inline">
               <input name="langall" type="checkbox" value="1" id="langall">
               <label for="langall">All Language</label>
            </div>
         </div>
      <?php endif; ?>
   </div>
</div>
<div id="builderNav" class="scrollbox"></div>
<div id="builderProperty" class="scrollbox">
   <?php include BASEPATH . 'view/admin/builder/snippets/element_helper.tpl.php'; ?>
</div>
<div id="builder">
   <iframe src="<?php echo ADMINVIEW . 'builder/iframe.php'; ?>" id="builderViewer"></iframe>
</div>
<?php include BASEPATH . 'view/admin/builder/snippets/code_helper.tpl.php'; ?>
<script src="<?php echo THEMEURL . 'plugins/cache/' . Cache::pluginJsCache(THEMEBASE . 'plugins'); ?>"></script>
<script src="<?php echo THEMEURL . 'modules/cache/' . Cache::moduleJsCache(THEMEBASE . 'modules'); ?>"></script>
<script src="<?php echo SITEURL . 'assets/ace/src/ace.js'; ?>"></script>
<script src="<?php echo SITEURL . 'assets/builder/builder.js'; ?>"></script>
<script type="text/javascript">
   $(window).on('load', function () {
      const $bv = $("#builderViewer");
      $.get("<?php echo ADMINURL . 'builder/action/';?>", {
         id: <?php echo $this->segments[3];?>,
         action: "load",
         lang: "<?php echo $this->segments[2];?>"
      }, function (result) {
         let data = (result) ? result : '<div class="section"><div class="wojo-grid"><div class="row gutters"><div class="columns is_empty"></div></div></div></div>';
         $bv.contents().find("body").html(data);
         $bv.contents().find("body")
           .Builder({
              aurl: "<?php echo ADMINURL;?>",
              url: "<?php echo ADMINVIEW;?>",
              surl: "<?php echo SITEURL;?>",
              upurl: "<?php echo UPLOADURL;?>",
              burl: "<?php echo Url::builderUrl($this->core->theme);?>",
              pagename: "<?php echo htmlspecialchars($this->data->{'title' . Language::$lang});?>",
           });

         $bv.contents().find('.wojo.carousel').each(function () {
            let set = $(this).data('wcarousel');
            $(this).slick(set);
         });

         $bv.contents().find('.wojo.progress').wProgress();
         
         $bv.contents().find('.wSlider').each(function () {
            let set = $(this).data('wslider');
            $(this).slick({
               dots: set.dots,
               arrows: set.arrows,
               autoplay: set.autoplay
            });
         });
      });
   });
</script>
<div id="tempData" class="hidden"></div>
<div id="undoData" class="hidden"></div>
<?php include BASEPATH . 'view/admin/builder/snippets/section_helper.tpl.php'; ?>
<?php Debug::displayInfo(); ?>
</body>
</html>