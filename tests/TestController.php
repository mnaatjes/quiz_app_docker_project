<?php

    class TestController {
        private object $repo;
        public function __construct($repository){
            $this->repo = $repository;
        }

        public function actionAll(){
            var_dump($this->repo->getAllIds());
        }
        
        public function actionWhere(){
            
            $query = $this->repo->findByWhere([["id", "<", 3]]);

            //var_dump(json_encode($query, JSON_PRETTY_PRINT));
            //$this->repo->findBy($params);
            //$this->repo->findById($params["id"]);
        }

        public function actionHydrate(){
            $model = $this->repo->hydrate([
                "id" => 3, 
                "text" => "Lorem Ipsum",
                "is_active" => false,
                "created_at" => time(),
                "updated_at" => time(),
            ]);

            var_dump(json_encode($model));
        }
    }
?>