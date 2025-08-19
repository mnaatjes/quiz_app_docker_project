<?php
    namespace App\Repositories;
    use App\Models\UserQuizModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * UserQuizRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class UserQuizRepository extends BaseRepository {

        protected string $tableName     = "user_quizzes";
        protected string $modelClass    = UserQuizModel::class;

    }
?>