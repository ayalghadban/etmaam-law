<?php
   /**
    * _columns
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _columns.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small block fields">
   <div class="field">
      <label>Column Size</label>
      <div id="columns_size">
         <div class="wojo input selection margin-mini-bottom">
            <i class="icon screen"></i>
            <span class="text-size-mini">+1200px</span>
            <div>
               <select name="screen">
                  <?php include '_columns_size_data.tpl.php'; ?>
               </select>
            </div>
         </div>
         <div class="wojo input selection margin-mini-bottom">
            <i class="icon tablet"></i>
            <span class="text-size-mini">-1199px</span>
            <div>
               <select name="tablet">
                  <?php include '_columns_size_data.tpl.php'; ?>
               </select>
            </div>
         </div>
         <div class="wojo input selection margin-mini-bottom">
            <i class="icon mobile"></i>
            <span class="text-size-mini">-768px</span>
            <div>
               <select name="mobile">
                  <?php include '_columns_size_data.tpl.php'; ?>
               </select>
            </div>
         </div>
         <div class="wojo input selection margin-mini-bottom">
            <i class="icon phone"></i>
            <span class="text-size-mini">-640px</span>
            <div>
               <select name="phone">
                  <?php include '_columns_size_data.tpl.php'; ?>
               </select>
            </div>
         </div>
         <a class="wojo mini fluid primary inverted left right button">
            <i class="icon distribute vertical"></i>
            Auto
            <i class="icon distribute vertical"></i>
         </a>
      </div>
   </div>
   <div class="field basic">
      <label>Column Order</label>
      <div id="columns_order">
         <div class="wojo input selection margin-mini-bottom">
            <i class="icon screen"></i>
            <span class="text-size-mini">+1200px</span>
            <div>
               <select name="screen">
                  <?php include '_columns_order_data.tpl.php'; ?>
               </select>
            </div>
         </div>
         <div class="wojo input selection margin-mini-bottom">
            <i class="icon tablet"></i>
            <span class="text-size-mini">-1199px</span>
            <div>
               <select name="tablet">
                  <?php include '_columns_order_data.tpl.php'; ?>
               </select>
            </div>
         </div>
         <div class="wojo input selection margin-mini-bottom">
            <i class="icon mobile"></i>
            <span class="text-size-mini">-768px</span>
            <div>
               <select name="mobile">
                  <?php include '_columns_order_data.tpl.php'; ?>
               </select>
            </div>
         </div>
         <div class="wojo input selection">
            <i class="icon phone"></i>
            <span class="text-size-mini">-640px</span>
            <div>
               <select name="phone">
                  <?php include '_columns_order_data.tpl.php'; ?>
               </select>
            </div>
         </div>
      </div>
   </div>
</div>