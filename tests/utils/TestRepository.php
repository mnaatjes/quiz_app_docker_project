<?php

    use mnaatjes\DataAccess\BaseRepository;
    use mnaatjes\DataAccess\ORM;

    /**-------------------------------------------------------------------------*/
    /**
     * TestRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class TestRepository extends BaseRepository {

        protected string $tableName     = "users";
        protected string $modelClass    = "TestModel";

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/

        /**-------------------------------------------------------------------------*/
    }
?>