<?php
    namespace App\Controllers;
    use mnaatjes\mvcFramework\MVCCore\BaseController;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;

    /**-------------------------------------------------------------------------*/
    /**
     * Application Controller for Quiz App
     * 
     * @since 1.0.0: 
     * - Decoupled from BaseRepository
     * - Added method for attaching services; default empty
     * 
     * @since 1.1.0:
     * - Cleared show() method
     * - Cleared index() method
     * 
     * @version 1.2.0
     */
    /**-------------------------------------------------------------------------*/
    class AppController extends BaseController {

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function index(HttpRequest $req, HttpResponse $res): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * Returns one instance
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, array $params): void{}

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

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
    }
?>