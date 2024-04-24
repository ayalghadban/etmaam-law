<?php
    /**
     * filepicker
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: filepicker.php, v1.00 5/7/2023 1:21 PM Gewa Exp $
     *
     */
    const _WOJO = true;
    include_once '../../init.php';
    
    use Wojo\Auth\Auth;
    use Wojo\Core\Session;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    
    if (!Auth::hasPrivileges('manage_files')): print Message::msgError(Language::$word->NOACCESS);
        return;
    endif;
?>
<div class="header">
    <h5 class="basic"><?php echo Language::$word->ADM_FM; ?></h5>
</div>
<div class="body" id="fm">
    <div class="wojo form small card framed top attached">
        <div class="content">
            <div class="row small-horizontal-gutters align-middle">
                <div class="auto columns phone-100">
                    <div class="wojo small secondary icon button stacked uploader" id="drag-and-drop-zone">
                        <i class="icon upload"></i>
                        <label>
                            <input type="file" multiple name="files[]">
                        </label>
                    </div>
                </div>
                <div class="columns phone-100">
                    <div class="wojo small action basic input">
                        <input placeholder="<?php echo Language::$word->FM_NEWFLD_S1; ?>..." name="foldername" type="text">
                        <a id="addFolder" class="wojo secondary button">
                            <?php echo Language::$word->ADD; ?>
                        </a>
                    </div>
                </div>
                <div class="columns auto phone-100">
                    <a class="wojo small negative button stacked disabled is_delete"><?php echo Language::$word->DELETE; ?></a>
                </div>
                <div class="columns auto phone-100">
                    <div id="displayType" class="center-align">
                        <a data-type="table"
                            class="wojo small primary icon button <?php echo Session::getCookie('CMS_FLAYOUT') == 'table' ? 'passive' : 'simple'; ?>"><i
                                class="icon list"></i></a>
                        <a data-type="list"
                            class="wojo small primary icon button <?php echo Session::getCookie('CMS_FLAYOUT') == 'list' ? 'passive' : 'simple'; ?>"><i
                                class="icon view stacked"></i></a>
                        <a data-type="grid"
                            class="wojo small primary icon button <?php echo Session::getCookie('CMS_FLAYOUT') == 'grid' ? 'passive' : 'simple'; ?>"><i
                                class="icon grid"></i></a>
                    </div>
                </div>
                <div class="columns auto hide-all">
                    <a id="fInsert" class="wojo small positive button"><?php echo Language::$word->INSERT; ?></a>
                </div>
            </div>
        </div>
    </div>
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
    <div class="footer">
        <div class="wojo small horizontal relaxed divided list">
            <div class="item"><?php echo Language::$word->FM_SPACE; ?>: <span
                    class="description"><?php echo File::directorySize(UPLOADS, true); ?></span></div>
            <div id="tsizeDir" class="item"><?php echo Language::$word->FM_DIRS; ?>: <span class="description">0</span></div>
            <div id="tsizeFile" class="item"><?php echo Language::$word->FM_FILES; ?>: <span class="description">0</span></div>
        </div>
    </div>
</div>
<input type="hidden" name="dir" value="">
<script src="<?php echo ADMINVIEW; ?>js/manager.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $("#result").Manager({
         aview: "<?php echo ADMINVIEW;?>",
         aurl: "<?php echo ADMINURL;?>",
         dirurl: "<?php echo UPLOADURL;?>",
         is_editor: true,
         is_mce: false,
         lang: {
            delete: "<?php echo Language::$word->DELETE;?>",
            insert: "<?php echo Language::$word->INSERT;?>",
            download: "<?php echo Language::$word->DOWNLOAD;?>",
            unzip: "<?php echo Language::$word->FM_UNZIP;?>",
            size: "<?php echo Language::$word->FM_FSIZE;?>",
            lastm: "<?php echo Language::$word->FM_LASTM;?>",
            items: "<?php echo mb_strtolower(Language::$word->ITEMS);?>",
            done: "<?php echo Language::$word->DONE;?>",
            home: "<?php echo Language::$word->HOME;?>",
         }
      });
   });

   // ]]>
</script>
