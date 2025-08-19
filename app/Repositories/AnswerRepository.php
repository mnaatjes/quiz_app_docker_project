<?php
    namespace App\Repositories;
    use App\Models\AnswerModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * AnswerRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class AnswerRepository extends BaseRepository {

        protected string $tableName     = "answers";
        protected string $modelClass    = AnswerModel::class;

    }
?>