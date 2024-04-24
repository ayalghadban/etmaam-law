<?php
   /**
    * loadLanguageSection
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: loadLanguageSection.tpl.php, v1.00 5/9/2023 11:16 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   $i = 0;
   $html = '';
   
   switch ($this->type):
      case 'plugins':
      case 'modules':
         foreach ($this->data as $key => $row):
            $i++;
            $html .= '
            <tr>
               <td>
                  <span data-editable="true"
                        data-set=\'{"action": "phrase", "id": ' . $i . ',"key":"' . $key . '", "path":"' . $this->abbr . '/' . $this->path . '", "url":"languages/action/"}\'>' . $row . '</span>
               </td>
               <td class="auto right-align"><span class="wojo mini secondary label">' . $key . '</span></td>
            </tr>';
         endforeach;
         break;
      
      case 'filter':
         foreach ($this->section as $key => $row):
            $i++;
            $html .= '
            <tr>
               <td>
                  <span data-editable="true"
                        data-set=\'{"action": "phrase", "id": ' . $i . ',"key":"' . $key . '", "path":"' . $this->abbr . '/lang.json", "url":"languages/action/"}\'>' . $row . '</span>
               </td>
               <td class="auto right-align"><span class="wojo mini secondary label">' . $key . '</span></td>
            </tr>';
         
         endforeach;
         break;
      
      default:
         foreach ($this->data as $key => $row):
            $i++;
            $html .= '
            <tr>
               <td>
                  <span data-editable="true"
                        data-set=\'{"action": "phrase", "id": ' . $i . ',"key":"' . $key . '", "path":"' . $this->abbr . '/lang.json", "url":"languages/action/"}\'>' . $row . '</span>
               </td>
               <td class="auto right-align"><span class="wojo mini secondary label">' . $key . '</span></td>
            </tr>';
         endforeach;
         break;
   
   endswitch;
   echo $html;