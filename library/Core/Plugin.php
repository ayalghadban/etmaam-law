<?php
    /**
     * Plugin Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Plugin.php, v1.00 5/8/2023 10:00 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use stdClass;
    use Wojo\Container\Container;
    use Wojo\Database\Database;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Utility\Utility;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Plugin
    {
        const mTable = 'plugins';
        const lTable = 'layout';
        
        /**
         * RenderAll
         *
         * @param string|null $ids
         * @return mixed
         */
        public static function renderAll(string|null $ids): mixed
        {
            $lg = Language::$lang;
            
            $sql = "
            SELECT id, plugin_id, jscode, show_title, alt_class, plugalias, title$lg as title, body$lg as body
              FROM `" . self::mTable . '`
              WHERE id IN (' . $ids . ')
              AND active = ?
            ';
            
            return Database::Go()->rawQuery($sql, array(1))->run();
        }
        
        /**
         * getModulePlugins
         *
         * @param string $modalias
         * @return mixed
         */
        public static function getModulePlugins(string $modalias): mixed
        {
            $lg = Language::$lang;
            $sql = "
            SELECT p.id, l.plug_id, l.space, l.place, p.system, p.alt_class, p.plugalias, p.plugin_id, p.title$lg as title, p.body$lg as body, p.jscode, p.show_title, p.cplugin
              FROM `" . self::lTable . '` as l
              LEFT JOIN ' . self::mTable . ' as p
              ON p.id = l.plug_id
              WHERE l.modalias = ?
              AND p.active = ?
              ORDER BY l.sorting
            ';
            
            return Database::Go()->rawQuery($sql, array($modalias, 1))->run();
        }
        
        /**
         * getPluginSpaces
         *
         * @param string $ids
         * @param int $mod_id
         * @return array|false|int|mixed
         */
        public static function getPluginSpaces(string $ids, int $mod_id): mixed
        {
            $lg = Language::$lang;
            $sql = "
			SELECT l.id, l.space, p.title$lg as title
			  FROM `" . self::mTable . '` as p
			  INNER JOIN ' . self::lTable . ' as l
			  ON p.id = l.plug_id
			  WHERE p.id IN (' . $ids . ")
			  AND l.mod_id = ?
			  AND p.multi = ?
			  AND p.active = ?
			  ORDER BY title$lg
			";
            
            return Database::Go()->rawQuery($sql, array($mod_id, 0, 1))->run();
        }
        
        /**
         * getFreePlugins
         *
         * @param $ids
         * @return array|false|int|mixed
         */
        public static function getFreePlugins($ids): mixed
        {
            $lg = Language::$lang;
            $and = $ids ? 'AND id NOT IN (' . $ids . ')' : null;
            $sql = "
			SELECT *, title$lg AS title, body$lg AS body
			  FROM `" . self::mTable . "`
			  WHERE multi = ?
			  $and
			  AND active = ?
			  ORDER BY title$lg ASC
			";
            
            return Database::Go()->rawQuery($sql, array(0, 1))->run();
        }
        
        /**
         * loadPluginFile
         *
         * @param array $items
         * @return false|string
         * @throws FileNotFoundException
         */
        public static function loadPluginFile(array $items): false|string
        {
            $tpl = new View();
            
            $core = Container::instance()->get('core');
            $auth = Container::instance()->get('auth');
            
            $tpl->id = $items[2];
            $tpl->plugin_id = $items[1];
            $tpl->all = $items[3];
            $tpl->core = $core;
            $tpl->auth = $auth;
            
            return File::is_File(FPLUGPATH . $items[0] . '/themes/' . $core->theme . '/index.tpl.php') ?
                $tpl->snippet('index', FPLUGPATH . $items[0] . '/themes/' . $core->theme . '/') :
                $tpl->snippet('index', FPLUGPATH . $items[0] . '/');
        }
        
        /**
         * moduleLayout
         *
         * @param array|null $data
         * @return stdClass
         */
        public static function moduleLayout(array|null $data): stdClass
        {
            $layout = new stdClass();
            //plugin layout
            $layout->topWidget = Utility::findInArray($data, 'place', 'top');
            $layout->bottomWidget = Utility::findInArray($data, 'place', 'bottom');
            $layout->leftWidget = Utility::findInArray($data, 'place', 'left');
            $layout->rightWidget = Utility::findInArray($data, 'place', 'right');
            
            //plugin counter
            $layout->topCount = ($layout->topWidget) ? count($layout->topWidget) : 0;
            $layout->bottomCount = ($layout->bottomWidget) ? count($layout->bottomWidget) : 0;
            $layout->leftCount = ($layout->leftWidget) ? count($layout->leftWidget) : 0;
            $layout->rightCount = ($layout->rightWidget) ? count($layout->rightWidget) : 0;
            
            //plugin space counter
            $layout->tcounter = Utility::countInArray($layout->topWidget, 'space', 10);
            $layout->bcounter = Utility::countInArray($layout->bottomWidget, 'space', 10);
            
            return $layout;
        }
    }