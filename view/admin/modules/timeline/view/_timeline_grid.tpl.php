<?php
    /**
     * _timeline_grid
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: _timeline_grid.tpl.php, v1.00 5/19/2023 6:39 PM Gewa Exp $
     *
     */
    
    use Wojo\Core\Router;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button"><i
            class="icon plus alt"></i><?php echo Language::$word->_MOD_TML_NEW; ?></a>
   </div>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message"><i
               class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_TML_NOTML; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="row grid phone-1 mobile-1 tablet-2 screen-3 gutters">
       <?php foreach ($this->data as $row): ?>
          <div class="columns">
             <div class="wojo attached card" id="item_<?php echo $row->id; ?>">
                <div class="content center-align">
                   <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"
                      class="inline-flex margin-small-bottom"> <img src="<?php echo AMODULEURL . 'timeline/view/images/' . $row->type . '.svg'; ?>"
                         class="wojo medium center image" alt=""> </a>
                   <h5 class="basic">
                      <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->name; ?></a>
                   </h5>
                </div>
                <div class="footer divided center-align">
                   <a class="wojo primary inverted icon button"
                      href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><i class="icon pencil"></i></a>
                     <?php if ($row->type == 'custom'): ?>
                       <a class="wojo secondary inverted icon button" href="<?php echo Url::url(Router::$path, 'items/' . $row->id); ?>"><i class="icon sliders horizontal"></i></a>
                     <?php endif; ?>
                   <a class="wojo negative inverted icon button data"
                      data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->name, 'chars'); ?>","id":<?php echo $row->id; ?>, "type":"item"}],"action":"delete","parent":"#item_<?php echo $row->id; ?>","url":"modules/timeline/action/"}'><i
                         class="icon trash"></i></a>
                </div>
             </div>
          </div>
       <?php endforeach; ?>
   </div>
<?php endif; ?>