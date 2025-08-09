<?php

    class HttpResponse
    {
        private $headers = [];
        private $body = '';
        private $statusCode = 200;
        private $statusText = 'OK';

        /**
         * Set the HTTP status code and optional status text.
         *
         * @param int $statusCode The HTTP status code (e.g., 200, 404, 500).
         * @param string $statusText Optional custom status text. If not provided,
         * a default will be used based on the status code.
         * @return self
         */
        public function setStatusCode(int $statusCode, string $statusText = ''): self
        {
            $this->statusCode = $statusCode;
            $this->statusText = $statusText ?: $this->getDefaultStatusText($statusCode);
            return $this;
        }

        /**
         * Add a custom HTTP header.
         *
         * @param string $name The name of the header (e.g., 'Content-Type').
         * @param string $value The value of the header (e.g., 'application/json').
         * @param bool $replace Whether to replace an existing header with the same name.
         * Defaults to true.
         * @return self
         */
        public function addHeader(string $name, string $value, bool $replace = true): self
        {
            $this->headers[] = ['name' => $name, 'value' => $value, 'replace' => $replace];
            return $this;
        }

        /**
         * Set the response body.
         *
         * @param string $body The content of the response body.
         * @return self
         */
        public function setBody(string $body): self
        {
            $this->body = $body;
            return $this;
        }

        /**
         * Send all headers and the response body.
         * This method will prevent further output once called.
         *
         * @return void
         */
        public function send(): void
        {
            // Set the HTTP status line
            header(sprintf('HTTP/1.1 %d %s', $this->statusCode, $this->statusText), true, $this->statusCode);

            // Send all custom headers
            foreach ($this->headers as $header) {
                header(sprintf('%s: %s', $header['name'], $header['value']), $header['replace']);
            }

            // Send the response body
            echo $this->body;

            // Ensure no further output is sent
            exit();
        }

        /**
         * Helper method to get default HTTP status text for common status codes.
         *
         * @param int $statusCode The HTTP status code.
         * @return string The default status text.
         */
        private function getDefaultStatusText(int $statusCode): string
        {
            switch ($statusCode) {
                case 100: return 'Continue';
                case 101: return 'Switching Protocols';
                case 200: return 'OK';
                case 201: return 'Created';
                case 202: return 'Accepted';
                case 203: return 'Non-Authoritative Information';
                case 204: return 'No Content';
                case 205: return 'Reset Content';
                case 206: return 'Partial Content';
                case 300: return 'Multiple Choices';
                case 301: return 'Moved Permanently';
                case 302: return 'Found';
                case 303: return 'See Other';
                case 304: return 'Not Modified';
                case 307: return 'Temporary Redirect';
                case 308: return 'Permanent Redirect';
                case 400: return 'Bad Request';
                case 401: return 'Unauthorized';
                case 403: return 'Forbidden';
                case 404: return 'Not Found';
                case 405: return 'Method Not Allowed';
                case 406: return 'Not Acceptable';
                case 408: return 'Request Timeout';
                case 409: return 'Conflict';
                case 410: return 'Gone';
                case 411: return 'Length Required';
                case 412: return 'Precondition Failed';
                case 413: return 'Payload Too Large';
                case 414: return 'URI Too Long';
                case 415: return 'Unsupported Media Type';
                case 429: return 'Too Many Requests';
                case 500: return 'Internal Server Error';
                case 501: return 'Not Implemented';
                case 502: return 'Bad Gateway';
                case 503: return 'Service Unavailable';
                case 504: return 'Gateway Timeout';
                case 505: return 'HTTP Version Not Supported';
                default: return 'Unknown Status';
            }
        }
    }
?>