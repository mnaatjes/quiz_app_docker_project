<?php
    namespace App\Models;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;

    class QuestionModel extends BaseModel {

        private $id;
        private $questionText;
        private $type;
        private $categoryId;
        private $createdAt;
        private $updatedAt;
        private $isActive;
        private $imageUrl;
        private $hintText;
        private $sourceReference;
        private $timesAsked;
        private $correctAttemptsCount;
        private $incorrectAttemptsCount;
        private $difficultyId;
        private $skipCount;
        private $totalTimeSpentSeconds;
        private $lastStatUpdateAt;
        private $lastPlayedAt;
    }

?>