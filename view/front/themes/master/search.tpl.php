<?php
   /**
    * search
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: search.tpl.php, v1.00 6/21/2023 6:30 PM Gewa Exp $
    *
    */

   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <?php if (File::is_File(FMODPATH . 'portfolio/_search.tpl.php')): ?>
      <?php include_once FMODPATH . 'portfolio/_search.tpl.php'; ?>
   <?php endif; ?>
   <?php if (File::is_File(FMODPATH . 'digishop/_search.tpl.php')): ?>
      <?php include_once FMODPATH . 'digishop/_search.tpl.php'; ?>
   <?php endif; ?>
   <?php if (File::is_File(FMODPATH . 'blog/_search.tpl.php')): ?>
      <?php include_once FMODPATH . 'blog/_search.tpl.php'; ?>
   <?php endif; ?>
   <?php if (File::is_File(FMODPATH . 'shop/_search.tpl.php')): ?>
      <?php include_once FMODPATH . 'shop/_search.tpl.php'; ?>
   <?php endif; ?>

   <div class="section" id="searchHero">
      <div class="wojo-grid relative">
         <div class="row justify-center">
            <div class="columns screen-60 tablet-60 mobile-100 phone-100 center-align">
               <h3 class="text-color-white"><?php echo $this->data->{'title' . Language::$lang}; ?></h3>
               <p class="text-color-white"><?php echo $this->data->{'caption' . Language::$lang}; ?></p>
               <form method="get" id="wojo_form" name="wojo_form" class="wojo form">
                  <div class="wojo action large input">
                     <input name="keyword" placeholder="<?php echo Language::$word->SEARCH; ?>..." type="text">
                     <button class="wojo icon primary button">
                        <i class="icon search"></i>
                     </button>
                  </div>
               </form>
            </div>
         </div>
         <div class="shape1">
            <img src="<?php echo THEMEURL; ?>images/shape-1-soft-light.svg" alt="Shape1">
         </div>
         <div class="shape2">
            <img src="<?php echo THEMEURL; ?>images/shape-7-soft-light.svg" alt="Shape2">
         </div>
      </div>
   </div>
   <div class="wojo-grid">
      <div class="padding-big-vertical">
         <?php if (!$this->keyword || strlen($this->keyword = trim($this->keyword)) == 0 || strlen($this->keyword) < 3): ?>
            <?php echo Message::msgSingleInfo(Language::$word->FRT_SEARCH_EMPTY2); ?>
         <?php elseif (!$this->pagedata and !$this->blogdata and !$this->portadata and !$this->digidata and !$this->shopdata): ?>
            <?php echo Message::msgSingleError(Language::$word->FRT_SEARCH_EMPTY . ' <span class="text-weight-600"> [' . Validator::sanitize($this->keyword) . ']</span> ' . Language::$word->FRT_SEARCH_EMPTY1); ?>
         <?php else: ?>
            <!-- Page -->
            <div class="wojo relaxed divided list margin-bottom">
               <?php $i = 0; ?>
               <?php foreach ($this->pagedata as $row): ?>
                  <?php
                  $newbody = '';
                  $body = $row->body;
                  $pattern = '/%%(.*?)%%/';
                  preg_match_all($pattern, $body, $matches);
                  if ($matches[1]) {
                     $body = str_replace($matches[0], '', $body);
                     $string = Validator::sanitize($body, 'text', 250);
                     $newbody = preg_replace("|($this->keyword)|Ui", "<span class=\"wojo negative small label\">$1</span>", $string);
                  }
                  $url = $row->page_type == 'home'? Url::url('') : Url::url($this->core->pageslug, $row->slug);
                  ?>
                  <?php $i++; ?>
                  <div class="item">
                     <div class="content">
                        <h6>
                           <small><?php echo $i; ?>.</small>
                           <a href="<?php echo $url; ?>"><?php echo $row->title; ?></a>
                        </h6>
                        <p><?php echo $newbody; ?></p>
                     </div>
                  </div>
               <?php endforeach; ?>
               <?php unset($row); ?>
            </div>
         <?php endif; ?>

         <!-- Portfolio -->
         <?php if ($this->portadata): ?>
            <h5><?php echo ucfirst($this->core->modname['portfolio']); ?></h5>
            <div class="wojo relaxed divided list margin-bottom">
               <?php $i = 0; ?>
               <?php foreach ($this->portadata as $row): ?>
                  <?php $i++; ?>
                  <div class="item">
                     <div class="content">
                        <h6>
                           <small><?php echo $i; ?>.</small>
                           <a href="<?php echo Url::url($this->core->modname['portfolio'], $row->slug); ?>"><?php echo $row->title; ?></a>
                        </h6>
                        <p><?php echo Validator::sanitize($row->body, 'text', 250); ?></p>
                     </div>
                  </div>
               <?php endforeach; ?>
               <?php unset($row); ?>
            </div>
         <?php endif; ?>

         <!-- Digishop -->
         <?php if ($this->digidata): ?>
            <h5><?php echo ucfirst($this->core->modname['digishop']); ?></h5>
            <div class="wojo relaxed divided list margin-bottom">
               <?php $i = 0; ?>
               <?php foreach ($this->digidata as $row): ?>
                  <?php $i++; ?>
                  <div class="item">
                     <div class="content">
                        <h6>
                           <small><?php echo $i; ?>.</small>
                           <a href="<?php echo Url::url($this->core->modname['digishop'], $row->slug); ?>"><?php echo $row->title; ?></a>
                        </h6>
                        <p><?php echo Validator::sanitize($row->body, 'text', 250); ?></p>
                     </div>
                  </div>
               <?php endforeach; ?>
               <?php unset($row); ?>
            </div>
         <?php endif; ?>

         <!-- Blog -->
         <?php if ($this->blogdata): ?>
            <h5><?php echo ucfirst($this->core->modname['blog']); ?></h5>
            <div class="wojo relaxed divided list margin-bottom">
               <?php $i = 0; ?>
               <?php foreach ($this->blogdata as $row): ?>
                  <?php $i++; ?>
                  <div class="item">
                     <div class="content">
                        <h6>
                           <small><?php echo $i; ?>.</small>
                           <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>"><?php echo $row->title; ?></a>
                        </h6>
                        <p><?php echo Validator::sanitize($row->body, 'text', 250); ?></p>
                     </div>
                  </div>
               <?php endforeach; ?>
               <?php unset($row); ?>
            </div>
         <?php endif; ?>

         <!-- Shop -->
         <?php if ($this->shopdata): ?>
            <h5><?php echo ucfirst($this->core->modname['shop']); ?></h5>
            <div class="wojo relaxed divided list margin-bottom">
               <?php $i = 0; ?>
               <?php foreach ($this->shopdata as $row): ?>
                  <?php $i++; ?>
                  <div class="item">
                     <div class="content">
                        <h6>
                           <small><?php echo $i; ?>.</small>
                           <a href="<?php echo Url::url($this->core->modname['shop'], $row->slug); ?>"><?php echo $row->title; ?></a>
                        </h6>
                        <p><?php echo Validator::sanitize($row->body, 'text', 250); ?></p>
                     </div>
                  </div>
               <?php endforeach; ?>
               <?php unset($row); ?>
            </div>
         <?php endif; ?>
      </div>
   </div>
</main>