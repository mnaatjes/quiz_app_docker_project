<?php

require_once("Database.php");
/**-------------------------------------------------------------------------*/
/**
 * QuizRepository Class
 *
 * Handles the persistence and retrieval of Quiz objects from the database.
 * This class implements the Repository Pattern.
 */
/**-------------------------------------------------------------------------*/
class QuizRepository {
    /**
     * @var PDO The PDO database connection object.
     */
    private PDO $db;

    /**
     * @var QuestionRepository $questionRepository
     */
    private QuestionRepository $questionRepository;

    /**
     * QuizRepository constructor.
     *
     * Initializes the repository by getting the shared database connection.
     */
    public function __construct(QuestionRepository $question_repository) {
        /**
         * Get DB Instance
         */
        $this->db = Database::getInstance()->getConnection();
        /**
         * Assign Question Depository for Quiz Repo
         */
        $this->questionRepository = $question_repository;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Finds a quiz by its ID.
     *
     * @param int $id The quiz ID.
     * @return Quiz|null Returns a Quiz object if found, otherwise null.
     */
    /**-------------------------------------------------------------------------*/
    public function findById(int $id): ?Quiz {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->execute([$id]);
        $quizData = $stmt->fetch();

        if (!$quizData) {
            return null;
        }

        return $this->mapToQuiz($quizData);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Saves a quiz to the database.
     *
     * This method handles both new quiz creation (insert) and updating existing quizzes.
     *
     * @param Quiz $quiz The Quiz object to save.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    public function save(Quiz $quiz): Quiz|bool {
        if ($quiz->getId() === null) {
            /**
             * Validate Insertion
             */
            $success = $this->insert($quiz);
            if($success){
                /**
                 * Fetch and return complete record
                 */
                return $this->findById($quiz->getId());
            }
        }
        return $this->update($quiz);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Inserts a new quiz into the database.
     *
     * @param Quiz $quiz The Quiz object to insert.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    private function insert(Quiz $quiz): bool {
        $sql = "INSERT INTO quizzes (quiz_id_map, description, category_id, difficulty_id) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);


        $result = $stmt->execute([
            $quiz->getQuizIdMap(),
            $quiz->getDescription(),
            $quiz->getCategoryId(),
            $quiz->getDifficultyId(),
        ]);

        if ($result) {
            $quiz->setId((int)$this->db->lastInsertId());
        }

        return $result;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Updates an existing quiz in the database.
     *
     * @param Quiz $quiz The Quiz object to update.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    private function update(Quiz $quiz): bool {
        $sql = "UPDATE quizzes SET quiz_id_map = ?, description = ?, 
                category_id = ?, difficulty_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        // Convert the array to a JSON string for storage
        $quizIdMapJson = json_encode($quiz->getQuizIdMap());

        return $stmt->execute([
            $quizIdMapJson,
            $quiz->getDescription(),
            $quiz->getCategoryId(),
            $quiz->getDifficultyId(),
            $quiz->getId(),
        ]);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Deletes a quiz from the database.
     *
     * @param Quiz $quiz The Quiz object to delete.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    public function delete(Quiz $quiz): bool {
        if ($quiz->getId() === null) {
            return false;
        }

        $sql = "DELETE FROM quizzes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$quiz->getId()]);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Maps an associative array from the database to a Quiz object.
     *
     * @param array $quizData The quiz data from the database.
     * @return Quiz The mapped Quiz object.
     */
    /**-------------------------------------------------------------------------*/
    private function mapToQuiz(array $quizData): Quiz {
        // Decode the JSON string back into a PHP array

        $quiz = new Quiz($quizData['category_id'], $quizData['difficulty_id'], $quizData["quiz_id_map"]);
        $quiz->setId((int)$quizData['id']);
        $quiz->setDescription($quizData['description']);
        $quiz->setCreatedAt($quizData['created_at']);
        $quiz->setUpdatedAt($quizData['updated_at']);
        /**
         * Return quiz objest
         */
        return $quiz;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Create Quiz
     * 
     * @uses $questionRepository
     */
    /**-------------------------------------------------------------------------*/
    public function createQuiz(int $category_id, int $difficulty_id){
        /**
         * Pull Question Ids as JSON string
         */
        $quiz_id_map = $this->questionRepository->pullQuestionIdMap($category_id, $difficulty_id);

        /**
         * Create Quiz Model Object
         */
        $quiz = $this->save(new Quiz(
            $category_id,
            $difficulty_id,
            $quiz_id_map
        ));
        //var_dump($quiz);
        /**
         * Return Quiz Model Object
         */
        return $quiz;

    }
}