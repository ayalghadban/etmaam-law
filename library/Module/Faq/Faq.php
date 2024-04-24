<?php
    /**
     * Faq Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Faq.php, v1.00 5/21/2023 9:52 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Faq;
    
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Faq
    {
        const mTable = 'mod_faq';
        const cTable = 'mod_faq_categories';
        
        
        /**
         * render
         *
         * @return array
         */
        public static function render(): array
        {
            $lg = Language::$lang;
            $sql = "
            SELECT m.question$lg as title, m.answer$lg as answer, c.name$lg as name
              FROM `" . self::mTable . '` as m
              LEFT JOIN `' . self::cTable . '` AS c
              ON c.id = m.category_id
              ORDER BY c.sorting, m.sorting';
            
            $query = Database::Go()->rawQuery($sql)->run();
            
            $data = array();
            if ($query) {
                foreach ($query as $i => $row) {
                    if (!array_key_exists($row->name, $data)) {
                        $data[$row->name]['name'] = $row->name;
                    }
                    
                    $data[$row->name]['items'][$i]['question'] = $row->title;
                    $data[$row->name]['items'][$i]['answer'] = $row->answer;
                }
            }
            return $data;
        }
        
        /**
         * categoryTree
         *
         * @return mixed
         */
        public static function categoryTree(): mixed
        {
            return Database::Go()->select(self::cTable, array('id', 'name' . Language::$lang))->orderBy('sorting', 'ASC')->run();
            
        }
        
        /**
         * getSortCategoryList
         *
         * @param $array
         * @return string
         */
        public static function getSortCategoryList($array): string
        {
            $submenu = false;
            $icon = '<i class="icon negative trash"></i>';
            $html = '';
            if ($array) {
                foreach ($array as $row) {
                    if ($submenu === false) {
                        $submenu = true;
                        $html .= "<ul class=\"wojo nestable list\">\n";
                    }
                    $html .= '<li class="item" data-id="' . $row->id . '">'
                        . '<div class="content"><div class="handle"><i class="icon grip horizontal"></i></div><div class="text"><a href="' . Url::url('admin/modules/faq/category', $row->id) . '">' . $row->{'name' . Language::$lang} . '</a></div>'
                        . '<div class="actions"><a class="data" data-set=\'{"option":[{"delete": "delete","title": "' . Validator::sanitize($row->{'name' . Language::$lang}, 'chars') . '","id":' . $row->id . ', "type":category"}],"action":"delete","parent":"li", "url":"modules/faq/action/"}\'>' . $icon . '</a></div> '
                        . '</div>';
                    $html .= "</li>\n";
                }
            }
            
            if ($submenu === true) {
                $html .= "</ul>\n";
            }
            
            return $html;
        }
    }