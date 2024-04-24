<?php
    /**
     * LayoutController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: LayoutController.php, v1.00 5/12/2023 11:48 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use stdClass;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class LayoutController extends Controller
    {
        /**
         * @param $request
         * @param $response
         * @param $services
         */
        public function __construct($request, $response, $services)
        {
            parent::__construct($request, $response, $services);
        }
        
        /**
         * index
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function index(): void
        {
            $this->view->modulelist = Module::getModuleList(false);
            $this->view->layoutlist = $this->layoutOptions();
            
            $this->view->crumbs = ['admin', Language::$word->LMG_TITLE];
            $this->view->caption = Language::$word->LMG_TITLE;
            $this->view->title = Language::$word->META_T18;
            $this->view->subtitle = [];
            $this->view->render('layout', 'view/admin/');
        }
        
        /**
         * action
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'sort':
                            IS_DEMO ? print '0' : $this->sort();
                            break;
                        
                        case 'update':
                            IS_DEMO ? print '0' : $this->update();
                            break;
                        
                        case 'insert':
                            IS_DEMO ? print '0' : $this->insert();
                            break;
                        
                        case 'delete':
                            IS_DEMO ? print '0' : $this->delete();
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            }
            
            $getAction = Validator::get('action');
            if ($getAction) {
                if (IS_AJAX) {
                    switch ($getAction) {
                        case 'free':
                            $this->free();
                            break;
                        case 'layout':
                            $this->layout();
                            break;
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            }
        }
        
        /**
         * layoutOptions
         *
         * @return stdClass
         */
        private function layoutOptions(): stdClass
        {
            $lg = Language::$lang;
            $mod_id = Validator::get('mod_id');
            $where = null;
            $mod = 0;
            $row = 0;
            $type = null;
            
            if ($mod_id and Validator::sanitize($mod_id, 'int')) {
                if ($mod = $this->db->select(Module::mTable, array('id', 'modalias'))->where('id', $mod_id, '=')->first()->run()) {
                    $type = 'mod_id';
                    $where = 'WHERE l.mod_id = ' . $mod->id;
                } else {
                    Url::redirect(Url::url('admin/layout'));
                }
                
                $sql = "
				SELECT l.id, l.plug_id, l.page_id, l.mod_id, l.place, p.title$lg as title
				  FROM `" . Plugin::lTable . '` as l
				  INNER JOIN ' . Plugin::mTable . " as p ON p.id = l.plug_id
				  $where
				  AND l.is_content = ?
				  AND p.multi = ?
				  ORDER BY l.sorting ASC,
				  p.title$lg ASC
				";
                
                $row = $this->db->rawQuery($sql, array(0, 0))->run();
            }
            
            $data = new stdClass();
            $data->row = $row;
            $data->type = $type;
            $data->mod = $mod;
            
            return $data;
        }
        
        /**
         * insert
         *
         * @return void
         */
        private function insert(): void
        {
            $place = Validator::sanitize($_POST['position']);
            $data = array();
            foreach ($_POST['items'] as $item):
                $data[] = [
                    'plug_id' => $item,
                    'place' => $place,
                    'mod_id' => intval($_POST['mod'][0]['id']),
                    'modalias' => Validator::sanitize($_POST['mod'][0]['modalias'], 'string', 12),
                    'type' => 'mod_id',
                ];
            endforeach;
            
            $this->db->batch(Plugin::lTable, $data)->run();
        }
        
        /**
         * update
         *
         * @return void
         */
        private function update(): void
        {
            $and = ' AND mod_id = ' . intval($_POST['mod'][0]['id']);
            
            $query = 'UPDATE `' . Plugin::lTable . '` SET `space` = CASE ';
            $list = '';
            foreach ($_POST['items'] as $item):
                $id = Validator::sanitize($item['name'], 'int');
                $space = Validator::sanitize($item['value'], 'int');
                $query .= ' WHEN id = ' . $id . ' THEN ' . $space . ' ';
                $list .= $id . ',';
            endforeach;
            $list = substr($list, 0, -1);
            $query .= 'END WHERE id IN (' . $list . ')';
            $query .= $and;
            
            $this->db->rawQuery($query)->run();
        }
        
        /**
         * delete
         *
         * @return void
         */
        private function delete(): void
        {
            if ($this->db->delete(Plugin::lTable)->where('plug_id', Filter::$id, '=')->where('mod_id', intval($_POST['mod'][0]['id']), '=')->run()) {
                $json['type'] = 'success';
            } else {
                $json['type'] = 'error';
            }
            
            $json['title'] = Language::$word->SUCCESS;
            print json_encode($json);
        }
        
        /**
         * sort
         *
         * @return void
         */
        private function sort(): void
        {
            $place = Validator::sanitize($_POST['position'], 'string', 7);
            $and = ' AND mod_id = ' . intval($_POST['mod'][0]['id']);
            
            $i = 0;
            $query = 'UPDATE `' . Plugin::lTable . '` SET `place` = ?, `sorting` = CASE ';
            $list = '';
            foreach ($_POST['items'] as $item):
                $i++;
                $query .= ' WHEN plug_id = ' . $item . ' THEN ' . $i . ' ';
                $list .= $item . ',';
            endforeach;
            $list = substr($list, 0, -1);
            $query .= 'END WHERE plug_id IN (' . $list . ')';
            $query .= $and;
            $this->db->rawQuery($query, array($place))->run();
        }
        
        /**
         * layout
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function layout(): void
        {
            $this->view->section = Validator::sanitize($_GET['section']);
            $this->view->data = Plugin::getPluginSpaces(Utility::implodeFields($_GET['ids']), intval($_GET['mod'][0]['id']));
            $json['html'] = $this->view->snippet('getPluginLayout', 'view/admin/snippets/');
            print json_encode($json);
        }
        
        /**
         * free
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function free(): void
        {
            $this->view->section = Validator::sanitize($_GET['section']);
            $this->view->data = Plugin::getFreePlugins(Utility::implodeFields(Validator::get('ids')));
            
            $json['html'] = $this->view->snippet('getFreePlugins', 'view/admin/snippets/');
            print json_encode($json);
        }
    }