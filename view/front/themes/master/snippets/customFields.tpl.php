<?php
   /**
    * customFields
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: customFields.tpl.php, v1.00 6/15/2023 4:13 PM Gewa Exp $
    *
    */
   
   use Wojo\Language\Language;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   $html = '';
   switch ($this->properties['section']):
      case 'profile':
         if ($this->properties['type'] == 'user'):
            foreach ($this->properties['data'] as $i => $row) {
               if ($row->field_value):
                  $is_url = (filter_var($row->field_value, FILTER_VALIDATE_URL))? '<a href="' . $row->field_value . '" target="_blank">' . $row->field_value . '</a>' : $row->field_value;
                  $html .= '<div class="item">';
                  $html .= '<div class="content text-weight-600">' . $row->{'title' . Language::$lang} . '</div>';
                  $html .= '<div class="content">' . $is_url;
                  $html .= '</div>';
                  $html .= '</div>';
               endif;
            }
            unset($row);
         else :
            $html .= '<div class="wojo block fields">';
            foreach ($this->properties['data'] as $i => $row) {
               $tootltip = $row->{'tooltip' . Language::$lang}? ' <span data-tooltip="' . $row->{'tooltip' . Language::$lang} . '"><i class="icon question sign"></i></span>' : '';
               $required = $row->required? ' <i class="icon asterisk"></i>' : '';
               
               $html .= '<div class="field">';
               $html .= '<label>' . $row->{'title' . Language::$lang} . $tootltip . $required . '</label>';
               $html .= '<input name="custom_' . $row->name . '" type="text" placeholder="' . $row->{'title' . Language::$lang} . '" value="' . ($this->properties['id']? $row->field_value : '') . '">';
               $html .= '</div>';
            }
            unset($row);
            $html .= '</div>';
         endif;
         
         break;
      
      case 'portfolio':
         foreach ($this->properties['data'] as $i => $row) {
            if ($row->field_value):
               $is_url = (filter_var($row->field_value, FILTER_VALIDATE_URL))? '<a href="' . $row->field_value . '" target="_blank">' . $row->field_value . '</a>' : '<span class="text-color-secondary">' . $row->field_value . '</span>';
               $html .= '<div class="item">';
               $html .= '<div class="content"><span class="text-weight-600">' . $row->{'title' . Language::$lang} . '</span></div>';
               $html .= '<div class="content">' . $is_url;
               $html .= '</div>';
               $html .= '</div>';
            endif;
         }
         unset($row);
         break;
      
      case 'digishop':
         foreach ($this->properties['data'] as $i => $row) {
            $is_url = (filter_var($row->field_value, FILTER_VALIDATE_URL))? '<a href="' . $row->field_value . '" target="_blank">' . $row->field_value . '</a>' : $row->field_value;
            $html .= '<div class="item">';
            $html .= '<div class="content auto"><span class="text-weight-600">' . $row->{'title' . Language::$lang} . ':</span></div>';
            $html .= '<div class="content description">' . $row->field_value;
            $html .= '</div>';
            $html .= '</div>';
         }
         unset($row);
         break;
   endswitch;
   echo $html;