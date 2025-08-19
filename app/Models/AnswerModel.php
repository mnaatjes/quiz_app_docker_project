<?php
    namespace App\Models;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;

    class AnswerModel extends BaseModel {

        private $id;
        private $questionId;
        private $isCorrect;
        private $answerText;
        private $createdAt;
        private $updatedAt;
        private $explanationText;
        private $timesShown;
        private $selectionCount;
    }

?>
