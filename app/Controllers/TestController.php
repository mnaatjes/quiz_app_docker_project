<?php
    namespace App\Controllers;
    use mnaatjes\mvcFramework\MVCCore\BaseController;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;

    /**-------------------------------------------------------------------------*/
    /**
     * Test Controller inhereting BaseController
     */
    /**-------------------------------------------------------------------------*/
    class TestController extends BaseController {

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function index(HttpRequest $req, HttpResponse $res): void{
            $data = $this->repository->all();
            
            $res->sendJson(array_map(function($obj){return $obj->toJSON();}, $data));

        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, array $params): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function create(HttpRequest $req, HttpResponse $res, array $params): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function store(HttpRequest $req, HttpResponse $res, array $params): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function edit(HttpRequest $req, HttpResponse $res, array $params): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function update(HttpRequest $req, HttpResponse $res, array $params): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function destroy(HttpRequest $req, HttpResponse $res, array $params): void{}
    }
?>