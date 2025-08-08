<?php
/**-------------------------------------------------------------------------*/
/**
 * Quiz Model Class
 *
 * Represents a quiz entity with properties corresponding to the database table columns.
 * It is a data transfer object (DTO) that holds quiz data.
 */
/**-------------------------------------------------------------------------*/
class Quiz {

    /**
     * The unique identifier for the quiz.
     * @var int|null
     */
    private ?int $id = null;

    /**
     * A map of quiz IDs, stored as a JSON string in the database.
     * @var string
     */
    private string $quizIdMap;

    /**
     * A description of the quiz.
     * @var string|null
     */
    private ?string $description = null;

    /**
     * The category ID for the quiz.
     * @var int|null
     */
    private ?int $categoryId = null;

    /**
     * The difficulty ID for the quiz.
     * @var int|null
     */
    private ?int $difficultyId = null;

    /**
     * The timestamp when the quiz was created.
     * @var string|null
     */
    private ?string $createdAt = null;

    /**
     * The timestamp when the quiz was last updated.
     * @var string|null
     */
    private ?string $updatedAt = null;

    /**-------------------------------------------------------------------------*/
    /**
     * Quiz constructor.
     *
     * @param array 
     */
    /**-------------------------------------------------------------------------*/
    public function __construct(int $category_id, int $difficulty_id, string $quiz_id_map) {
        /**
         * Define Minimal Properties
         */
        $this->categoryId   = $category_id;
        $this->difficultyId = $difficulty_id;
        $this->setQuizIdMap($quiz_id_map);
    }

    // --- Getters and Setters ---

    /**
     * @return int|null
     */
    public function getId(): ?int { return $this->id; }

    /**
     * @param int $id
     */
    public function setId(int $id): void { $this->id = $id; }

    /**
     * @return string
     */
    public function getQuizIdMap(): string { return $this->quizIdMap; }

    /**
     * @param string $quizIdMap
     */
    public function setQuizIdMap(string $quizIdMap): void { $this->quizIdMap = $quizIdMap; }

    /**
     * @return string|null
     */
    public function getDescription(): ?string { return $this->description; }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void { $this->description = $description; }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int { return $this->categoryId; }

    /**
     * @param int|null $categoryId
     */
    public function setCategoryId(?int $categoryId): void { $this->categoryId = $categoryId; }

    /**
     * @return int|null
     */
    public function getDifficultyId(): ?int { return $this->difficultyId; }

    /**
     * @param int|null $difficultyId
     */
    public function setDifficultyId(?int $difficultyId): void { $this->difficultyId = $difficultyId; }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string { return $this->createdAt; }

    /**
     * @param string|null $createdAt
     */
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string { return $this->updatedAt; }

    /**
     * @param string|null $updatedAt
     */
    public function setUpdatedAt(?string $updatedAt): void { $this->updatedAt = $updatedAt; }

}