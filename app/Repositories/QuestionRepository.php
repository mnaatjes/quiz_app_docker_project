<?php
    namespace App\Repositories;

    use App\Models\QuestionModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * QuestionRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class QuestionRepository extends BaseRepository {

        protected string $tableName     = "questions";
        protected string $modelClass    = QuestionModel::class;

    }
?>