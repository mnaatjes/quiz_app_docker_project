<?php

class AnswerModel
{
    private ?int $id = null;
    private ?int $questionId = null;
    private bool $isCorrect = false;
    private ?string $answerText = null;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;
    private ?string $explanationText = null;
    private int $timesShown = 0;
    private int $selectionCount = 0;

    /**
     * Constructor
     */
    public function __construct(){
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }

    public function setQuestionId(int $questionId): self
    {
        $this->questionId = $questionId;
        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }

    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    public function setAnswerText(string $answerText): self
    {
        $this->answerText = $answerText;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getExplanationText(): ?string
    {
        return $this->explanationText;
    }

    public function setExplanationText(?string $explanationText): self
    {
        $this->explanationText = $explanationText;
        return $this;
    }

    public function getTimesShown(): int
    {
        return $this->timesShown;
    }

    public function setTimesShown(int $timesShown): self
    {
        $this->timesShown = $timesShown;
        return $this;
    }

    public function getSelectionCount(): int
    {
        return $this->selectionCount;
    }

    public function setSelectionCount(int $selectionCount): self
    {
        $this->selectionCount = $selectionCount;
        return $this;
    }
}