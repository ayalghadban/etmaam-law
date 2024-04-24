<?php
    /**
     * Controller Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: Controller.php, v1.00 2023-04-05 10:12:05 gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Auth\Auth;
    use Wojo\Database\Database;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    abstract class Controller
    {
        protected View $view;
        
        protected Request $request;
        protected Response $response;
        protected Services $services;
        protected Database $db;
        protected Core $core;
        protected Auth $auth;
        //private mixed $model;
        
        
        /**
         * @param Request $request
         * @param Response $response
         * @param Services $services
         */
        public function __construct(Request $request, Response $response, Services $services)
        {
            $this->view = new View();
            
            $this->view->request = $request;
            $this->view->response = $response;
            $this->view->services = $services;
            $this->view->route = SITEURL . $request->route;
            $this->view->path = $request->route;
            $this->view->segments = $request->segments;
            $this->view->matches = $request->getMatches();
            $this->core = $this->view->core = $services->core;
            $this->auth = $this->view->auth = $services->auth;
            $this->db = $this->view->db = $services->database;
        }
        
        /**
         * loadModel
         *
         * @param $request
         * @param $services
         * @return void
         * @throws NotFoundException
         */
        public function loadModel($request, $services): void
        {
            $path = File::_fixPath(BASEPATH . 'library' . str_replace('Wojo', '', $request->controller)) . '.php';
            $namespace = '';
            if (file_exists($path)) {
                $namespace = sprintf('\%s', str_replace('Controller', 'Model', $request->controller));
                $this->model = new $namespace($request, $services);
            } else {
                throw new NotFoundException("Model \"$namespace\" doesn't exist!");
            }
        }
    }