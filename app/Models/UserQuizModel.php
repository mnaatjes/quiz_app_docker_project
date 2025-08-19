<?php
    namespace App\Models;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;

    class UserQuizModel extends BaseModel {

        private $id;
        private $userId;
        private $quizId;
        private $startedAt;
        private $completedAt;
        private $score;
        private $totalQuestions;
        private $correctAnswersCount;
        private $incorrectAnswersCount;
        private $skippedQuestionsCount;
        private $timeTakenSeconds;
        private $isCompleted;
        private $status;
        private $lastActivityAt;
        private $createdAt;
        private $updatedAt;
    }

?>