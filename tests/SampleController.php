<?php

    /**
     * Sample Controller Class for Debugging
     */
    class SampleController {
        public ?string $testParam;
        public function __construct(?string $test_param=NULL){
            var_dump("SampleController Instance Created");
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