<?php
    namespace mnaatjes\DataAccess\utils;
    use mnaatjes\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * TestRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class TestRepository extends BaseRepository {

        protected string $tableName     = "products";
        protected string $modelClass    = TestModel::class;

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/

        /**-------------------------------------------------------------------------*/
    }
?>