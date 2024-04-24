<?php
   /**
    * _timeline_iedit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _timeline_iedit.tpl.php, v1.00 5/20/2023 6:39 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo form">
      <div class="wojo lang tabs">
         <ul class="nav">
            <?php foreach ($this->langlist as $lang): ?>
               <li<?php echo ($lang->abbr == $this->core->lang)? ' class="active"' : null; ?>>
                  <a class="lang-color <?php echo Utility::colorToWord($lang->color); ?>"
                     data-tab="lang_<?php echo $lang->abbr; ?>"><span
                       class="flag icon <?php echo $lang->abbr; ?>"></span><?php echo $lang->name; ?></a>
               </li>
            <?php endforeach; ?>
         </ul>
         <div class="tab spaced">
            <?php foreach ($this->langlist as $lang): ?>
               <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                  <div class="wojo block fields">
                     <div class="field">
                        <label><?php echo Language::$word->NAME; ?>
                           <small><?php echo $lang->abbr; ?></small>
                           <i class="icon asterisk"></i>
                        </label>
                        <div class="wojo large basic input">
                           <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                  value="<?php echo $this->data->{'title_' . $lang->abbr}; ?>"
                                  name="title_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                     <div class="field basic">
                  <textarea class="altpost"
                            name="body_<?php echo $lang->abbr; ?>"><?php echo Url::out_url($this->data->{'body_' . $lang->abbr}); ?></textarea>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_TML_RMORE; ?></label>
            <div class="wojo labeled input">
               <div class="wojo simple label"> http::</div>
               <input placeholder="<?php echo Language::$word->_MOD_TML_RMORE; ?>" value="<?php echo $this->data->readmore; ?>"
                      type="text" name="readmore">
            </div>
         </div>
      </div>
   </div>
   <div class="wojo form segment margin-bottom">
      <?php if ($this->data->type == 'iframe'): ?>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_MOD_TML_IURL; ?></label>
               <div class="wojo labeled input">
                  <div class="wojo simple label"> http::</div>
                  <input placeholder="<?php echo Language::$word->_MOD_TML_IURL; ?>" value="<?php echo $this->data->dataurl; ?>"
                         type="text" name="dataurl">
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_MOD_TML_IHEIGHT; ?></label>
               <div class="wojo labeled input">
                  <input placeholder="<?php echo Language::$word->_MOD_TML_IHEIGHT; ?>"
                         value="<?php echo $this->data->height; ?>" type="text" name="height">
                  <div class="wojo simple label">px</div>
               </div>
            </div>
         </div>
      <?php else: ?>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->IMAGES; ?></label>
               <a class="multipick wojo primary button">
                  <i class="open folder icon"></i><?php echo Language::$word->_MOD_TML_SUB16; ?></a>
               <div class="scrollbox margin-top height300">
                  <div class="wojo sortable row blocks phone-1 mobile-2 tablet-3 screen-5 gutters" id="sortable">
                     <?php if ($this->imagedata): ?>
                        <?php $k = 0; ?>
                        <?php foreach ($this->imagedata as $k => $irow): ?>
                           <div class="columns" id="item_<?php echo $k++; ?>" data-id="<?php echo $k++; ?>">
                              <div class="wojo compact segment center-align">
                                 <div class="handle">
                                    <i class="icon grip horizontal"></i>
                                 </div>
                                 <img src="<?php echo UPLOADURL . $irow; ?>" alt="" class="wojo rounded image">
                                 <input type="hidden" name="images[]" value="<?php echo $irow; ?>">
                                 <a class="wojo mini icon negative simple button remove">
                                    <i class="icon x alt"></i>
                                 </a>
                              </div>
                           </div>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>
      <?php endif; ?>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules/timeline/items', $this->row->id); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/timeline/action/" data-action="customUpdate" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_TML_SUB13; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   <input type="hidden" name="type" value="<?php echo $this->data->type; ?>">
   <input type="hidden" name="timeline_id" value="<?php echo $this->data->timeline_id; ?>">
</form>
