<?php
    
    /**
     * AdblockController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: AdblockController.php, v1.00 6/14/2023 12:10 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front\Module\Adblock;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Module\Adblock\Adblock;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class AdblockController extends Controller
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
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'update':
                            $this->update();
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
         * update
         *
         * @return void
         */
        private function update(): void
        {
            
            if (Filter::$id) {
                $this->db->rawQuery('
				  UPDATE `' . Adblock::mTable . '`
				  SET total_clicks = total_clicks + 1
				  WHERE id = ' . Filter::$id . '
			  ')->run();
            }
        }
    }