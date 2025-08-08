<?php

/**
 * Question Model Class
 *
 * Represents a question entity with properties corresponding to the database table columns.
 * It is a data transfer object (DTO) that holds question data.
 */
class Question {
    /**
     * The unique identifier for the question.
     * @var int|null
     */
    private ?int $id = null;

    /**
     * The text of the question.
     * @var string
     */
    private string $questionText;

    /**
     * The type of question (e.g., 'multiple_choice').
     * @var string
     */
    private string $type;

    /**
     * The category ID for the question.
     * @var int|null
     */
    private ?int $categoryId = null;

    /**
     * The timestamp when the question was created.
     * @var string|null
     */
    private ?string $createdAt = null;

    /**
     * The timestamp when the question was last updated.
     * @var string|null
     */
    private ?string $updatedAt = null;

    /**
     * Whether the question is active.
     * @var int
     */
    private int $isActive = 1;

    /**
     * URL for an associated image.
     * @var string|null
     */
    private ?string $imageUrl = null;

    /**
     * Optional hint text for the question.
     * @var string|null
     */
    private ?string $hintText = null;

    /**
     * Optional source reference for the question.
     * @var string|null
     */
    private ?string $sourceReference = null;

    /**
     * The number of times the question has been asked.
     * @var int
     */
    private int $timesAsked = 0;

    /**
     * The count of correct attempts.
     * @var int
     */
    private int $correctAttemptsCount = 0;

    /**
     * The count of incorrect attempts.
     * @var int
     */
    private int $incorrectAttemptsCount = 0;

    /**
     * The difficulty ID for the question.
     * @var int|null
     */
    private ?int $difficultyId = null;

    /**
     * The count of times the question was skipped.
     * @var int
     */
    private int $skipCount = 0;

    /**
     * The total time spent on the question, in seconds.
     * @var int
     */
    private int $totalTimeSpentSeconds = 0;

    /**
     * The timestamp of the last time statistics were updated.
     * @var string|null
     */
    private ?string $lastStatUpdateAt = null;

    /**
     * The timestamp of the last time the question was played.
     * @var string|null
     */
    private ?string $lastPlayedAt = null;

    /**
     * Question constructor.
     *
     * @param string $questionText The text of the question.
     * @param string $type The type of question (e.g., 'multiple_choice').
     */
    public function __construct(string $questionText, string $type) {
        $this->questionText = $questionText;
        $this->type = $type;
    }

    // --- Getters and Setters for all properties ---

    /** @return int|null */
    public function getId(): ?int { return $this->id; }

    /** @param int $id */
    public function setId(int $id): void { $this->id = $id; }

    /** @return string */
    public function getQuestionText(): string { return $this->questionText; }

    /** @param string $questionText */
    public function setQuestionText(string $questionText): void { $this->questionText = $questionText; }

    /** @return string */
    public function getType(): string { return $this->type; }

    /** @param string $type */
    public function setType(string $type): void { $this->type = $type; }

    /** @return int|null */
    public function getCategoryId(): ?int { return $this->categoryId; }

    /** @param int|null $categoryId */
    public function setCategoryId(?int $categoryId): void { $this->categoryId = $categoryId; }

    /** @return string|null */
    public function getCreatedAt(): ?string { return $this->createdAt; }

    /** @param string|null $createdAt */
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }

    /** @return string|null */
    public function getUpdatedAt(): ?string { return $this->updatedAt; }

    /** @param string|null $updatedAt */
    public function setUpdatedAt(?string $updatedAt): void { $this->updatedAt = $updatedAt; }

    /** @return int */
    public function getIsActive(): int { return $this->isActive; }

    /** @param int $isActive */
    public function setIsActive(int $isActive): void { $this->isActive = $isActive; }

    /** @return string|null */
    public function getImageUrl(): ?string { return $this->imageUrl; }

    /** @param string|null $imageUrl */
    public function setImageUrl(?string $imageUrl): void { $this->imageUrl = $imageUrl; }

    /** @return string|null */
    public function getHintText(): ?string { return $this->hintText; }

    /** @param string|null $hintText */
    public function setHintText(?string $hintText): void { $this->hintText = $hintText; }

    /** @return string|null */
    public function getSourceReference(): ?string { return $this->sourceReference; }

    /** @param string|null $sourceReference */
    public function setSourceReference(?string $sourceReference): void { $this->sourceReference = $sourceReference; }

    /** @return int */
    public function getTimesAsked(): int { return $this->timesAsked; }

    /** @param int $timesAsked */
    public function setTimesAsked(int $timesAsked): void { $this->timesAsked = $timesAsked; }

    /** @return int */
    public function getCorrectAttemptsCount(): int { return $this->correctAttemptsCount; }

    /** @param int $correctAttemptsCount */
    public function setCorrectAttemptsCount(int $correctAttemptsCount): void { $this->correctAttemptsCount = $correctAttemptsCount; }

    /** @return int */
    public function getIncorrectAttemptsCount(): int { return $this->incorrectAttemptsCount; }

    /** @param int $incorrectAttemptsCount */
    public function setIncorrectAttemptsCount(int $incorrectAttemptsCount): void { $this->incorrectAttemptsCount = $incorrectAttemptsCount; }

    /** @return int|null */
    public function getDifficultyId(): ?int { return $this->difficultyId; }

    /** @param int|null $difficultyId */
    public function setDifficultyId(?int $difficultyId): void { $this->difficultyId = $difficultyId; }

    /** @return int */
    public function getSkipCount(): int { return $this->skipCount; }

    /** @param int $skipCount */
    public function setSkipCount(int $skipCount): void { $this->skipCount = $skipCount; }

    /** @return int */
    public function getTotalTimeSpentSeconds(): int { return $this->totalTimeSpentSeconds; }

    /** @param int $totalTimeSpentSeconds */
    public function setTotalTimeSpentSeconds(int $totalTimeSpentSeconds): void { $this->totalTimeSpentSeconds = $totalTimeSpentSeconds; }

    /** @return string|null */
    public function getLastStatUpdateAt(): ?string { return $this->lastStatUpdateAt; }

    /** @param string|null $lastStatUpdateAt */
    public function setLastStatUpdateAt(?string $lastStatUpdateAt): void { $this->lastStatUpdateAt = $lastStatUpdateAt; }

    /** @return string|null */
    public function getLastPlayedAt(): ?string { return $this->lastPlayedAt; }

    /** @param string|null $lastPlayedAt */
    public function setLastPlayedAt(?string $lastPlayedAt): void { $this->lastPlayedAt = $lastPlayedAt; }
}