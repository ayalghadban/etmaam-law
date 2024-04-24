<?php
   /**
    * _color
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _color.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="margin-mini-bottom">
   <a data-class="none" class="button">
      <i class="icon slash medium secondary square"></i>
   </a>
</div>
<div class="row blocks screen-6 mini-gutters color-picker">
   <div class="columns">
      <a data-bg="bg-color-primary"
         data-text="text-color-primary"
         data-border="border-color-primary"
         data-class="primary"
         class="button" style="background-color:<?php echo $this->colors['primary-color'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-secondary"
         data-text="text-color-secondary"
         data-border="border-color-secondary"
         data-class="secondary"
         class="button" style="background-color:<?php echo $this->colors['secondary-color'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-positive"
         data-text="text-color-positive"
         data-border="border-color-positive"
         data-class="positive"
         class="button" style="background-color:<?php echo $this->colors['positive-color'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-negative"
         data-text="text-color-negative"
         data-border="border-color-negative"
         data-class="negative"
         class="button" style="background-color:<?php echo $this->colors['negative-color'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-alert"
         data-text="text-color-alert"
         data-border="border-color-alert"
         data-class="alert"
         class="button" style="background-color:<?php echo $this->colors['alert-color'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-info"
         data-text="text-color-info"
         data-border="border-color-info"
         data-class="info"
         class="button" style="background-color:<?php echo $this->colors['info-color'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-primary-inverted"
         data-text="text-color-primary-inverted"
         data-border="border-color-primary-inverted"
         data-class="primary-inverted"
         class="button" style="background-color:<?php echo $this->colors['primary-color-inverted'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-secondary-inverted"
         data-text="text-color-secondary-inverted"
         data-border="border-color-secondary-inverted"
         data-class="secondary-inverted"
         class="button" style="background-color:<?php echo $this->colors['secondary-color-inverted'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-positive-inverted"
         data-text="text-color-positive-inverted"
         data-border="border-color-positive-inverted"
         data-class="positive-inverted"
         class="button" style="background-color:<?php echo $this->colors['positive-color-inverted'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-negative-inverted"
         data-text="text-color-negative-inverted"
         data-border="border-color-negative-inverted"
         data-class="negative-inverted"
         class="button" style="background-color:<?php echo $this->colors['negative-color-inverted'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-alert-inverted"
         data-text="text-color-alert-inverted"
         data-border="border-color-alert-inverted"
         data-class="alert-inverted"
         class="button" style="background-color:<?php echo $this->colors['alert-color-inverted'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-info-inverted"
         data-text="text-color-info-inverted"
         data-border="border-color-info-inverted"
         data-class="info-inverted"
         class="button" style="background-color:<?php echo $this->colors['info-color-inverted'];?>"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-light"
         data-text="text-color-light"
         data-border="border-color-light"
         data-class="light"
         class="button bg-color-light"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-dark"
         data-text="text-color-dark"
         data-border="border-color-dark"
         data-class="dark"
         class="button bg-color-dark"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-grey"
         data-text="text-color-grey"
         data-border="border-color-grey"
         data-class="grey"
         class="button bg-color-grey"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-grey-300"
         data-text="text-color-grey-300"
         data-border="border-color-grey-300"
         data-class="grey-300"
         class="button bg-color-grey-300"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-grey-500"
         data-text="text-color-grey-500"
         data-border="border-color-grey-500"
         data-class="grey-500"
         class="button bg-color-grey-500"></a>
   </div>
   <div class="columns">
      <a data-bg="bg-color-white"
         data-text="text-color-white"
         data-border="border-color-white"
         data-class="white"
         class="button white"></a>
   </div>
</div>