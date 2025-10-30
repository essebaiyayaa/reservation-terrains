<?php

/**
 * Class User
 * 
 * Model representing a user (Utilisateur) in the Football Pitch Booking
 * Management System.
 * 
 * Handles all database operations related to users including:
 * - User registration and authentication
 * - Email verification
 * - Role management (client, admin, gerant_terrain)
 * - User profile management
 * 
 * @package Models
 * @author  Aya Essebaiy
 * @version 1.0
 */
class User extends BaseModel
{
    /**
     * User properties
     */
    private ?int $id_utilisateur = null;
    private ?string $nom = null;
    private ?string $prenom = null;
    private ?string $email = null;
    private ?string $telephone = null;
    private ?string $mot_de_passe = null;
    private string $role = 'client';
    private ?string $verification_token = null;
    private int $email_verified = 0;

    /**
     * Initializes the User model by setting up the table name,
     * schema, and ensuring the table exists.
     * 
     * @return void
     */
    protected function init(): void
    {
        $this->table = 'Utilisateur';
        
        $this->schema = "
            CREATE TABLE IF NOT EXISTS {$this->table} (
                id_utilisateur INT PRIMARY KEY AUTO_INCREMENT,
                nom VARCHAR(50) NOT NULL,
                prenom VARCHAR(50) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                telephone VARCHAR(20),
                mot_de_passe VARCHAR(255) NOT NULL,
                role ENUM('client', 'admin', 'gerant_terrain') DEFAULT 'client',
                verification_token VARCHAR(64) NULL,
                email_verified TINYINT(1) DEFAULT 0,
                date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                date_derniere_connexion TIMESTAMP NULL,
                INDEX idx_email (email),
                INDEX idx_verification_token (verification_token),
                INDEX idx_email_verified (email_verified)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        // Execute schema to create table if not exists
        $this->db->query($this->schema);
        $this->db->execute();
    }

    /**
     * Retrieves all users from the database.
     * 
     * @return array List of all users.
     */
    public function getAll(): array
    {
        $query = "SELECT id_utilisateur, nom, prenom, email, telephone, role, 
                         email_verified, date_creation, date_derniere_connexion 
                  FROM {$this->table} 
                  ORDER BY date_creation DESC";
        
        $this->db->query($query);
        return $this->db->results();
    }

    /**
     * Retrieves a single user by ID.
     * 
     * @param string $id The user ID.
     * @return array|null User data or null if not found.
     */
    public function getById(string $id): ?array
    {
        $query = "SELECT id_utilisateur, nom, prenom, email, telephone, role, 
                         email_verified, date_creation, date_derniere_connexion 
                  FROM {$this->table} 
                  WHERE id_utilisateur = :id";
        
        $this->db->query($query);
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        
        $result = $this->db->result();
        return $result ? (array)$result : null;
    }

    /**
 * Adds a new user to the database.
 * 
 * @param array $data Associative array containing user data:
 *  - 'nom', 'prenom', 'email', 'telephone', 'mot_de_passe', 'role', 'verification_token'
 * @return bool True on success, false on failure.
 */
public
 function add(array $data): bool
{
    $query = "INSERT INTO {$this->table} 
              (nom, prenom, email, telephone, mot_de_passe, role, verification_token) 
              VALUES (:nom, :prenom, :email, :telephone, :mot_de_passe, :role, :verification_token)";
    
    $this->db->query($query);

    // Hash the password before storing
    if (isset($data['mot_de_passe'])) {
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);
    }

    $this->db->bindValue(':nom', $data['nom'], PDO::PARAM_STR);
    $this->db->bindValue(':prenom', $data['prenom'], PDO::PARAM_STR);
    $this->db->bindValue(':email', $data['email'], PDO::PARAM_STR);
    $this->db->bindValue(':telephone', $data['telephone'] ?? null, PDO::PARAM_STR);
    $this->db->bindValue(':mot_de_passe', $data['mot_de_passe'], PDO::PARAM_STR);
    $this->db->bindValue(':role', $data['role'] ?? 'client', PDO::PARAM_STR);
    $this->db->bindValue(':verification_token', $data['verification_token'] ?? null, PDO::PARAM_STR);

    return $this->db->execute();
}

    /**
     * Updates an existing user.
     * 
     * @param string $id The user ID.
     * @param array $data Associative array of fields to update.
     * @return bool True on success, false on failure.
     */
    public function update(string $id, array $data): bool
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_utilisateur = :id";
        
        $this->db->query($query);
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        
        foreach ($data as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $this->db->bindValue(":{$key}", $value, $type);
        }
        
        return $this->db->execute();
    }

    /**
     * Deletes a user from the database.
     * 
     * @param string $id The user ID.
     * @return bool True on success, false on failure.
     */
    public function delete(string $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id_utilisateur = :id";
        
        $this->db->query($query);
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    /**
     * Setters for user properties
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function setMotDePasse(string $mot_de_passe): void
    {
        $this->mot_de_passe = password_hash($mot_de_passe, PASSWORD_BCRYPT);
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setVerificationToken(?string $token): void
    {
        $this->verification_token = $token;
    }

    /**
     * Finds a user by email address.
     * 
     * @param string $email The email address.
     * @return array|null User data or null if not found.
     */
    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        
        $this->db->query($query);
        $this->db->bindValue(':email', $email, PDO::PARAM_STR);
        
        $result = $this->db->result();
        return $result ? (array)$result : null;
    }

    /**
     * Finds a user by verification token.
     * 
     * @param string $token The verification token.
     * @return array|null User data or null if not found.
     */
    public function findByVerificationToken(string $token): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE verification_token = :token LIMIT 1";
        
        $this->db->query($query);
        $this->db->bindValue(':token', $token, PDO::PARAM_STR);
        
        $result = $this->db->result();
        return $result ? (array)$result : null;
    }

    /**
     * Verifies a user's email address.
     * 
     * @param string $token The verification token.
     * @return bool True on success, false on failure.
     */
    public function verifyEmail(string $token): bool
    {
        $query = "UPDATE {$this->table} 
                  SET email_verified = 1, verification_token = NULL 
                  WHERE verification_token = :token";
        
        $this->db->query($query);
        $this->db->bindValue(':token', $token, PDO::PARAM_STR);
        
        return $this->db->execute();
    }

    /**
     * Updates the last login timestamp.
     * 
     * @param int $userId The user ID.
     * @return bool True on success, false on failure.
     */
    public function updateLastLogin(int $userId): bool
    {
        $query = "UPDATE {$this->table} 
                  SET date_derniere_connexion = CURRENT_TIMESTAMP 
                  WHERE id_utilisateur = :id";
        
        $this->db->query($query);
        $this->db->bindValue(':id', $userId, PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    /**
     * Checks if an email already exists.
     * 
     * @param string $email The email to check.
     * @param int|null $excludeId Optional user ID to exclude from check.
     * @return bool True if email exists, false otherwise.
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        
        if ($excludeId) {
            $query .= " AND id_utilisateur != :id";
        }
        
        $this->db->query($query);
        $this->db->bindValue(':email', $email, PDO::PARAM_STR);
        
        if ($excludeId) {
            $this->db->bindValue(':id', $excludeId, PDO::PARAM_INT);
        }
        
        $result = $this->db->result();
        return $result && $result->count > 0;
    }

    /**
     * Generates a unique verification token.
     * 
     * @return string The generated token.
     */
    public function generateVerificationToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}

?>