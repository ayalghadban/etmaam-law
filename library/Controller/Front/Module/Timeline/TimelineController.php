<?php
    
    /**
     * TimelineController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: TimelineController.php, v1.00 6/30/2023 11:19 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front\Module\Timeline;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Date\Date;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Module\Blog\Blog;
    use Wojo\Module\Event\Event;
    use Wojo\Module\Portfolio\Portfolio;
    use Wojo\Module\Timeline\Timeline;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class TimelineController extends Controller
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
                        case 'pagination':
                            $this->pagination(Validator::post('type'));
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            } else {
                Url::invalidMethod();
            }
        }
        
        /**
         * pagination
         *
         * @param string $type
         * @return void
         * @throws FileNotFoundException
         */
        private function pagination(string $type): void
        {
            $pageno = isset($_POST['pageno']) ? intval($_POST['pageno']) : 1;
            $no_of_records_per_page = isset($_POST['maxitems']) ? intval($_POST['maxitems']) : 10;
            $offset = ($pageno - 1) * $no_of_records_per_page;
            $lg = Language::$lang;
            
            switch ($type) {
                case 'blog':
                    $sql = "
                    SELECT id, images, thumb, YEAR(created) as year, MONTH(created) as month, created as timedate, slug$lg as slug, title$lg as title, body$lg as content
                      FROM `" . Blog::mTable . '`
                      WHERE expire <= NOW() AND active = ?
                      ORDER BY created DESC
                      LIMIT ' . $offset . ', ' . $no_of_records_per_page . '
                    ';
                    $data = $this->db->rawQuery($sql, array(1))->run();
                    $temp = array();
                    
                    if ($data) {
                        foreach ($data as $k => $row) {
                            $imagedata = Utility::jSonToArray($row->images);
                            $temp[$k]['year'] = $row->year;
                            $temp[$k]['month'] = $row->month;
                            $temp[$k]['created'] = $row->timedate;
                            $temp[$k]['expire'] = Date::doDate('long_date', $row->timedate);
                            $temp[$k]['title'] = $row->title;
                            $temp[$k]['content'] = Validator::sanitize($row->content, 'text', 250);
                            $temp[$k]['link'] = Url::url($this->core->modname['blog'], $row->slug);
                            $temp[$k]['display_mode'] = 'blog_post';
                            
                            if ($imagedata) {
                                $images = array();
                                foreach ($imagedata as $img) {
                                    $images[] = FMODULEURL . Blog::BLOGDATA . $row->id . '/thumbs/' . $img->name;
                                    $temp[$k]['thumb'] = $images;
                                }
                            } else {
                                $temp[$k]['thumb'] = array(Blog::hasThumb($row->thumb, $row->id));
                            }
                        }
                        
                        $this->view->blog = json_decode(json_encode((object) $temp), false);
                        $json = array(
                            'type' => 'success',
                            'html' => $this->view->snippet('_blog_item', File::isThemeDir(FMODPATH . 'timeline/themes/' . $this->core->theme . '/snippets/', FMODPATH . 'timeline/snippets/'))
                        );
                    } else {
                        $json['status'] = 'error';
                    }
                    break;
                
                case 'event':
                    $sql = "
                    SELECT YEAR(date_start) as year, MONTH(date_start) as month,
                          CONCAT(date_start,' ',time_start) as timedate, title$lg as title, body$lg as content, venue$lg as venue , contact_person, contact_email, contact_phone, color
                      FROM `" . Event::mTable . '`
                      WHERE active = ?
                      ORDER BY date_start DESC
                      LIMIT ' . $offset . ', ' . $no_of_records_per_page . '
                    ';
                    
                    $data = $this->db->rawQuery($sql, array(1))->run();
                    $temp = array();
                    
                    if ($data) {
                        foreach ($data as $k => $row) {
                            $temp[$k]['year'] = $row->year;
                            $temp[$k]['month'] = $row->month;
                            $temp[$k]['created'] = $row->timedate;
                            $temp[$k]['expire'] = Date::doDate('long_date', $row->timedate);
                            $temp[$k]['title'] = $row->title;
                            $temp[$k]['content'] = Url::out_url($row->content);
                            $temp[$k]['venue'] = $row->venue;
                            $temp[$k]['contact'] = $row->contact_person;
                            $temp[$k]['phone'] = $row->contact_phone;
                            $temp[$k]['color'] = $row->color;
                            $temp[$k]['display_mode'] = 'event';
                        }
                        
                        $this->view->event = json_decode(json_encode((object) $temp), false);
                        $json = array(
                            'type' => 'success',
                            'html' => $this->view->snippet('_event_item', File::isThemeDir(FMODPATH . 'timeline/themes/' . $this->core->theme . '/snippets/', FMODPATH . 'timeline/snippets/'))
                        );
                    } else {
                        $json['status'] = 'error';
                    }
                    break;
                
                case 'portfolio':
                    $sql = "
                    SELECT id, images, thumb, YEAR(created) as year, MONTH(created) as month, created as timedate, slug$lg as slug, title$lg as title, body$lg as content
                      FROM `" . Portfolio::mTable . '`
                      ORDER BY created DESC
                      LIMIT ' . $offset . ', ' . $no_of_records_per_page;
                    
                    $data = $this->db->rawQuery($sql)->run();
                    $temp = array();
                    
                    if ($data) {
                        foreach ($data as $k => $row) {
                            $imagedata = Utility::jSonToArray($row->images);
                            $temp[$k]['year'] = $row->year;
                            $temp[$k]['month'] = $row->month;
                            $temp[$k]['created'] = $row->timedate;
                            $temp[$k]['expire'] = Date::doDate('long_date', $row->timedate);
                            $temp[$k]['title'] = $row->title;
                            $temp[$k]['content'] = Validator::sanitize($row->content, 'text', 250);
                            $temp[$k]['link'] = Url::url($this->core->modname['blog'], $row->slug);
                            $temp[$k]['display_mode'] = 'blog_post';
                            
                            if ($imagedata) {
                                $images = array();
                                foreach ($imagedata as $img) {
                                    $images[] = FMODULEURL . Portfolio::PORTDATA . $row->id . '/thumbs/' . $img->name;
                                    $temp[$k]['thumb'] = $images;
                                }
                            } else {
                                $temp[$k]['thumb'] = array(Portfolio::hasThumb($row->thumb, $row->id));
                            }
                        }
                        
                        $this->view->portfolio = json_decode(json_encode((object) $temp), false);
                        $json = array(
                            'type' => 'success',
                            'html' => $this->view->snippet('_portfolio_item', File::isThemeDir(FMODPATH . 'timeline/themes/' . $this->core->theme . '/snippets/', FMODPATH . 'timeline/snippets/'))
                        );
                    } else {
                        $json['status'] = 'error';
                    }
                    break;
                
                default:
                    $sql = "
                    SELECT *, YEAR(created) AS year, MONTH(created) AS month, created AS timedate, title$lg AS title, body$lg AS content
                      FROM `" . Timeline::dTable . '`
                      WHERE timeline_id = ?
                      ORDER BY created DESC
                      LIMIT ' . $offset . ', ' . $no_of_records_per_page;
                    
                    $data = $this->db->rawQuery($sql, array(Filter::$id))->run();
                    $temp = array();
                    
                    if ($data) {
                        foreach ($data as $k => $row) {
                            $imagedata = Utility::jSonToArray($row->images);
                            $temp[$k]['year'] = $row->year;
                            $temp[$k]['month'] = $row->month;
                            $temp[$k]['created'] = $row->timedate;
                            $temp[$k]['expire'] = Date::doDate('long_date', $row->timedate);
                            $temp[$k]['title'] = $row->title;
                            $temp[$k]['content'] = Url::out_url($row->content);
                            $temp[$k]['dataurl'] = $row->dataurl;
                            $temp[$k]['height'] = $row->height;
                            $temp[$k]['display_mode'] = $row->type;
                            
                            if ($imagedata) {
                                $images = array();
                                foreach ($imagedata as $img) {
                                    $images[] = UPLOADURL . $img;
                                    $temp[$k]['thumb'] = $images;
                                }
                            }
                        }
                        
                        $this->view->custom = json_decode(json_encode((object) $temp), false);
                        $json = array(
                            'type' => 'success',
                            'html' => $this->view->snippet('_custom_item', File::isThemeDir(FMODPATH . 'timeline/themes/' . $this->core->theme . '/snippets/', FMODPATH . 'timeline/snippets/'))
                        );
                    } else {
                        $json['status'] = 'error';
                    }
                    break;
            }
            print json_encode($json);
        }
    }