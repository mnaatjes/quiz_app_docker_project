<?php
    namespace App\Repositories;
    use App\Models\TestModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * TestRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class TestRepository extends BaseRepository {

        protected string $tableName     = "products";
        protected string $modelClass    = TestModel::class;

    }
?>