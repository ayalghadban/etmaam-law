<?php
    /**
     * Donate
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2020
     * @version $Id: index.tpl.php, v1.00 2020-05-05 10:12:05 gewa Exp $
     */
    
    use Wojo\Language\Language;
    use Wojo\Plugin\Donate\Donate;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
?>
   <!-- Donate -->
<?php if ($settings = Utility::findInArray($this->properties['all'], 'id', $this->properties['id'])): ?>
   <div class="wojo plugin segment<?php echo ($settings[0]->alt_class)? ' ' . $settings[0]->alt_class : null; ?>">
       <?php if ($settings[0]->show_title): ?>
          <h3 class="center-align"><?php echo $settings[0]->title; ?></h3>
       <?php endif; ?>
       <?php if ($settings[0]->body): ?>
           <?php echo Url::out_url($settings[0]->body); ?>
       <?php endif; ?>
       <?php if ($row = Donate::render($this->properties['plugin_id'])): ?>
           <?php $percent = Utility::doPercent($row->total, $row->target_amount); ?>
          <div class="wojo indicating progress active" data-wprogress='{"tooltip": true,"label": false}'>
             <span class="bar" data-percent="<?php echo $percent; ?>"><span class="tip"></span></span>
             <div class="label"></div>
          </div>
          <div class="wojo fluid buttons">
             <div class="wojo secondary passive button"><?php echo Utility::formatMoney($row->total); ?></div>
             <div class="wojo primary passive button"><?php echo Utility::formatMoney($row->target_amount); ?></div>
          </div>
          <div class="center-align margin-top">
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="pp_form_<?php echo $row->id; ?>" name="pp_form">
                <input type="hidden" name="cmd" value="_donations"/>
                <input type="hidden" name="business" value="<?php echo $row->pp_email; ?>"/>
                <input type="hidden" name="item_name" value="Donations For <?php echo $this->properties['core']->company; ?>"/>
                <input type="hidden" name="item_number" value="<?php echo $row->id; ?>"/>
                <input type="hidden" name="return" value="<?php echo Url::url($this->properties['core']->pageslug, $row->page); ?>"/>
                <input type="hidden" name="rm" value="2"/>
                <input type="hidden" name="notify_url" value="<?php echo SITEURL; ?>gateways/paypal/donate/ipn.php"/>
                <input type="hidden" name="cancel_return" value="<?php echo SITEURL; ?>"/>
                <input type="hidden" name="no_note" value="1"/>
                <input type="hidden" name="currency_code" value="<?php echo $this->properties['core']->currency; ?>"/>
                <button class="wojo fluid positive button" type="submit" name="pp_form"><?php echo Language::$word->_PLG_DP_DONATE; ?></button>
             </form>
          </div>
       <?php endif; ?>
   </div>
<?php endif; ?>