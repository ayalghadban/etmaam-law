<?php
    /**
     * Router
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Router.php, v1.00 4/25/2023 1:54 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Closure;
    use InvalidArgumentException;
    use Wojo\Debug\Debug;
    use Wojo\Exception\RouteNotFoundException;
    use Wojo\Exception\RequestMethodException;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    
    class Router
    {
        
        private const SUPPORTED_VERBS = ['GET', 'POST'];
        
        const GET = 'GET';
        const POST = 'POST';
        public static string $path;
        
        private array $routes = [];
        private array $groups = [];
        private ?Services $services;
        private string $basepath;
        private ?Closure $default_controller;
        
        /**
         *
         */
        public function __construct()
        {
            $this->basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1));
        }
        
        /**
         * useServices
         *
         * Set services to use into controllers
         * @param Services $services
         * @return $this
         */
        public function useServices(Services $services): Router
        {
            $this->services = $services;
            
            return $this;
        }
        
        /**
         * get
         *
         * Shortcut to add route with GET method
         * @param string $path
         * @param $closure
         * @return Route
         */
        public function get(string $path, $closure): Route
        {
            try {
                return $this->route('GET', $path, $closure);
            } catch (RequestMethodException) {
            }
        }
        
        /**
         * post
         *
         * Shortcut to add route with POST method
         * @param string $path
         * @param $closure
         * @return Route
         */
        public function post(string $path, $closure): Route
        {
            try {
                return $this->route('POST', $path, $closure);
            } catch (RequestMethodException) {
            
            }
        }
        
        /**
         * match
         *
         * Shortcut to add route with GET|POST method
         * @param string $path
         * @param $closure
         * @return void
         */
        public function match(string $path, $closure): void
        {
            $this->get($path, $closure);
            $this->post($path, $closure);
        }
        
        /**
         * route
         *
         * @param string $verb The allowed route http method
         * @param string $path The route path
         * @param array|Closure $closure
         * @return Route
         * @throws RequestMethodException
         */
        public function route(string $verb, string $path, array|Closure $closure): Route
        {
            if (!$closure instanceof Closure && !is_array($closure)) {
                throw new InvalidArgumentException(sprintf('The closure must be a function or array with controller definition, caught %s', gettype($closure)));
            }
            
            $verb = strtoupper(trim($verb));
            $path = pathFormat($path);
            
            if (!in_array($verb, Router::SUPPORTED_VERBS)) {
                throw new RequestMethodException(sprintf('The HTTP method %s isn\'t allowed in route definition "%s".', $verb, $path));
            }
            
            $new_route = new Route($verb, $path, $closure);
            $this->routes[$verb][] = $new_route;
            
            return $new_route;
        }
        
        /**
         * default
         *
         * Default controller to execute if no match to any route. Match any request method
         * @param Closure $closure
         * @return void
         */
        public function default(Closure $closure): void
        {
            $this->default_controller = $closure;
        }
        
        /**
         * group
         *
         * Routes group definition under a common prefix
         * @param string $prefix Prefix for routes group
         * @param Closure $closure
         * @return Group
         */
        public function group(string $prefix, Closure $closure): Group
        {
            $new_group = new Group($prefix, $closure, $this);
            $this->groups[] = $new_group;
            
            return $new_group;
        }
        
        /**
         * run
         *
         * Start the router
         * @param Request $request
         * @return void
         * @throws RequestMethodException
         * @throws RouteNotFoundException
         */
        public function run(Request $request): void
        {
            static $invoke = false;
            
            if (!$invoke) {
                $this->processGroups();
                $this->handleRequest($request);
                $invoke = true;
            }
        }
        
        /**
         * processGroups
         *
         * Process the route groups before routing
         * @return void
         */
        private function processGroups(): void
        {
            if ([] !== $this->groups) {
                foreach ($this->groups as $group) {
                    $group();
                }
            }
        }
        
        /**
         * handleRequest
         *
         * Handle the request uri and start router
         * @param Request $request The Request object with global params
         * @return void
         * @throws RequestMethodException
         * @throws RouteNotFoundException
         */
        private function handleRequest(Request $request): void
        {
            $server = $request->getServer();
            $request_uri = $this->filterRequestUri($server['REQUEST_URI']);
            $request_method = $server['REQUEST_METHOD'];
            
            if (!in_array($request_method, Router::SUPPORTED_VERBS)) {
                throw new RequestMethodException(sprintf('The HTTP method %s isn\'t supported by router.', $request_method));
            }
            
            // Trailing slash no matters
            $request_uri = '/' !== $request_uri ? rtrim($request_uri, '/\\') : $request_uri;
            
            $uri = substr($request_uri, strlen($this->basepath));
            $uri = ltrim($uri, '/\\');
            Debug::addMessage('params', 'route', $uri);
            
            self::$path = $uri;
            
            // Select the routes collection according to the http request method
            $routes = $this->routes[$request_method] ?? [];
            
            foreach ($routes as $route) {
                $full_path = $this->basepath . $route->getPath();
                
                if (preg_match($this->getPattern($full_path), $request_uri, $arguments)) {
                    $path = array_filter(explode('/', trim($uri)));
                    array_shift($arguments);
                    list($params, $matches) = $this->filterArguments($arguments);
                    $request->setParams($params);
                    $request->setMatches($matches);
                    $request->setSegments(array_values($path));
                    $request->setRoute(ltrim($route->getPath(), '/'));
                    
                    $services = $this->services;
                    
                    // Filter the services for route
                    if ([] !== $route->getRouteServices() && isset($services)) {
                        $services = $this->filterServices($route->getRouteServices());
                    }
                    
                    $response = new Response;
                    
                    // Exec the middleware
                    if ($route->hasHookBefore()) {
                        $before = $route->getHookBefore();
                        
                        $data = isset($services) ? call_user_func($before, $request, $response, $services) : call_user_func($before, $request, $response);
                        
                        if (null !== $data) {
                            $request->setParam('@data', $data);
                        }
                    }
                    
                    if (gettype($route->getController()) == 'object') {
                        if (isset($services)) {
                            call_user_func($route->getController(), $request, $response, $services);
                        } else {
                            call_user_func($route->getController(), $request, $response);
                        }
                    } else {
                        $controller = $route->getController()[0];
                        $method = $route->getController()[1];
                        $request->setController($route->getController()[0]);
                        
                        //$model = str_replace("Controller", "Model", $route->getController()[0]);
                        
                        $controller = new $controller($request, $response, $services);
                        //$controller->loadModel($request, $services);
                        //$controller->{$method}($request, $services);
                        Content::$segments = $request->getSegments();
                        Debug::addMessage('params', 'path', $route->getPath(), 'session');
                        Debug::addMessage('params', 'segments', $request->getSegments(), 'session');
                        Debug::addMessage('params', 'closure', $route->getController(), 'session');
                        
                        if (isset($services)) {
                            call_user_func(array($controller, $method), $request, $response, $services);
                        } else {
                            call_user_func(array($controller, $method), $request, $response);
                        }
                    }
                    return;
                }
            }
            //$path = array_filter(explode('/', trim($this->routes[$request_method])));
            $request->setSegments(explode('/', $uri));
            // Check for default controller. Match any request
            if ($this->default_controller) {
                //call_user_func($this->default_controller, $request, new Response, $this->services);
                isset($this->services) ? call_user_func($this->default_controller, $request, new Response, $this->services) : call_user_func($this->default_controller, $request, new Response);
                return;
            }
            
            // Exception for routes not found
            throw new RouteNotFoundException(sprintf('The request URI "%s" don\'t match any route.', $request_uri));
        }
        
        /**
         * getPattern
         *
         * Return the regex pattern for a string path
         * @param string $path
         * @return string
         */
        private function getPattern(string $path): string
        {
            $path = str_replace('/', '\/', pathFormat($path));
            $path = preg_replace('#{(\w+)}#', '(?<$1>\w+)', $path); // Replace wildcards
            
            return '#^' . $path . '$#i';
        }
        
        /**
         * filterRequestUri
         *
         * Filter a URI with GET params
         * @param string $uri
         * @return string
         */
        private function filterRequestUri(string $uri): string
        {
            $uri = parse_url($uri, PHP_URL_PATH);
            return rawurldecode($uri);
        }
        
        /**
         * filterArguments
         *
         * Filter an arguments array. Unnamed matches are pushed to a lineal array.
         * @param array $params
         * @return array
         */
        private function filterArguments(array $params): array
        {
            $matches = [];
            
            foreach ($params as $key => $item) {
                if (is_int($key)) {
                    unset($params[$key]);
                    $matches[] = $item;
                }
            }
            
            return [$params, $matches];
        }
        
        /**
         * filterServices
         *
         * Keep the services defined in list, otherwise unregister
         * @param array $names
         * @return Services
         */
        private function filterServices(array $names): Services
        {
            $new_service = clone $this->services;
            $names = array_diff($new_service->keys(), $names);
            $new_service->unregister(...$names);
            
            return $new_service;
        }
        
    }
    
    /**
     * pathFormat
     *
     * Convert a string to valid path format for the router
     * @param string $value
     * @return string
     */
    function pathFormat(string $value): string
    {
        return '/' . trim(trim($value), '/\\');
    }

