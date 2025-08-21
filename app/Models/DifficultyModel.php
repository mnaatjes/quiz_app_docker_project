<?php

    namespace App\Models;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;
    
    class DifficultyModel extends BaseModel {

        /**
         * @var int The primary key of the difficulty. Corresponds to the 'id' column.
         */
        private $id;

        /**
         * @var string The name of the difficulty level. Corresponds to the 'name' column.
         */
        private $name;

        /**
         * @var int The numerical value representing the difficulty level. Corresponds to the 'level_value' column.
         */
        private $levelValue;

        /**
         * @var string|null A description of the difficulty level. Corresponds to the 'description' column.
         */
        private $description;

        /**
         * @var DateTime The timestamp when the record was created. Corresponds to the 'created_at' column.
         */
        private $createdAt;

        /**
         * @var DateTime The timestamp when the record was last updated. Corresponds to the 'updated_at' column.
         */
        private $updatedAt;
    }
?>