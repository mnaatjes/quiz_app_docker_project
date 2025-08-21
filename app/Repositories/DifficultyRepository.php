<?php
    namespace App\Repositories;
    use App\Models\DifficultyModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * AnswerRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class DifficultyRepository extends BaseRepository {

        protected string $tableName     = "difficulties";
        protected string $modelClass    = DifficultyModel::class;

    }
?>