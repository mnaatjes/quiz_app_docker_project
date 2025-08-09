<?php
/**-------------------------------------------------------------------------*/
/**
 * 
 */
/**-------------------------------------------------------------------------*/
class AnswerRepository
{
    private PDO $db;

    /**-------------------------------------------------------------------------*/
    /**
     * 
     */
    /**-------------------------------------------------------------------------*/
    public function __construct(){
        /**
         * Create / Find DB Instance
         */
        $this->db = Database::getInstance()->getConnection();
    }

    /**-------------------------------------------------------------------------*/ 
    /**
     * 
     */
    /**-------------------------------------------------------------------------*/ 
    public function findById(int $id): ?AnswerModel
    {
        $sql = "SELECT * FROM answers WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return $this->mapRowToModel($row);
    }
    
    /**-------------------------------------------------------------------------*/
    /**
     * 
     */
    /**-------------------------------------------------------------------------*/
    public function findByQuestionId(int $questionId): array
    {
        $sql = "SELECT * FROM answers WHERE question_id = :question_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->execute();
        
        $answers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $answers[] = $this->mapRowToModel($row);
        }

        return $answers;
    }
    
    /**-------------------------------------------------------------------------*/
    /**
     * Maps a database row to an AnswerModel object.
     */
    /**-------------------------------------------------------------------------*/
    private function mapRowToModel(array $row): AnswerModel{
        $model = new AnswerModel();
        $model->setId($row['id']);
        $model->setQuestionId($row['question_id']);
        $model->setIsCorrect($row['is_correct'] === 1); // tinyint(1) is a boolean
        $model->setAnswerText($row['answer_text']);
        $model->setExplanationText($row['explanation_text']);
        $model->setTimesShown($row['times_shown']);
        $model->setSelectionCount($row['selection_count']);
        
        // Handle timestamps
        if ($row['created_at']) {
            $model->setCreatedAt(new \DateTime($row['created_at']));
        }
        if ($row['updated_at']) {
            $model->setUpdatedAt(new \DateTime($row['updated_at']));
        }

        return $model;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Pull Answers
     */
    /**-------------------------------------------------------------------------*/
    public function pullAnswers(array $question_id_array){
        /**
         * Form SQL
         * Prepare
         * Bind Params
         */
        $placeholders = implode(", ", array_fill(0, count($question_id_array), "?"));
        $sql    = "SELECT * FROM answers WHERE question_id IN (" . $placeholders . ")";
        $stmt   = $this->db->prepare($sql);

        /**
         * Execute and Process Records
         */
        $stmt->execute($question_id_array);
        
        $records = [];
        while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
            $records[] = $this->mapRowToModel([
                "id"            => $record["id"],
                "question_id"   => $record["question_id"],
                "is_correct"    => $record["is_correct"],
                "answer_text"   => $record["answer_text"],
                "created_at"    => $record["created_at"],
                "updated_at"    => $record["updated_at"],
                "explanation_text"  => $record["explanation_text"],
                "times_shown"       => $record["times_shown"],
                "selection_count"   => $record["selection_count"],
            ]);
        }
        
        /**
         * Validate and return
         */
        if(!empty($records)){
            return $records;
        }

        /**
         * Return Default
         */
        return NULL;
    }
}