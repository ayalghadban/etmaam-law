<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/14/2023 8:44 PM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkPlugAcl('poll')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'edit': ?>
   <!-- Start edit -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form segment margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_PL_QUESTION; ?></label>
               <div class="wojo large basic input">
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_PL_QUESTION; ?>"
                         value="<?php echo $this->data->question; ?>" name="question">
               </div>
            </div>
         </div>
         <div class="wojo block fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_PL_OPTIONS; ?></label>
            </div>
            <div id="item">
               <?php foreach ($this->options as $key => $row): ?>
                  <?php $key++; ?>
                  <div class="field old">
                     <div class="wojo icon input">
                        <input type="text" data-id="<?php echo $row->id; ?>"
                               placeholder="<?php echo Language::$word->_PLG_PL_OPTIONS; ?>" value="<?php echo $row->value; ?>"
                               name="newvalue[]">
                        <?php if ($key <> 1): ?>
                           <i class="icon negative x alt selectable"></i>
                        <?php endif; ?>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="field">
            <button type="button" id="btnAdd"
                    class="wojo small secondary button"><?php echo Language::$word->_PLG_PL_ADD_Q; ?></button>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'poll'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/poll/action/" data-action="update" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->_PLG_PL_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form segment margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_PL_QUESTION; ?></label>
               <div class="wojo large basic input">
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_PL_QUESTION; ?>" name="question">
               </div>
            </div>
         </div>
         <div class="wojo block fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_PL_OPTIONS; ?></label>
            </div>
            <div id="item">
               <div class="field">
                  <div class="wojo icon input">
                     <input type="text" placeholder="<?php echo Language::$word->_PLG_PL_OPTIONS; ?>" name="value[]">
                  </div>
               </div>
            </div>
         </div>
         <div class="field">
            <button type="button" id="btnAdd"
                    class="wojo small secondary button"><?php echo Language::$word->_PLG_PL_ADD_Q; ?></button>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'poll'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/poll/action/" data-action="add" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->_PLG_PL_ADD; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_PLG_PL_SUB4; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->_PLG_PL_NOPOLL; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="row grid phone-1 mobile-1 tablet-2 screen-2 gutters">
         <?php foreach ($this->data as $key => $rows): ?>
            <div class="columns" id="item_<?php echo $rows->id; ?>">
               <div class="wojo simple segment">
                  <h5><?php echo $rows->name; ?></h5>
                  <div class="wojo very relaxed celled list">
                     <?php foreach ($rows->opts as $i => $row): ?>
                        <?php $percent = Utility::doPercent($row->total, $rows->totals); ?>
                        <div class="item relative">
                           <div class="content"><?php echo $row->value; ?></div>
                           <div class="content auto"><span
                                class="wojo small secondary inverted label"><?php echo $row->total; ?></span>
                           </div>
                           <div class="wojo primary tiny bottom attached progress"
                                data-wprogress='{"tooltip": false,"label": false}'>
                              <span class="bar" data-percent="<?php echo $percent; ?>"><span class="tip"></span></span>
                              <div class="label"></div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
                  <div class="center-align padding-top">
                     <a href="<?php echo Url::url(Router::$path . '/edit', $rows->id); ?>"
                        class="wojo icon primary inverted button">
                        <i class="icon pencil"></i>
                     </a>
                     <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($rows->name, 'chars'); ?>","id":<?php echo $rows->id; ?>, "type":"poll"}],"action":"delete","parent":"#item_<?php echo $rows->id; ?>", "url":"plugins/poll/action/"}'
                        class="wojo icon negative inverted button data">
                        <i class="icon trash"></i>
                     </a>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>
<script src="<?php echo APLUGINURL; ?>poll/view/js/poll.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Poll({
         url: "<?php echo ADMINURL . 'plugins/poll/action/';?>",
         lang: {
            optext: "<?php echo Language::$word->_PLG_PL_OPTIONS;?>",
         }
      });
   });
   // ]]>
</script>