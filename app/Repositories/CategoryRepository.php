<?php
    namespace App\Repositories;
    use App\Models\CategoryModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * AnswerRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class CategoryRepository extends BaseRepository {

        protected string $tableName     = "categories";
        protected string $modelClass    = CategoryModel::class;

    }
?>