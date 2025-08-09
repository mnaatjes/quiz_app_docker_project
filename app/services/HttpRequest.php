<?php

class HttpRequest
{
    private $method;
    private $uri;
    private $pathInfo;
    private $headers;
    private $queryParams; // GET parameters
    private $postParams;  // POST parameters
    private $body;
    private $files;       // Uploaded files

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->pathInfo = $_SERVER['PATH_INFO'] ?? '';
        $this->headers = $this->getIncomingHeaders();
        $this->queryParams = $_GET;
        $this->postParams = $_POST;
        $this->body = file_get_contents('php://input'); // Raw request body
        $this->files = $_FILES;
    }

    /**
     * Get the HTTP request method (e.g., 'GET', 'POST', 'PUT', 'DELETE').
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the full request URI (e.g., '/path/to/resource?param=value').
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get a specific request header by name (case-insensitive).
     *
     * @param string $name The name of the header.
     * @param mixed $default The default value to return if the header is not found.
     * @return string|null The header value, or the default if not found.
     */
    public function getHeader(string $name, $default = null): ?string
    {
        $name = strtolower($name);
        foreach ($this->headers as $headerName => $headerValue) {
            if (strtolower($headerName) === $name) {
                return $headerValue;
            }
        }
        return $default;
    }

    /**
     * Get all request headers.
     *
     * @return array An associative array of headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get a specific GET query parameter.
     *
     * @param string $name The name of the query parameter.
     * @param mixed $default The default value to return if the parameter is not found.
     * @return mixed The query parameter value, or the default if not found.
     */
    public function getQueryParam(string $name, $default = null)
    {
        return $this->queryParams[$name] ?? $default;
    }

    /**
     * Get all GET query parameters.
     *
     * @return array An associative array of query parameters.
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Get a specific POST parameter.
     *
     * @param string $name The name of the POST parameter.
     * @param mixed $default The default value to return if the parameter is not found.
     * @return mixed The POST parameter value, or the default if not found.
     */
    public function getPostParam(string $name, $default = null)
    {
        return $this->postParams[$name] ?? $default;
    }

    /**
     * Get all POST parameters.
     *
     * @return array An associative array of POST parameters.
     */
    public function getPostParams(): array
    {
        return $this->postParams;
    }

    /**
     * Get the raw request body (useful for JSON, XML, etc.).
     *
     * @return string The raw request body content.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get uploaded files.
     *
     * @return array The $_FILES superglobal array.
     */
    public function getFiles(): array
    {
        return $this->files;
    }
    /**
     * Get the "path info" part of the URI.
     * This is the part of the URI after the script name but before the query string.
     * For example, if the URI is '/app.php/items/123?action=edit',
     * getPathInfo() would return '/items/123'.
     *
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->pathInfo;
    }
    /**
     * Helper method to retrieve all incoming HTTP headers.
     *
     * @return array An associative array of request headers.
     */
    private function getIncomingHeaders(): array
    {
        $headers = [];
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }
        return $headers;
    }
}