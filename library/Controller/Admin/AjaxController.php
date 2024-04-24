<?php
    /**
     * AjaxController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: AjaxController, v1.00 4/29/2023 2:38 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Exception;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Membership;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Core\Session;
    use Wojo\Debug\Debug;
    use Wojo\File\File;
    use Wojo\Image\Image;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class AjaxController extends Controller
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
         * Action
         *
         * @return void
         */
        public function Action(): void
        {
            $postAction = Validator::post('action');
            $getAction = Validator::get('action');
            $lg = Language::$lang;
            
            //Post actions
            if ($postAction) {
                switch ($postAction) {
                    // search page
                    case 'searchPage':
                        $string = Validator::sanitize($_POST['value'], 'string', 15);
                        if (strlen($string) > 3) {
                            $sql = "
                            SELECT id, title$lg
                              FROM `" . Content::pTable . "`
                              WHERE MATCH (title$lg) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
                              ORDER BY title$lg
                              LIMIT 10
                            ";
                            
                            $html = '';
                            if ($result = $this->db->rawQuery($sql)->run()) {
                                $html .= '<table class="wojo table">';
                                foreach ($result as $row) {
                                    $link = Url::url('admin/pages/edit', $row->id);
                                    $html .= '<tr>';
                                    $html .= '<td>';
                                    $html .=
                                        '<span class="wojo simple label">' . $row->id . '</span>';
                                    $html .= '</td>';
                                    $html .= '<td>';
                                    $html .=
                                        '<a href="' . $link . '">' . $row->{'title' . $lg} . '</a>';
                                    $html .= '</td>';
                                    $html .= '</tr>';
                                }
                                $html .= '</table>';
                                $json = array(
                                    'status' => 'success',
                                    'html' => $html
                                );
                            } else {
                                $json['status'] = 'error';
                            }
                            print json_encode($json);
                        }
                        break;
                    
                    case 'searchPlugin':
                        $string = Validator::sanitize($_POST['value'], 'string', 15);
                        if (strlen($string) > 3) {
                            $sql = "
                            SELECT id, hasconfig, plugalias, title$lg
                              FROM `" . Plugin::mTable . "`
                              WHERE MATCH (title$lg) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
                              ORDER BY title$lg
                              LIMIT 10
                            ";
                            
                            $html = '';
                            if ($result = $this->db->rawQuery($sql)->run()) {
                                
                                $html .= '<table class="wojo table">';
                                foreach ($result as $row) {
                                    $url = Url::url('admin/plugins', $row->plugalias);
                                    $link = Url::url('admin/plugins/edit', $row->id);
                                    $html .= '<tr>';
                                    $html .= '<td>';
                                    $html .=
                                        '<span class="wojo simple label">' . $row->id . '</span>';
                                    $html .= '</td>';
                                    $html .= '<td>';
                                    $html .= '<a href="' . $link . '">' . $row->{'title' . $lg} . '</a>';
                                    $html .= '</td>';
                                    if ($row->hasconfig) {
                                        $html .= '<td class="auto">';
                                        $html .= '<a href="' . $url . '" class="wojo icon secondary inverted circular small button"><i class="icon gears"></i></a>';
                                        $html .= '</td>';
                                    }
                                    $html .= '</tr>';
                                }
                                $html .= '</table>';
                                $json = array(
                                    'status' => 'success',
                                    'html' => $html
                                );
                            } else {
                                $json['status'] = 'error';
                            }
                            print json_encode($json);
                        }
                        break;
                    
                    //editor image upload
                    case 'eupload':
                        if(IS_DEMO){
                            Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO);
                            exit;
                        }
                        if (array_key_exists('file', $_FILES)) {
                            $dir = UPLOADS . '/images/';
                            $jsons = [];
                            $exts = array(
                                'image/png',
                                'image/jpg',
                                'image/gif',
                                'image/jpeg',
                                'image/pjpeg'
                            );
                            
                            foreach ($_FILES['file']['name'] as $x => $name) {
                                $image = $_FILES['file']['name'][$x];
                                if ($_FILES['file']['size'][$x] > $this->core->file_size) {
                                    $json = array(
                                        'error' => true,
                                        'type' => 'error',
                                        'title' => Language::$word->ERROR,
                                        'message' => Message::$msgs['name'] = Language::$word->FU_ERROR10 . ' ' . File::getSize($this->core->file_size)
                                    );
                                    print json_encode($json);
                                    exit;
                                }
                                
                                $ext = strtolower($_FILES['file']['type'][$x]);
                                if (!in_array($ext, $exts)) {
                                    $json = array(
                                        'error' => true,
                                        'type' => 'error',
                                        'title' => Language::$word->ERROR,
                                        'message' => Message::$msgs['name'] = Language::$word->FU_ERROR8 . 'jpg, png, jpeg' //invalid extension
                                    );
                                    print json_encode($json);
                                    exit;
                                }
                                
                                if (file_exists($dir . $image)) {
                                    $json = array(
                                        'error' => true,
                                        'type' => 'error',
                                        'title' => Language::$word->ERROR,
                                        'message' => Message::$msgs['name'] = Language::$word->FU_ERROR6 //file exists
                                    );
                                    print json_encode($json);
                                    exit;
                                }
                                
                                if (!getimagesize($_FILES['file']['tmp_name'][$x])) {
                                    $json = array(
                                        'error' => true,
                                        'type' => 'error',
                                        'title' => Language::$word->ERROR,
                                        'message' => Message::$msgs['name'] = Language::$word->FU_ERROR7 //invalid image
                                    );
                                    print json_encode($json);
                                    exit;
                                }
                                
                                if (!move_uploaded_file($_FILES['file']['tmp_name'][$x], $dir . $image)) {
                                    $json = array(
                                        'error' => true,
                                        'type' => 'error',
                                        'title' => Language::$word->ERROR,
                                        'message' => Message::$msgs['name'] = Language::$word->FU_ERROR13 //cant move image
                                    );
                                    print json_encode($json);
                                    exit;
                                }
                                
                                if (count(Message::$msgs) === 0) {
                                    try {
                                        $img = new Image($dir . $image);
                                        $img->resizeToWidth($this->core->img_w)->save($dir . $image);
                                        $img->resizeToWidth($this->core->thumb_w)->save(UPLOADS . '/thumbs/' . $image);
                                        
                                        $jsons['file-' . $x] = array('url' => UPLOADURL . '/images/' . $image, 'id' => $x,);
                                    } catch (Exception $e) {
                                        Debug::addMessage('errors', '<i>Error</i>', $e->getMessage(), 'session');
                                    }
                                }
                            }
                            print json_encode($jsons);
                        }
                        break;
                    
                    //clear console sessions
                    case 'debugSession':
                        Session::remove('debug-queries');
                        Session::remove('debug-warnings');
                        Session::remove('debug-errors');
                        print 'ok';
                        break;
                    
                    default:
                        Url::invalidMethod();
                        break;
                    
                }
            }
            
            //Get actions
            if ($getAction) {
                switch ($getAction) {
                    //get content type
                    case 'contentType':
                        $type = Validator::get('type');
                        $html = '';
                        
                        switch ($type):
                            case 'page':
                                $data = $this->db->select(Content::pTable, array('id', "title$lg"))->where('active', 1, '=')->orderBy("title$lg", 'ASC')->run();
                                if ($data) {
                                    foreach ($data as $row) {
                                        $html .= "<option value=\"" . $row->id . "\">" . $row->{"title$lg"} . "</option>\n";
                                    }
                                    $json['type'] = 'page';
                                }
                                break;
                            
                            case 'module':
                                $data = $this->db->select(Module::mTable, array('id', "title$lg"))->where('active', 1, '=')->orderBy("title$lg", 'ASC')->run();
                                if ($data) {
                                    foreach ($data as $row) {
                                        $html .= "<option value=\"" . $row->id . "\">" . $row->{"title$lg"} . "</option>\n";
                                    }
                                    $json['type'] = 'module';
                                }
                                break;
                            
                            default:
                                $json['type'] = 'web';
                        endswitch;
                        
                        $json['message'] = $html;
                        print json_encode($json);
                        break;
                    
                    //membership list
                    case 'membershipList':
                        if (isset($_GET['type']) == 'Membership') {
                            $json = array(
                                'status' => 'success',
                                'html' => Utility::loopOptionsMultiple(Membership::getMembershipList(), 'id', "title$lg", false, 'membership_id')
                            );
                        } else {
                            $json['status'] = 'none';
                        }
                        print json_encode($json);
                        break;
                    
                    //get editor images
                    case 'getImages':
                        $result = File::scanDirectory(UPLOADS . 'images', ['include' => ['jpg', 'jpeg', 'bmp', 'png', 'svg', 'webp']], 'name');
                        $list = [];
                        foreach ($result['files'] as $row):
                            $type = pathinfo($row['name']);
                            $clean = preg_replace('/\\.[^.\\s]{3,4}$/', '', $row['name']);
                            $item = [
                                'url' => UPLOADURL . $row['url'],
                                'thumb' => $type['extension'] == 'svg' ? UPLOADURL . 'images/' . $row['name'] : UPLOADURL . 'thumbs/' . $row['name'],
                                'id' => strtolower($clean),
                                'name' => $clean,
                            ];
                            $list[] = $item;
                        endforeach;
                        print json_encode($list);
                        break;
                    
                    //get editor links
                    case 'getlinks':
                        $list = [];
                        $data = $this->db->select(Content::pTable, array('id', "title$lg", "slug$lg"))->where('active', 1, '=')->orderBy("title$lg", 'ASC')->run();
                        if ($data) {
                            foreach ($data as $row) {
                                if (Validator::get('is_builder')) {
                                    $item = array(
                                        'name' => $row->{'title' . Language::$lang},
                                        'href' => Url::url('/' . $this->core->pageslug, $row->{"slug$lg"}),
                                        'id' => $row->id,
                                    );
                                } else {
                                    $item = array(
                                        'name' => $row->{"title$lg"},
                                        'url' => Url::url('/' . $this->core->pageslug, $row->{"slug$lg"}),
                                        'id' => $row->id,
                                    );
                                }
                                $list[] = $item;
                            }
                        }
                        if (Validator::get('is_builder')) {
                            $json['message'] = $list;
                            print json_encode($json);
                        } else {
                            print json_encode($list);
                        }
                        break;
                    default:
                        Url::invalidMethod();
                        break;
                }
            }
        }
    }