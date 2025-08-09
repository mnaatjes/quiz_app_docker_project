<?php

/**
 * User Model Class
 *
 * Represents a user entity with properties corresponding to the database table columns.
 * It is a data transfer object (DTO) with no knowledge of data persistence.
 */
class User {
    /**
     * The unique identifier for the user.
     * @var int|null
     */
    private ?int $id = null;

    /**
     * The user's unique username.
     * @var string
     */
    private string $username;

    /**
     * The user's unique email address.
     * @var string
     */
    private string $email;

    /**
     * The user's hashed password.
     * @var string
     */
    private string $passwordHash;

    /**
     * The user's first name.
     * @var string|null
     */
    private ?string $firstName = null;

    /**
     * The user's last name.
     * @var string|null
     */
    private ?string $lastName = null;

    /**
     * The timestamp when the user was created.
     * @var string|null
     */
    private ?string $createdAt = null;

    /**
     * The timestamp when the user was last updated.
     * @var string|null
     */
    private ?string $updatedAt = null;

    /**
     * Whether the user account is active.
     * @var int
     */
    private int $isActive = 1;

    /**
     * The timestamp of the user's last login.
     * @var string|null
     */
    private ?string $lastLoginAt = null;

    /**
     * User constructor.
     *
     * @param string $username The user's username.
     * @param string $email The user's email.
     * @param string $passwordHash The user's hashed password.
     */
    public function __construct(string $username, string $email, string $passwordHash) {
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
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
    public function getUsername(): string { return $this->username; }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void { $this->username = $username; }

    /**
     * @return string
     */
    public function getEmail(): string { return $this->email; }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void { $this->email = $email; }

    /**
     * @return string
     */
    public function getPasswordHash(): string { return $this->passwordHash; }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void { $this->passwordHash = $passwordHash; }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string { return $this->firstName; }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void { $this->firstName = $firstName; }

    /**
     * @return string|null
     */
    public function getLastName(): ?string { return $this->lastName; }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void { $this->lastName = $lastName; }

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

    /**
     * @return int
     */
    public function getIsActive(): int { return $this->isActive; }

    /**
     * @param int $isActive
     */
    public function setIsActive(int $isActive): void { $this->isActive = $isActive; }

    /**
     * @return string|null
     */
    public function getLastLoginAt(): ?string { return $this->lastLoginAt; }

    /**
     * @param string|null $lastLoginAt
     */
    public function setLastLoginAt(?string $lastLoginAt): void { $this->lastLoginAt = $lastLoginAt; }
}