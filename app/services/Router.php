<?php
    /**
     * PHP Simple HTTP Manager:
     * 
     * Lightweight HTTP Request and Response manager with Router
     * 
     * @author Michael Naatjes michael.naatjes87@gmail.com
     * @version 2.0
     * @since 1.0
     *  - Created
     *  - Integrated
     *  - Tested
     * 
     * @since 2.0:
     *  - Added instance parameter $container
     *  - Modified addRoute() method:
     *  - Modified dispatch() method:
     */

    /**
     * Require Framework Classes
     * TODO: Move to Container Dependency Injection
     */
    //require_once('HttpRequest.php');
    //require_once('HttpResponse.php');

    /**-------------------------------------------------------------------------*/
    /**
     * Request Class
     * 
     */
    /**-------------------------------------------------------------------------*/
    class Router
    {
        private array $routes = [];
        private Container $container;
        public HttpRequest $request;
        public HttpResponse $response;


        public function __construct(Container $container) {
            $this->container = $container;
        }
        /**-------------------------------------------------------------------------*/
        /**
         * Add a GET route.
         *
         * @param string $path The URL path (e.g., '/users', '/users/{id}').
         * @param string|array|callable $handler The callback function or method to execute.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function get(string $path, string|array|callable $handler): self{
            $this->addRoute('GET', $path, $handler);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a POST route.
         *
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method to execute.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function post(string $path, string|array|callable $handler): self{
            $this->addRoute('POST', $path, $handler);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a PUT route.
         *
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method to execute.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function put(string $path, string|array|callable $handler): self{
            $this->addRoute('PUT', $path, $handler);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a DELETE route.
         *
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method to execute.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function delete(string $path, string|array|callable $handler): self{
            $this->addRoute('DELETE', $path, $handler);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a route to the internal routes array.
         *
         * @param string $method The HTTP method (e.g., 'GET', 'POST').
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        private function addRoute(string $method, string $path, string|array|callable $handler): void{
            // Convert path to a regex pattern, handling dynamic segments like {id}
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $path);
            // Ensure the pattern matches the entire path and is case-insensitive
            $pattern = '#^' . $pattern . '$#i';

            $this->routes[] = [
                'method' => strtoupper($method),
                'path' => $path, // Store original path for parameter extraction
                'pattern' => $pattern,
                'handler' => $handler
            ];
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Dispatches the incoming request to the appropriate handler.
         *
         * @param HttpRequest $request The incoming HttpRequest object.
         * @param HttpResponse $response The HttpResponse object to build the response.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function dispatch(HttpRequest $req, HttpResponse $res): void{
            /**
             * Define HTTP Request Properties
             */
            $requestMethod   = $req->getMethod();
            $requestPathInfo = $req->getPathInfo();

            foreach ($this->routes as $route) {
                // Check if method matches and path matches the pattern
                if ($route['method'] === $requestMethod && preg_match($route['pattern'], $requestPathInfo, $matches)) {
                    // Remove the full match (index 0) from the matches array
                    array_shift($matches);

                    // Extract parameter names from the original path
                    preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $route['path'], $paramNames);
                    $paramNames = $paramNames[1];

                    // Combine parameter names with extracted values
                    $params = array_combine($paramNames, $matches);

                    /**
                     * @since 2.0
                     *  - Adapted type handling for strings, arrays, and callables
                     *  - Defined $controllerKey and $methodName
                     *  - Added try method
                     *  - Modified to accept container instance methods
                     */
                    if(is_callable($route["handler"])){
                        /**
                         * Handler is function / invoked
                         */
                        try {
                            /**
                             * Execute Handler Function:
                             * - Call the handler with the HttpRequest, HttpResponse, and extracted parameters
                             * - Return
                             */
                            call_user_func($route['handler'], $req, $res, $params);
                            return;

                        } catch(Exception $e){
                            // Set response for error condition
                            $this->sendErrorResponse($res, $e);
                        }

                    } elseif(is_string($route["handler"])){
                        /**
                         * Handler is reference to class in dependency w/out method
                         */
                        try {
                            /**
                             * Gather Class Parameters
                             */
                            $pos = strpos($route["handler"], "@");

                            // Throw exception if cannot find delimiter
                            if(!is_int($pos)){
                                throw new Exception("Unable to determine path!");
                            }

                            // Split values
                            $handler = [
                                "className"  => substr($route["handler"], 0, $pos),
                                "methodName" => substr($route["handler"], $pos + 1),
                            ];
                            
                            // Execute Handler
                            $instance = $this->container->get($handler["className"]);
                            call_user_func_array(
                                [$instance, $handler["methodName"]],
                                [$req, $res, $params]
                            );
                            return;

                        } catch(Exception $e) {
                            // Set response for error condition
                            $this->sendErrorResponse($res, $e);
                        }
                    } elseif(is_array($route["handler"])){
                        /**
                         * Handler is reference to $container dependency w/ method
                         */
                            // Split values
                            $handler = [
                                "className"  => $route["handler"][0],
                                "methodName" => $route["handler"][1]
                            ];
                            
                            // Execute Handler
                            $instance = $this->container->get($handler["className"]);
                            call_user_func_array(
                                [$instance, $handler["methodName"]],
                                [$req, $res, $params]
                            );
                            return;
                    }
                }
            }

            /**
             * Route Not Found
             */
            $this->sendErrorResponse($res, "Could not find matching route path!");
        }

        /**-------------------------------------------------------------------------*/
        /**
         * HTTP Method
         * Sends error response
         */
        /**-------------------------------------------------------------------------*/
        private function sendErrorResponse(HttpResponse $res, string|Exception $e){
            /**
             * Form and execute response 
             */
            $res->setStatusCode(404, 'Not Found');
            $res->addHeader('Content-Type', 'text/plain');
            $res->setBody(json_encode([
                "message"   => "404 Not Found: The requested resource could not be found",
                "error"     => $e
            ]));
            $res->send();
        }
    }
?>