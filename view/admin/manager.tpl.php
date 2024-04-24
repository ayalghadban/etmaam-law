<?php
   /**
    * manager
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: manager.tpl.php, v1.00 5/11/2023 1:16 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Core\Session;
   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Message\Message;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_files')): print Message::msgError(Language::$word->NOACCESS);
      return; endif;
?>
<div id="fm">
   <div class="wojo form small card top attached">
      <div class="content">
         <div class="row small-horizontal-gutters align-middle">
            <div class="columns mobile-auto">
               <div class="wojo small secondary icon button stacked uploader" id="drag-and-drop-zone">
                  <i class="icon upload"></i>
                  <label>
                     <input type="file" multiple name="files[]">
                  </label>
               </div>
            </div>
            <div class="columns phone-100 mobile-inherit">
               <div class="wojo small action basic input">
                  <input placeholder="<?php echo Language::$word->FM_NEWFLD_S1; ?>..." name="foldername" type="text">
                  <a id="addFolder" class="wojo secondary button">
                     <?php echo Language::$word->ADD; ?>
                  </a>
               </div>
            </div>
            <div class="columns auto phone-100 mobile-auto">
               <a class="wojo small negative button stacked disabled is_delete"><?php echo Language::$word->DELETE; ?></a>
            </div>
            <div class="columns auto phone-100">
               <div id="displayType" class="center-align">
                  <a data-type="table"
                     class="wojo small primary icon button <?php echo Session::getCookie('CMS_FLAYOUT') == 'table'? 'passive' : 'simple'; ?>">
                     <i class="icon list"></i>
                  </a>
                  <a data-type="list"
                     class="wojo small primary icon button <?php echo Session::getCookie('CMS_FLAYOUT') == 'list'? 'passive' : 'simple'; ?>">
                     <i class="icon view stacked"></i>
                  </a>
                  <a data-type="grid"
                     class="wojo small primary icon button <?php echo Session::getCookie('CMS_FLAYOUT') == 'grid'? 'passive' : 'simple'; ?>">
                     <i class="icon grid"></i>
                  </a>
               </div>
            </div>
            <div class="columns auto phone-hide mobile-hide">
               <a id="togglePreview" class="wojo small simple icon button">
                  <i class="icon rotate90 arrows expand"></i>
               </a>
            </div>
         </div>
      </div>
   </div>
   <div class="row gutters">
      <div class="columns auto phone-hide mobile-hide">
         <div class="wojo form simple segment margin-top">
            <div id="ftype" class="wojo divided list">
               <a data-type="all" class="item active">
                  <i class="icon inbox"></i><?php echo Language::$word->FM_ALL_F; ?>
               </a>
               <a data-type="pic" class="item">
                  <i class="icon image"></i><?php echo Language::$word->FM_AMG_F; ?></a>
               <a data-type="vid" class="item">
                  <i class="icon film"></i><?php echo Language::$word->FM_VID_F; ?></a>
               <a data-type="aud" class="item">
                  <i class="icon soundwave"></i><?php echo Language::$word->FM_AUD_F; ?></a>
               <a data-type="doc" class="item">
                  <i class="icon files"></i><?php echo Language::$word->FM_DOC_F; ?></a>
            </div>
            <div class="margin-top">
               <select class="fileSort small">
                  <option value="name"><?php echo Language::$word->TITLE; ?></option>
                  <option value="size"><?php echo Language::$word->FM_FSIZE; ?></option>
                  <option value="type"><?php echo Language::$word->TYPE; ?></option>
                  <option value="date"><?php echo Language::$word->FM_LASTM; ?></option>
               </select>
               <input type="hidden" name="dir" value="">
            </div>
         </div>
      </div>
      <div class="columns">
         <div class="row row align-middle">
            <div class="columns">
               <div id="fcrumbs" class="padding-small text-size-small text-weight-600"><?php echo Language::$word->HOME; ?></div>
            </div>
            <div class="columns auto">
               <div id="done"></div>
            </div>
         </div>
         <div id="fileList" class="wojo small celled list"></div>
         <div id="result" class="scrollbox height500"></div>
      </div>
      <div class="columns auto phone-hide mobile-hide">
         <div id="preview" class="margin-top">
            <img src="<?php echo ADMINVIEW; ?>images/empty.svg" class="wojo medium image" alt="">
         </div>
      </div>
   </div>
   <div class="footer">
      <div class="wojo small horizontal relaxed divided list">
         <div class="item"><?php echo Language::$word->FM_SPACE; ?>:
            <span
              class="description"><?php echo File::directorySize(UPLOADS, true); ?></span>
         </div>
         <div id="tsizeDir" class="item"><?php echo Language::$word->FM_DIRS; ?>:
            <span class="description">0</span>
         </div>
         <div id="tsizeFile" class="item"><?php echo Language::$word->FM_FILES; ?>:
            <span class="description">0</span>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo ADMINVIEW; ?>js/manager.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $("#result").Manager({
         aview: "<?php echo ADMINVIEW;?>",
         aurl: "<?php echo ADMINURL;?>",
         dirurl: "<?php echo UPLOADURL;?>",
         is_editor: false,
         is_mce: false,
         lang: {
            delete: "<?php echo Language::$word->DELETE;?>",
            insert: "<?php echo Language::$word->INSERT;?>",
            download: "<?php echo Language::$word->DOWNLOAD;?>",
            unzip: "<?php echo Language::$word->FM_UNZIP;?>",
            size: "<?php echo Language::$word->FM_FSIZE;?>",
            lastm: "<?php echo Language::$word->FM_LASTM;?>",
            items: "<?php echo strtolower(Language::$word->ITEMS);?>",
            done: "<?php echo Language::$word->DONE;?>",
            home: "<?php echo Language::$word->HOME;?>",
         }
      });
   });
   // ]]>
</script>