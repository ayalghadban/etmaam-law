<?php
   /**
    * section_helper
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: section_helper.tpl.php, v1.00 6/1/2023 10:19 AM Gewa Exp $
    *
    */

   use Wojo\File\File;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="section-helper" class="is_draggable hide-all">
   <div class="header">
      <a class="drag-handle">
         <i class="icon grip vertical"></i>
      </a>
      <ul class="wojo fluid tabs">
         <li class="hidden tab_rows">
            <a data-tab="tab_rows">Rows</a>
         </li>
         <li class="hidden tab_blocks">
            <a data-tab="tab_blocks">Blocks</a>
         </li>
         <li class="hidden tab_sections">
            <a data-tab="tab_sections">Sections</a>
         </li>
         <li class="hidden tab_plugins">
            <a data-tab="tab_plugins">Plugins</a>
         </li>
         <li class="hidden tab_modules">
            <a data-tab="tab_modules">Modules</a>
         </li>
      </ul>
      <a class="close">
         <i class="icon x alt"></i>
      </a>
   </div>
   <div class="content scrollbox min-height200 max-height600">
      <div class="wojo tab">
         <div id="tab_rows" class="item">
            <div class="grid-blocks row blocks screen-3 small-gutters">
               <div class="columns">
                  <a data-row="1" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_1.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="2" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_2.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="3" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_3.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="4" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_4.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="5" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_5.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="6" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_6.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="7" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_7.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="8" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_8.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="9" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_9.svg" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-row="10" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_10.svg" alt="">
                  </a>
               </div>
               <div class="columns">&nbsp;</div>
               <div class="columns">
                  <a data-row="11" data-element="rows">
                     <img src="<?php echo ADMINVIEW; ?>builder/images/grid_11.svg" alt="">
                  </a>
               </div>
            </div>
         </div>
         <div id="tab_blocks" class="item">
            <div class="grid-blocks row blocks screen-2 small-gutters">
               <div class="columns">
                  <a data-name="divider" data-element="blocks" data-html="elements/divider_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/divider_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="divider" data-element="blocks" data-html="elements/divider_2">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/divider_2.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="divider" data-element="blocks" data-html="elements/divider_3">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/divider_3.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="divider" data-element="blocks" data-html="elements/divider_4">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/divider_4.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="divider" data-element="blocks" data-html="elements/divider_5">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/divider_5.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="divider" data-element="blocks" data-html="elements/divider_6">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/divider_6.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="divider" data-element="blocks" data-html="elements/divider_7">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/divider_7.png" alt="">
                  </a>
               </div>
            </div>
            <div class="grid-blocks row blocks screen-4 small-gutters">
               <div class="columns">
                  <a data-name="soundcloud" data-element="blocks" data-html="elements/audio_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/audio_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="google map" data-element="blocks" data-html="elements/map_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/map_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="youtube" data-element="blocks" data-html="elements/youtube_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/youtube_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="vimeo" data-element="blocks" data-html="elements/vimeo_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/vimeo_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="image" data-element="blocks" data-html="elements/picture_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/picture_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="button" data-element="blocks" data-html="elements/button_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/button_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="heading" data-element="blocks" data-html="elements/heading_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/heading_1.png" alt="">
                  </a>
               </div>
               <div class="columns">
                  <a data-name="paragraph" data-element="blocks" data-html="elements/text_1">
                     <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/text_1.png" alt="">
                  </a>
               </div>
            </div>
         </div>
         <div id="tab_sections" class="item">
            <?php if (File::is_File(BUILDERBASE . 'themes/' . $this->core->theme . '/sections.tpl.php')): ?>
               <?php include BUILDERBASE . 'themes/' . $this->core->theme . '/sections.tpl.php'; ?>
            <?php else: ?>
               <?php include BUILDERBASE . 'themes/default/sections.tpl.php'; ?>
            <?php endif; ?>
         </div>
         <div id="tab_plugins" class="item">
            <div class="grid-blocks row blocks screen-3 small-gutters">
               <?php if ($this->plugins): ?>
                  <?php foreach ($this->plugins as $row): ?>
                     <?php if ($row->plugalias): ?>
                        <div class="columns">
                           <a data-element="plugins" data-mode="readonly" data-plugin-id="<?php echo $row->id; ?>" data-plugin-plugin_id="<?php echo $row->plugin_id; ?>" data-plugin-name="<?php echo htmlspecialchars($row->title); ?>" data-plugin-alias="<?php echo $row->plugalias; ?>" data-plugin-group="<?php echo $row->groups; ?>">
                              <img src="<?php echo APLUGINURL . $row->icon; ?>" alt="">
                           </a>
                           <div class="truncate margin-mini-top center-align">
                              <span class="text-size-mini"><?php echo $row->title; ?></span>
                           </div>
                        </div>
                     <?php endif; ?>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>

            <!-- User Plugins -->
            <div class="grid-blocks row blocks screen-2 small-gutters">
               <?php if ($this->plugins): ?>
                  <?php foreach ($this->plugins as $row): ?>
                     <?php if (!$row->plugalias): ?>
                        <div class="columns">
                           <a data-element="uplugins" data-plugin-id="<?php echo $row->id; ?>" data-plugin-name="<?php echo htmlspecialchars($row->title); ?>">
                              <img src="<?php echo BUILDERVIEW; ?>images/uplugins.jpg" alt="">
                           </a>
                           <div class="truncate margin-mini-top center-align">
                              <span class="text-size-mini"><?php echo $row->title; ?></span>
                           </div>
                        </div>
                     <?php endif; ?>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>
         </div>

         <div id="tab_modules" class="item">
            <div class="grid-blocks row grid screen-3 small-gutters">
               <?php if ($this->modules): ?>
                  <?php foreach ($this->modules as $row): ?>
                     <?php $group = explode('/', $row->modalias); ?>
                     <div class="columns">
                        <a data-element="modules" data-mode="readonly" data-module-id="<?php echo $row->parent_id; ?>" data-module-module_id="<?php echo $row->id; ?>" data-module-name="<?php echo $row->title; ?>" data-module-alias="<?php echo $row->modalias; ?>" data-module-group="<?php echo $group[0]; ?>">
                           <img src="<?php echo AMODULEURL . $row->icon; ?>" alt="">
                        </a>
                        <p class="truncate margin-mini-top center-align">
                           <span class="text-size-mini"><?php echo $row->title; ?></span>
                        </p>
                     </div>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>