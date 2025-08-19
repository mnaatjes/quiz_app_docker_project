<?php
    namespace App\Models;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;

    class QuizModel extends BaseModel {

        private $id;
        private $quizIdMap;
        private $title;
        private $categoryId;
        private $difficultyId;
        private $createdAt;
        private $updatedAt;
    }

?>
