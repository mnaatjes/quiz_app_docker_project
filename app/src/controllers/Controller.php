<?php

    /**-------------------------------------------------------------------------*/
    /**
     * Controller Abstract Class
     * 
     * @var object Controller
     * @version 1.0
     * @since 1.0:
     *      
     */
    /**-------------------------------------------------------------------------*/
    abstract class Controller {

        protected static $view;
        protected static $model;
        protected static $req;
        protected static $res;
        protected static $response_type;

        /**-------------------------------------------------------------------------*/
        /**
         * Invoke
         */
        /**-------------------------------------------------------------------------*/
        public function __invoke($request, $response){
            /**
             * Check that $view and $model are defined
             */
            if(!isset(static::$model)){
                throw new Exception("No Model associated with Controller!");
            }
            if(isset(static::$view)){
                static::$response_type = 'view_engine';
            }
            
            /**
             * Check Response and Request Objects
             */
            if(!isset($request)){
                throw new Exception("HTTP Request Object Missing!");
            }
            if(!isset($response)){
                throw new Exception("HTTP Response Object Missing!");
            }

            /**
             * Set Request and Response Objects
             */
            static::$req = $request;
            static::$res = $response;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Index: Display list of resources
         */
        /**-------------------------------------------------------------------------*/
        public function index(){}

        /**-------------------------------------------------------------------------*/
        /**
         * Show: Display single record by id
         */
        /**-------------------------------------------------------------------------*/
        public function show(){}
                
        /**-------------------------------------------------------------------------*/
        /**
         * Create: Create New Record
         */
        /**-------------------------------------------------------------------------*/
        public function create(){}

        /**-------------------------------------------------------------------------*/
        /**
         * Store: Insert a new record
         */
        /**-------------------------------------------------------------------------*/ 
        public function store(){}

        /**-------------------------------------------------------------------------*/ 
        /**
         * Edit: Update existing record
         */
        /**-------------------------------------------------------------------------*/ 
        public function edit(){}

        /**-------------------------------------------------------------------------*/
        /**
         * Destroy: Delete existing record
         */
        /**-------------------------------------------------------------------------*/
        public function destroy(){}
    }