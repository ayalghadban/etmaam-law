<?php
    
    /**
     * PollController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: PollController.php, v1.00 6/26/2023 11:45 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front\Plugin\Poll;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Plugin\Poll\Poll;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class PollController extends Controller
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
                        case 'vote':
                            $this->vote();
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
         * vote
         *
         * @return void
         */
        private function vote(): void
        {
            if (Filter::$id) {
                if (Poll::updatePollResult(Filter::$id)) {
                    $json['type'] = 'success';
                } else {
                    $json['type'] = 'error';
                }
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
    }