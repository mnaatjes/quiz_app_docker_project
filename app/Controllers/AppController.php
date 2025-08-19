<?php
    namespace App\Controllers;

use App\Models\UserModel;
use mnaatjes\mvcFramework\MVCCore\BaseController;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;
use mnaatjes\mvcFramework\MVCCore\BaseModel;

    /**-------------------------------------------------------------------------*/
    /**
     * Test Controller inhereting BaseController
     */
    /**-------------------------------------------------------------------------*/
    class AppController extends BaseController {

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function index(HttpRequest $req, HttpResponse $res): void{

            /**
             * TODO: Change
             * Debugging
             */
            $data = $this->repository->all();
            $res->addHeader("Content-Type", "application/json");
            $res->setBody(json_encode(array_map(function($obj){return $obj->toArray();}, $data), JSON_PRETTY_PRINT));
            $res->send();

        }

        /**-------------------------------------------------------------------------*/
        /**
         * Returns one instance
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, array $params): void{
            // Pull data
            $model = $this->repository->findById($params["id"]);
            
            // Validate
            if(is_a($model, BaseModel::class)){
                $res->setBody($model->toJSON());
            } else {
                $res->setBody("Error!");
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Save a New Resource
         */
        /**-------------------------------------------------------------------------*/
        public function create(HttpRequest $req, HttpResponse $res, array $params): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * Redundant
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