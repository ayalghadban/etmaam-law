<?php
   /**
    * utility
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: utility.tpl.php, v1.00 4/30/2023 12:00 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   $this->auth::checkOwner();
?>
<div class="wojo tabs">
   <ul class="nav">
      <li class="active">
         <a data-tab="users"><i class="icon people"></i><?php echo Language::$word->META_T3_1; ?>
         </a>
      </li>
      <li>
         <a data-tab="sitemap"><i class="icon diagram"></i><?php echo Language::$word->UTL_CREATESM; ?></a>
      </li>
      <li>
         <a data-tab="slugs"><i class="icon tools"></i><?php echo Language::$word->META_T19_1; ?>
         </a>
      </li>
         <li>
            <a data-tab="colors"><i class="icon droplet"></i><?php echo Language::$word->META_T34; ?></a>
         </li>
   </ul>
   <div class="wojo simple segment tab">
      <div data-tab="users" class="item">
         <div class="wojo attached dashed dashed-negative segment">
            <form method="post" name="wojo_forma">
               <div class="wojo form">
                  <div class="wojo fields">
                     <div class="field three wide">
                        <label><?php echo Language::$word->UTL_SUB1; ?></label>
                        <select name="days">
                           <option value="3">3</option>
                           <option value="7">7</option>
                           <option value="14">14</option>
                           <option value="30">30</option>
                           <option value="60">60</option>
                           <option value="100">100</option>
                           <option value="180">180</option>
                           <option value="365">365</option>
                        </select>
                     </div>
                     <div class="field five wide">
                        <label><?php echo Language::$word->DELETE; ?></label>
                        <button type="button" data-route="admin/utilities/" data-action="deleteInactive" name="dosubmit"
                                class="wojo negative button"><?php echo Language::$word->UTL_DELINACTIVE; ?>
                        </button>
                     </div>
                  </div>
                  <p class="text-size-small"><?php echo Language::$word->UTL_SUB1_T; ?></p>
               </div>
            </form>
         </div>
         <div class="wojo segment margin-vertical">
            <form method="post" name="wojo_formb">
               <div class="wojo form">
                  <div class="wojo fields">
                     <div class="field  basic">
                        <label><?php echo Language::$word->UTL_SUB3; ?></label>
                        <p class="text-size-small"><?php echo str_replace('[NUMBER]', '<span class="wojo primary inverted label" id="banned">' . $this->banned . '</span>', Language::$word->UTL_SUB2_T); ?></p>
                     </div>
                     <div class="field auto basic">
                        <label><?php echo Language::$word->DELETE; ?></label>
                        <button type="button" data-route="admin/utilities/" data-action="deleteBanned" name="dosubmit"
                                class="wojo negative button"><?php echo Language::$word->UTL_DELBANNED; ?>
                        </button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="wojo segment">
            <form method="post" name="wojo_formc">
               <div class="wojo form">
                  <div class="wojo fields">
                     <div class="field basic">
                        <label><?php echo Language::$word->UTL_SUB2; ?></label>
                        <p class="text-size-small"><?php echo str_replace('[NUMBER]', '<span class="wojo small primary inverted label" id="pending">' . $this->pending . '</span>', Language::$word->UTL_SUB2_T); ?></p>
                     </div>
                     <div class="field auto basic">
                        <label><?php echo Language::$word->DELETE; ?></label>
                        <button type="button" data-route="admin/utilities/" data-action="deletePending" name="dosubmit"
                                class="wojo negative button"><?php echo Language::$word->UTL_DELPENDING; ?>
                        </button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="wojo segment margin-top">
            <form method="post" name="wojo_formd">
               <div class="wojo form">
                  <div class="wojo fields">
                     <div class="field basic">
                        <label><?php echo Language::$word->UTL_CART; ?></label>
                        <p class="text-size-small"><?php echo Language::$word->UTL_CART_T; ?></p>
                     </div>
                     <div class="field auto basic">
                        <label><?php echo Language::$word->DELETE; ?></label>
                        <button type="button" data-route="admin/utilities/" data-action="emptyCart" name="dosubmit"
                                class="wojo negative button"><i
                             class="icon cart"></i><?php echo Language::$word->UTL_CRBTN; ?>
                        </button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <div data-tab="sitemap" class="item hide-all">
         <form method="post" name="wojo_formf">
            <div class="wojo form">
               <div class="wojo fields">
                  <div class="field basic">
                     <label><?php echo Language::$word->UTL_SUB4; ?></label>
                     <p class="text-size-small"><?php echo Language::$word->UTL_SUB4_T; ?></p>
                  </div>
                  <div class="field auto basic">
                     <label><?php echo Language::$word->UTL_GENERATE; ?></label>
                     <button type="button" data-route="admin/utilities/" data-action="siteMap" name="dosubmit"
                             class="wojo primary button"><?php echo Language::$word->UTL_GENERATE; ?>
                     </button>
                  </div>
               </div>
            </div>
         </form>
      </div>
      <div data-tab="slugs" class="item hide-all">
         <div class="wojo attached dashed simple segment">
            <form method="post" name="wojo_forme">
               <div class="wojo form">
                  <div class="wojo fields">
                     <div class="field  basic">
                        <label><?php echo Language::$word->UTL_INSTALL; ?></label>
                        <p class="text-size-small"><?php echo Language::$word->UTL_INSTALL_T; ?></p>
                     </div>
                     <div class="field auto basic">
                        <input type="file" name="installer" id="installer" class="filestyle"
                               data-input="false"
                               data-badge="true">
                     </div>
                     <div class="field auto basic">
                        <button type="button" data-route="admin/utilities/" data-action="install" name="dosubmit"
                                class="wojo secondary button"><?php echo Language::$word->UTL_INSTALL; ?>
                        </button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="margin-top">
            <form method="post" name="wojo_formg">
               <div class="wojo attached dashed basic segment form">
                  <div class="wojo fields">
                     <div class="field">
                        <label><?php echo Language::$word->UTL_SUB5; ?></label>
                        <p class="text-size-small"><?php echo Language::$word->UTL_SUB5_T; ?></p>
                     </div>
                  </div>
                  <?php foreach ($this->core->slugs->moddir as $key => $mod): ?>
                     <div class="wojo fields">
                        <div class="field">
                           <input type="text" value="<?php echo $key; ?>"
                                  name="<?php echo $mod; ?>">
                           <p class="text-size-small"><?php echo SITEURL . $key . '/'; ?></p>
                        </div>
                        <div class="field">
                           <input type="text" disabled value="<?php echo $mod; ?>">
                        </div>
                     </div>
                  <?php endforeach; ?>
                  <div class="wojo fields">
                     <div class="field">
                        <input type="text" value="<?php echo $this->core->slugs->pagedata->page; ?>"
                               name="page">
                        <p class="text-size-small"><?php echo SITEURL . $this->core->slugs->pagedata->page . '/page-title/'; ?></p>
                     </div>
                     <div class="field">
                        <input type="text" disabled value="page">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field basic center-align">
                        <button type="button" data-route="admin/utilities/" data-action="slugs" name="dosubmit"
                                class="wojo primary button"><?php echo Language::$word->UPDATE; ?>
                        </button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
         <div data-tab="colors" class="item hide-all">
            <h5><?php echo Language::$word->META_T34; ?></h5>
            <p class="text-size-small margin-bottom">
               <?php echo Language::$word->UTL_INFO1; ?>
            </p>
            <div class="wojo form">
               <form method="post" name="wojo_formt">
                  <div class="wojo fields">
                     <div class="field">
                        <label>Body Bg Color</label>
                        <input data-wcolor="full" name="body-bg-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['body-bg-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['body-bg-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Body Color</label>
                        <input data-wcolor="full" name="body-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['body-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['body-color']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Primary</label>
                        <input data-wcolor="full" name="primary-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['primary-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['primary-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Primary Hover</label>
                        <input data-wcolor="full" name="primary-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['primary-color-hover']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['primary-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Primary Active</label>
                        <input data-wcolor="full" name="primary-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['primary-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['primary-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Primary Inverted</label>
                        <input data-wcolor="full" name="primary-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['primary-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['primary-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Primary Shadow</label>
                        <input data-wcolor="full" name="primary-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['primary-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['primary-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Secondary</label>
                        <input data-wcolor="full" name="secondary-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['secondary-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['secondary-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Secondary Hover</label>
                        <input data-wcolor="full" name="secondary-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['secondary-color-hover']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['secondary-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Secondary Active</label>
                        <input data-wcolor="full" name="secondary-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['secondary-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['secondary-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Secondary Inverted</label>
                        <input data-wcolor="full" name="secondary-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['secondary-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['secondary-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Secondary Shadow</label>
                        <input data-wcolor="full" name="secondary-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['secondary-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['secondary-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Positive</label>
                        <input data-wcolor="full" name="positive-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['positive-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['positive-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Positive Hover</label>
                        <input data-wcolor="full" name="positive-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['positive-color-hover']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['positive-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Positive Active</label>
                        <input data-wcolor="full" name="positive-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['positive-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['positive-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Positive Inverted</label>
                        <input data-wcolor="full" name="positive-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['positive-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['positive-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Positive Shadow</label>
                        <input data-wcolor="full" name="positive-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['positive-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['positive-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Negative</label>
                        <input data-wcolor="full" name="negative-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['negative-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['negative-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Negative Hover</label>
                        <input data-wcolor="full" name="negative-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['negative-color-hover']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['negative-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Negative Active</label>
                        <input data-wcolor="full" name="negative-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['negative-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['negative-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Negative Inverted</label>
                        <input data-wcolor="full" name="negative-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['negative-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['negative-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Negative Shadow</label>
                        <input data-wcolor="full" name="negative-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['negative-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['negative-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Alert</label>
                        <input data-wcolor="full" name="alert-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['alert-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['alert-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Alert Hover</label>
                        <input data-wcolor="full" name="alert-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['alert-color-hover']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['alert-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Alert Active</label>
                        <input data-wcolor="full" name="alert-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['alert-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['alert-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Alert Inverted</label>
                        <input data-wcolor="full" name="alert-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['alert-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['alert-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Alert Shadow</label>
                        <input data-wcolor="full" name="alert-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['alert-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['alert-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Info</label>
                        <input data-wcolor="full" name="info-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['info-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['info-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Info Hover</label>
                        <input data-wcolor="full" name="info-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['info-color-hover']; ?>"}'
                               type="text" value="<?php echo $this->colors['info-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Info Active</label>
                        <input data-wcolor="full" name="info-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['info-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['info-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Info Inverted</label>
                        <input data-wcolor="full" name="info-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['info-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['info-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Info Shadow</label>
                        <input data-wcolor="full" name="info-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['info-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['info-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Light</label>
                        <input data-wcolor="full" name="light-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['light-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['light-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Light Hover</label>
                        <input data-wcolor="full" name="light-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['light-color-hover']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['light-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Light Active</label>
                        <input data-wcolor="full" name="light-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['light-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['light-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Light Inverted</label>
                        <input data-wcolor="full" name="light-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['light-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['light-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Light Shadow</label>
                        <input data-wcolor="full" name="light-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['light-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['light-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Dark</label>
                        <input data-wcolor="full" name="dark-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['dark-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['dark-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Dark Hover</label>
                        <input data-wcolor="full" name="dark-color-hover"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['dark-color-hover']; ?>"}'
                               type="text" value="<?php echo $this->colors['dark-color-hover']; ?>">
                     </div>
                     <div class="field">
                        <label>Dark Active</label>
                        <input data-wcolor="full" name="dark-color-active"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['dark-color-active']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['dark-color-active']; ?>">
                     </div>
                     <div class="field">
                        <label>Dark Inverted</label>
                        <input data-wcolor="full" name="dark-color-inverted"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['dark-color-inverted']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['dark-color-inverted']; ?>">
                     </div>
                     <div class="field">
                        <label>Dark Shadow</label>
                        <input data-wcolor="full" name="dark-color-shadow"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['dark-color-shadow']; ?>"}'
                               type="text"
                               value="<?php echo $this->colors['dark-color-shadow']; ?>">
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label>Grey</label>
                        <input data-wcolor="full" name="grey-color"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['grey-color']; ?>"}'
                               type="text" value="<?php echo $this->colors['grey-color']; ?>">
                     </div>
                     <div class="field">
                        <label>Grey 100</label>
                        <input data-wcolor="full" name="grey-color-100"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['grey-color-100']; ?>"}'
                               type="text" value="<?php echo $this->colors['grey-color-100']; ?>">
                     </div>
                     <div class="field">
                        <label>Grey 300</label>
                        <input data-wcolor="full" name="grey-color-300"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['grey-color-300']; ?>"}'
                               type="text" value="<?php echo $this->colors['grey-color-300']; ?>">
                     </div>
                     <div class="field">
                        <label>Grey 500</label>
                        <input data-wcolor="full" name="grey-color-500"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['grey-color-500']; ?>"}'
                               type="text" value="<?php echo $this->colors['grey-color-500']; ?>">
                     </div>
                     <div class="field">
                        <label>Grey 700</label>
                        <input data-wcolor="full" name="grey-color-700"
                               data-color='{"format":"rgb","opacity":"0.5","color": "<?php echo $this->colors['grey-color-700']; ?>"}'
                               type="text" value="<?php echo $this->colors['grey-color-700']; ?>">
                     </div>
                  </div>
                  <div class="center-align">
                     <button type="button" data-route="admin/utilities/" data-action="colors" name="dosubmit"
                             class="wojo primary button"><?php echo Language::$word->UPDATE; ?>
                     </button>
                  </div>
               </form>
            </div>
         </div>
   </div>
</div>