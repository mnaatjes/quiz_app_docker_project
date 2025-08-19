<?php
    namespace App\Repositories;
    use App\Models\QuizModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * QuizRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class QuizRepository extends BaseRepository {

        protected string $tableName     = "quizzes";
        protected string $modelClass    = QuizModel::class;

    }
?>