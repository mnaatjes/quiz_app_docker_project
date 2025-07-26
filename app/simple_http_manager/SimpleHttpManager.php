<?php
    /**
     * PHP Simple HTTP Manager:
     * 
     * Lightweight HTTP Request and Response manager with Router
     * 
     * @author Michael Naatjes michael.naatjes87@gmail.com
     * @version 1.0
     * @since 1.0
     *  - Created
     *  - Integrated
     *  - Tested
     */

    /**
     * Require Framework Classes
     */
    require_once('HttpRequest.php');
    require_once('HttpResponse.php');

    /**
     * Request Class
     * 
     */
    class Router
    {
        private $routes = [];
        public $request;
        public $response;


        public function __construct() {
            $this->request  = new HttpRequest();
            $this->response = new HttpResponse();
        }
        /**
         * Add a GET route.
         *
         * @param string $path The URL path (e.g., '/users', '/users/{id}').
         * @param callable $handler The callback function or method to execute.
         * @return self
         */
        public function get(string $path, callable $handler): self
        {
            $this->addRoute('GET', $path, $handler);
            return $this;
        }

        /**
         * Add a POST route.
         *
         * @param string $path The URL path.
         * @param callable $handler The callback function or method to execute.
         * @return self
         */
        public function post(string $path, callable $handler): self
        {
            $this->addRoute('POST', $path, $handler);
            return $this;
        }

        /**
         * Add a PUT route.
         *
         * @param string $path The URL path.
         * @param callable $handler The callback function or method to execute.
         * @return self
         */
        public function put(string $path, callable $handler): self
        {
            $this->addRoute('PUT', $path, $handler);
            return $this;
        }

        /**
         * Add a DELETE route.
         *
         * @param string $path The URL path.
         * @param callable $handler The callback function or method to execute.
         * @return self
         */
        public function delete(string $path, callable $handler): self
        {
            $this->addRoute('DELETE', $path, $handler);
            return $this;
        }

        /**
         * Add a route to the internal routes array.
         *
         * @param string $method The HTTP method (e.g., 'GET', 'POST').
         * @param string $path The URL path.
         * @param callable $handler The callback function or method.
         * @return void
         */
        private function addRoute(string $method, string $path, callable $handler): void
        {
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

        /**
         * Dispatches the incoming request to the appropriate handler.
         *
         * @param HttpRequest $request The incoming HttpRequest object.
         * @param HttpResponse $response The HttpResponse object to build the response.
         * @return void
         */
        public function dispatch(): void
        {
            $requestMethod = $this->request->getMethod();
            $requestPathInfo = $this->request->getPathInfo();

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

                    // Call the handler with the HttpRequest, HttpResponse, and extracted parameters
                    call_user_func($route['handler'], $this->request, $this->response, $params);
                    return; // Route found and handled, exit dispatch
                }
            }

            // If no route matched
            $this->response->setStatusCode(404, 'Not Found');
            $this->response->addHeader('Content-Type', 'text/plain');
            $this->response->setBody('404 Not Found: The requested resource could not be found.');
            $this->response->send();
        }
    }
?>