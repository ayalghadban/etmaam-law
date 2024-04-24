<?php
    /**
     * master
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: master.php, v1.00 6/14/2023 9:55 AM Gewa Exp $
     *
     */
    
    use Wojo\Plugin\Slider\Slider;
    use Wojo\Url\Url;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
?>
<?php if ($setting = Slider::render($this->properties['plugin_id'])) : ?>
    <?php if ($data = Slider::getSlides($setting->id)) : ?>
      <div class="wSlider <?php echo $setting->layout; ?>" data-wslider='<?php echo $setting->settings; ?>'>
          <?php foreach ($data as $row): ?>
             <div class="holder" style="
             <?php if ($row->mode == 'bg'): ?>
                background-position: top center;
                background-repeat: no-repeat;
                background-size: cover;
                background-image: url(<?php echo UPLOADURL . $row->image;?>);
             <?php elseif ($row->mode == 'tr'): ?>
                background-color: transparent;
             <?php else: ?>
                background-color: <?php echo $row->color;?>;
    <?php endif; ?>
                min-height:<?php echo ($setting->height == 100)? $setting->height . 'vh' : $setting->height . '0'; ?>"
                data-in="0"
                data-ease-in="0"
                data-out="fade"
                data-ease-out="0"
                data-time="0"
             >
                <div class="inner <?php echo $row->attrib; ?>" style="min-height:<?php echo ($setting->height == 100)? $setting->height . 'vh' : $setting->height . '0px'; ?>"><?php echo Url::out_url($row->html); ?></div>
             </div>
          <?php endforeach; ?>
      </div>
    <?php endif; ?>
<?php endif; ?>