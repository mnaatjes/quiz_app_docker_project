<?php

    /**
     * Sample Controller Class for Debugging
     */
    class SampleController {
        public ?string $testParam;
        private object $repo;
        public function __construct($repository, ?string $test_param=NULL){
            
            $this->repo = $repository;
            $this->testParam = $test_param;
        }
        public function log(HttpRequest $req, HttpResponse $res, array $params){
            //var_dump("log() method from SampleController executed!");
            //var_dump($this->testParam);
            var_dump($req->getURI());
            var_dump($params);
        }
    }

?>