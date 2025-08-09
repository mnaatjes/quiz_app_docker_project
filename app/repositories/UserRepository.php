<?php

require_once("Database.php");

/**-------------------------------------------------------------------------*/
/**
 * UserRepository Class
 *
 * Handles the persistence and retrieval of User objects from the database.
 * This class implements the Repository Pattern, abstracting the data storage.
 */
/**-------------------------------------------------------------------------*/
class UserRepository {
    /**
     * @var PDO The PDO database connection object.
     */
    private PDO $db;
    /**-------------------------------------------------------------------------*/
    /**
     * UserRepository constructor.
     *
     * Initializes the repository by getting the shared database connection.
     */
    /**-------------------------------------------------------------------------*/
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**-------------------------------------------------------------------------*/
    /**
     * Finds a user by their ID.
     *
     * @param int $id The user ID.
     * @return User|null Returns a User object if found, otherwise null.
     */
    /**-------------------------------------------------------------------------*/
    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $userData = $stmt->fetch();

        if (!$userData) {
            return null;
        }

        return $this->mapToUser($userData);
    }
    /**-------------------------------------------------------------------------*/
    /**
     * Finds a user by their email address.
     *
     * @param string $email The user's email.
     * @return User|null Returns a User object if found, otherwise null.
     */
    /**-------------------------------------------------------------------------*/
    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch();

        if (!$userData) {
            return null;
        }

        return $this->mapToUser($userData);
    }
    
    /**-------------------------------------------------------------------------*/
    /**
     * Finds a user by their username.
     *
     * @param string $username The user's username.
     * @return User|null Returns a User object if found, otherwise null.
     */
    /**-------------------------------------------------------------------------*/
    public function findByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $userData = $stmt->fetch();

        if (!$userData) {
            return null;
        }

        return $this->mapToUser($userData);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Saves a user to the database.
     *
     * This method handles both new user creation (insert) and updating existing users.
     *
     * @param User $user The User object to save.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    public function save(User $user): User|bool {
        if ($user->getId() === null) {
            return $this->create($user);
        }
        return $this->update($user);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Inserts a new user into the database.
     *
     * @param User $user The User object to insert.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    private function create(User $user): ?User {
        $sql = "INSERT INTO users (username, email, password_hash, first_name, last_name, is_active) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $user->getUsername(),
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getIsActive(),
        ]);

        if ($result) {
            /**
             * Return mapped user object
             */
            $new_id = (int)$this->db->lastInsertId();
            return $this->findById($new_id);
        }

        /**
         * Return Default / Failure
         */
        return NULL;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Updates an existing user in the database.
     *
     * @param User $user The User object to update.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    private function update(User $user): bool {
        $sql = "UPDATE users SET username = ?, email = ?, password_hash = ?, 
                first_name = ?, last_name = ?, is_active = ?, last_login_at = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $user->getUsername(),
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getIsActive(),
            $user->getLastLoginAt(),
            $user->getId(),
        ]);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Deletes a user from the database.
     *
     * @param User $user The User object to delete.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    public function delete(User $user): bool {
        if ($user->getId() === null) {
            return false;
        }

        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user->getId()]);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Handles the form submission for creating a new user.
     * e.g., accessed via POST to /user/create
     */
    /**-------------------------------------------------------------------------*/
    public function createUser(array $user_data) {
        /**
         * Create New User Object:
         * - Create new instance
         * - Append remaining values
         */
        $user = new User(
            $user_data["username"],
            $user_data["email"],
            $user_data["password_hash"],
        );

        // Append remaining data
        $user->setFirstName($user_data["firstname"]);
        $user->setLastName($user_data["lastname"]);
        
        /**
         * Save new User Object to DB:
         * - UserRepo maps remaining / missing values to User
         * - Returns last insert id on success
         */
        $result = $this->save($user);

        /**
         * TODO: Validate
         */
        return $result;
    }
    
    /**-------------------------------------------------------------------------*/
    /**
     * Maps an associative array from the database to a User object.
     *
     * @param array $userData The user data from the database.
     * @return User The mapped User object.
     */
    /**-------------------------------------------------------------------------*/
    private function mapToUser(array $userData): User {
        $user = new User(
            $userData['username'],
            $userData['email'],
            $userData['password_hash']
        );
        $user->setId((int)$userData['id']);
        $user->setFirstName($userData['first_name']);
        $user->setLastName($userData['last_name']);
        $user->setCreatedAt($userData['created_at']);
        $user->setUpdatedAt($userData['updated_at']);
        $user->setIsActive((int)$userData['is_active']);
        $user->setLastLoginAt($userData['last_login_at']);
        return $user;
    }
}