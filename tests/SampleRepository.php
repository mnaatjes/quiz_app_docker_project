<?php

    /**
     * SampleRepository
     * Test Repo for ORM Integration
     * 
     */
    class SampleRepository {
        private ORM $orm;

        public function __construct(ORM $orm_instance){
            $this->orm = $orm_instance;
        }
        public function findById(int $id){
            return $this->orm->getRepository("Sample")->find($id);
        }
    }
?>