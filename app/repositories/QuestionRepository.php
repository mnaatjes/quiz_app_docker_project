<?php
/**-------------------------------------------------------------------------*/
/**
 * QuestionRepository Class
 *
 * Handles the persistence and retrieval of Question objects from the database.
 * This class implements the Repository Pattern.
 */
/**-------------------------------------------------------------------------*/
class QuestionRepository {

    /**
     * @var PDO The PDO database connection object.
     */
    private PDO $db;

    /**-------------------------------------------------------------------------*/
    /**
     * QuestionRepository constructor.
     *
     * Initializes the repository by getting the shared database connection.
     */
    /**-------------------------------------------------------------------------*/
    public function __construct() {
        /**
         * Create / Find DB Instance
         */
        $this->db = Database::getInstance()->getConnection();
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Finds a question by its ID.
     *
     * @param int $id The question ID.
     * @return Question|null Returns a Question object if found, otherwise null.
     */
    /**-------------------------------------------------------------------------*/
    public function findById(int $id): ?Question {
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        $questionData = $stmt->fetch();

        if (!$questionData) {
            return null;
        }

        return $this->mapToQuestion($questionData);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Saves a question to the database.
     *
     * This method handles both new question creation (insert) and updating existing questions.
     *
     * @param Question $question The Question object to save.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    public function save(Question $question): bool {
        if ($question->getId() === null) {
            return $this->insert($question);
        }
        return $this->update($question);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Inserts a new question into the database.
     *
     * @param Question $question The Question object to insert.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    private function insert(Question $question): bool {
        $sql = "INSERT INTO questions (question_text, type, category_id, is_active, 
                                     image_url, hint_text, source_reference, difficulty_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            $question->getQuestionText(),
            $question->getType(),
            $question->getCategoryId(),
            $question->getIsActive(),
            $question->getImageUrl(),
            $question->getHintText(),
            $question->getSourceReference(),
            $question->getDifficultyId(),
        ]);

        if ($result) {
            $question->setId((int)$this->db->lastInsertId());
        }

        return $result;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Updates an existing question in the database.
     *
     * @param Question $question The Question object to update.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    private function update(Question $question): bool {
        $sql = "UPDATE questions SET question_text = ?, type = ?, category_id = ?, 
                is_active = ?, image_url = ?, hint_text = ?, source_reference = ?, 
                times_asked = ?, correct_attempts_count = ?, incorrect_attempts_count = ?, 
                difficulty_id = ?, skip_count = ?, total_time_spent_seconds = ?, 
                last_stat_update_at = ?, last_played_at = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            $question->getQuestionText(),
            $question->getType(),
            $question->getCategoryId(),
            $question->getIsActive(),
            $question->getImageUrl(),
            $question->getHintText(),
            $question->getSourceReference(),
            $question->getTimesAsked(),
            $question->getCorrectAttemptsCount(),
            $question->getIncorrectAttemptsCount(),
            $question->getDifficultyId(),
            $question->getSkipCount(),
            $question->getTotalTimeSpentSeconds(),
            $question->getLastStatUpdateAt(),
            $question->getLastPlayedAt(),
            $question->getId(),
        ]);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Deletes a question from the database.
     *
     * @param Question $question The Question object to delete.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    public function delete(Question $question): bool {
        if ($question->getId() === null) {
            return false;
        }

        $sql = "DELETE FROM questions WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$question->getId()]);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Maps an associative array from the database to a Question object.
     *
     * @param array $questionData The question data from the database.
     * @return Question The mapped Question object.
     */
    /**-------------------------------------------------------------------------*/
    private function mapToQuestion(array $questionData): Question {
        $question = new Question(
            $questionData['question_text'],
            $questionData['type']
        );
        $question->setId((int)$questionData['id']);
        $question->setCategoryId($questionData['category_id']);
        $question->setCreatedAt($questionData['created_at']);
        $question->setUpdatedAt($questionData['updated_at']);
        $question->setIsActive((int)$questionData['is_active']);
        $question->setImageUrl($questionData['image_url']);
        $question->setHintText($questionData['hint_text']);
        $question->setSourceReference($questionData['source_reference']);
        $question->setTimesAsked((int)$questionData['times_asked']);
        $question->setCorrectAttemptsCount((int)$questionData['correct_attempts_count']);
        $question->setIncorrectAttemptsCount((int)$questionData['incorrect_attempts_count']);
        $question->setDifficultyId($questionData['difficulty_id']);
        $question->setSkipCount((int)$questionData['skip_count']);
        $question->setTotalTimeSpentSeconds((int)$questionData['total_time_spent_seconds']);
        $question->setLastStatUpdateAt($questionData['last_stat_update_at']);
        $question->setLastPlayedAt($questionData['last_played_at']);
        return $question;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Pull Questions to create quiz
     * 
     * @return array{QuestionModel} $questions
     */
    /**-------------------------------------------------------------------------*/
    public function pullQuestions(string $quiz_id_map): ?array {
        /**
         * Parse ID Map
         */
        $id_array       = json_decode($quiz_id_map);
        $placeholders   = implode(", ", array_fill(0, count($id_array), "?"));
        /**
         * Form SQL
         * Prepare
         * Bind Params
         */
        $sql    = "SELECT * FROM questions WHERE id IN (" . $placeholders . ")";
        $stmt   = $this->db->prepare($sql);
        
        // Bind ID Parameters

        /**
         * Execute and Process Records
         */
        $stmt->execute($id_array);

        $records = [];
        while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
            /**
             * Map record properties to a new Question model
             * Push Question model to $records array
             */
            $records[] = $this->mapToQuestion([
                "id"            => $record["id"],
                "question_text" => $record["question_text"],
                "type"          => $record["type"],
                "category_id"   => $record["category_id"],
                "created_at"    => $record["created_at"],
                "updated_at"    => $record["updated_at"],
                "is_active"     => $record["is_active"],
                "image_url"     => $record["image_url"],
                "hint_text" => $record["hint_text"],
                "source_reference" => $record["source_reference"],
                "times_asked"   => $record["times_asked"],
                "correct_attempts_count"    => $record["correct_attempts_count"],
                "incorrect_attempts_count"  => $record["incorrect_attempts_count"],
                "difficulty_id"     => $record["difficulty_id"],
                "skip_count"        => $record["skip_count"],
                "total_time_spent_seconds" => $record["total_time_spent_seconds"],
                "last_stat_update_at"       => $record["last_stat_update_at"],
                "last_played_at"    => $record["last_played_at"]
            ]);
        }
        
        /**
         * Validate $records and return
         */
        if(!empty($records)){
            return $records;
        }


        /**
         * Return Default
         */
        return NULL;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Pull Question Ids and return JSON map
     * 
     * @return string JSON Map of Question Ids for creating a quiz
     */
    /**-------------------------------------------------------------------------*/
    public function pullQuestionIdMap(int $category_id, int $difficulty_id, int $limit=10): ?string{
        /**
         * Form SQL
         * Prepare
         * Bind Params
         */
        $sql    = "SELECT id FROM questions WHERE category_id = :category_id AND difficulty_id = :difficulty_id ORDER BY RAND() LIMIT " . $limit;
        $stmt   = $this->db->prepare($sql);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->bindParam(":difficulty_id", $difficulty_id, PDO::PARAM_INT);

        /**
         * Execute and Process Records
         */
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if(is_array($records)){
            /**
             * Return JSON string
             */
            return json_encode($records);
        }

        /**
         * Return Default
         */
        return NULL;
    }
}