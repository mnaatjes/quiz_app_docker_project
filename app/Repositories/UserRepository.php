<?php
    namespace App\Repositories;
    use App\Models\UserModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * UserRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class UserRepository extends BaseRepository {

        protected string $tableName     = "users";
        protected string $modelClass    = UserModel::class;

    }
?>