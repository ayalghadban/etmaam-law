<?php
    /**
     * Digishop Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Digishop.php, v1.00 5/6/2023 9:35 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Container\Container;
    use Wojo\Database\Database;
    use Wojo\File\File;
    use Wojo\Language\Language;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Module
    {
        const mTable = 'modules';
        const mcTable = 'mod_comments';
        
        /**
         * parseModuleAssets
         *
         * @param string $body
         * @return string
         */
        public static function parseModuleAssets(string $body): string
        {
            $pattern = '#%%([^/|%<>]+)(?=[/|])#';
            preg_match_all($pattern, $body, $matches);
            $core = Container::instance()->get('core');
            $content = '';
            
            if ($matches[1]) {
                $data = array_unique($matches[1]);
                foreach ($data as $row) {
                    $themecss = File::is_File(FMODPATH . $row . '/themes/' . $core->theme . '/assets/' . $row . '.css');
                    $themejs = File::is_File(FMODPATH . $row . '/themes/' . $core->theme . '/assets/' . $row . '.js');
                    $basecss = File::is_File(FMODPATH . $row . '/assets/' . $row . '.css');
                    $basejs = File::is_File(FMODPATH . $row . '/assets/' . $row . '.js');
                    
                    //css
                    if ($themecss) {
                        $content .= '<link id="' . $row . '" href="' . FMODULEURL . $row . '/themes/' . $core->theme . '/assets/' . $row . '.css" rel="stylesheet" type="text/css">' . "\n";
                    } elseif ($basecss) {
                        $content .= '<link id="' . $row . '" href="' . FMODULEURL . $row . '/assets/' . $row . '.css" rel="stylesheet" type="text/css">' . "\n";
                    }
                    
                    //js
                    if ($themejs) {
                        $content .= '<script id="' . $row . '" src="' . FMODULEURL . $row . '/themes/' . $core->theme . '/assets/' . $row . '.js"></script>' . "\n";
                    } elseif ($basejs) {
                        $content .= '<script id="' . $row . '" src="' . FMODULEURL . $row . '/assets/' . $row . '.js"></script>' . "\n";
                    }
                    
                }
            }
            return $content;
        }
        
        /**
         * render
         *
         * @param string $segment
         * @param Core $core
         * @return string|null
         */
        public static function render(string $segment, Core $core): string|null
        {
            if (in_array($segment, $core->modname)) {
                $mod = $core->moddir[$segment];
                
                if (File::is_File(FMODPATH . $mod . '/themes/' . $core->theme . '/index.tpl.php')) {
                    $content = FMODPATH . $mod . '/themes/' . $core->theme . '/index.tpl.php';
                } else {
                    $content = FMODPATH . $mod . '/index.tpl.php';
                }
                return ($content);
            }
        }
        
        /**
         * getAvailableModules
         *
         * @param $modalias
         * @return mixed
         */
        public static function getAvailableModules($modalias = false): mixed
        {
            $lg = Language::$lang;
            $in = ($modalias) ? "AND modalias IN($modalias)" : null;
            $sql = "
            SELECT id, title$lg as title, modalias, parent_id, icon
              FROM `" . self::mTable . "`
              WHERE is_builder = ?
              $in
              AND active = ?
              ORDER BY modalias
            ";
            return Database::Go()->rawQuery($sql, array(1, 1))->run();
        }
        
        /**
         * getFreeModules
         *
         * @param string|null $ids
         * @return mixed
         */
        public static function getFreeModules(string|null $ids): mixed
        {
            $lg = Language::$lang;
            $and = $ids ? 'AND id NOT IN (' . $ids . ')' : null;
            
            $sql = "
            SELECT id, title$lg as title, modalias, parent_id, icon
              FROM `" . self::mTable . "`
              WHERE is_builder = ?
              $and
              ORDER BY modalias
            ";
            
            return Database::Go()->rawQuery($sql, array(1))->run();
        }
        
        /**
         * getModuleList
         *
         * @param bool $is_content
         * @return array|false|int|mixed
         */
        public static function getModuleList(bool $is_content = true): mixed
        {
            $type = ($is_content) ? 'content' : 'is_menu';
            $lg = Language::$lang;
            
            $sql = "SELECT id, modalias, title$lg FROM `" . self::mTable . "` WHERE active = ? AND $type = ? ORDER BY title$lg";
            
            return Database::Go()->rawQuery($sql, array(1, 1))->run();
        }
        
        /**
         * moduleFieldList
         *
         * @return array|false|int|mixed
         */
        public static function moduleFieldList(): mixed
        {
            $lg = Language::$lang;
            $sql = "SELECT id, modalias, title$lg AS title FROM `" . self::mTable . '` WHERE hasfields = ?';
            
            return Database::Go()->rawQuery($sql, array(1))->run();
        }
    }