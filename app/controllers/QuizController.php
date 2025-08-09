<?php

/**-------------------------------------------------------------------------*/
/**
 * Quiz Controller Class
 * 
 * 
 */
/**-------------------------------------------------------------------------*/
class QuizController {

    /**
     * @var QuizRepository
     */
    private QuizRepository $quizRepository;
    private QuestionRepository $questionRepository;
    private AnswerRepository $answerRepository;
    
    /**-------------------------------------------------------------------------*/
    /**
     * Constructor
     */
    /**-------------------------------------------------------------------------*/
    public function __construct(QuizRepository $quiz_repository, QuestionRepository $question_repository, AnswerRepository $answer_repository){
        /**
         * Assign Dependency
         */
        $this->quizRepository       = $quiz_repository;
        $this->questionRepository   = $question_repository;
        $this->answerRepository     = $answer_repository;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Create Quiz
     */
    /**-------------------------------------------------------------------------*/
    public function createQuizAction($req, $res){
        $category_id = 4;
        $difficulty_id = 1;
        $user_id = 12;
        $length = 10;
        return;
        /**
         * Create New Quiz Object
         */
        $quiz = $this->quizRepository->createQuiz($category_id, $difficulty_id);
        
        /**
         * Assemble Questions Response Object
         * @var array $questions_object
         */
        $questions_object = [];
        foreach(json_decode($quiz->getQuizIdMap()) as $question_id){
            /**
             * @var QuestionResponseObject
             */
            $questions_object[] = new class($question_id, $this->questionRepository, $this->answerRepository){
                // Props
                public int $id;
                public string $question_text;
                public array $answers = [];

                public function __construct($question_id, $question_repo, $answer_repo){
                    // Query Questions Table
                    $model = $question_repo->findById($question_id);
                    
                    // Assign Object Properties
                    $this->id = $model->getId();
                    $this->question_text = $model->getQuestionText();

                    // Find Answers
                    foreach($answer_repo->pullAnswers([$question_id]) as $answer){
                        $this->answers[] = new class($answer->getId(), $answer->isCorrect(), $answer->getAnswerText()){
                            // Props
                            public int $id;
                            public int $is_correct;
                            public string $answer_text;

                            public function __construct($id, $is_correct, $text){
                                $this->id = $id;
                                $this->is_correct = $is_correct;
                                $this->answer_text = $text;
                            }
                        };
                    }
                }
            };
        }

        /**
         * @var QuizResponseObject $quizObject
         */
        $quizObject = new QuizResponseObject(
            $quiz->getId(),
            $quiz->getDescription(),
            $questions_object
        );

        /**
         * Send Reponse
         */
        header("Content-Type: application/json");
        echo (json_encode($quizObject));

    }
}
?>