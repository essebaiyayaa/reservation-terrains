<?php

/**
 * Class UserModel
 * 
 * Handles all database operations for Utilisateur table
 * 
 * @package Models
 * @author  Amos 
 * @version 1.1
 */
class UserModel extends BaseModel
{
    public function __construct()
    {
        $this->db = new PDODatabase();
        $this->init();
    }

    public function init(): void
    {
        $this->table = 'Utilisateur';
    }

    /**
     * Get all users
     */
    public function getAll(): array
    {
        $this->db->query("SELECT * FROM Utilisateur ORDER BY prenom, nom");
        return $this->db->results();
    }

    /**
     * Get user by ID (email in this case)
     */
    public function getById(string $id): ?object
    {
        $this->db->query("SELECT * FROM Utilisateur WHERE email = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_STR);
        $this->db->execute();

        $user = $this->db->result();
        return $user ?: null;
    }

    /**
     * Get user by numeric ID
     */
    public function getByUserId(int $userId): ?object
    {
        $this->db->query("SELECT * FROM Utilisateur WHERE id_utilisateur = :id");
        $this->db->bindValue(':id', $userId, PDO::PARAM_INT);
        $this->db->execute();

        $user = $this->db->result();
        return $user ?: null;
    }

    /**
     * Get all users by role
     * 
     * @param string $role The role to filter by (e.g., 'gerant_terrain', 'admin', 'client')
     * @return array List of users with the specified role
     */
    public function getAllByRole(string $role): array
    {
        $this->db->query("
            SELECT id_utilisateur, nom, prenom, email, telephone, role 
            FROM Utilisateur 
            WHERE role = :role 
            ORDER BY prenom, nom
        ");
        
        $this->db->bindValue(':role', $role, PDO::PARAM_STR);
        return $this->db->results();
    }

    /**
     * Add new user
     */
    public function add(array $data): bool
    {
        $this->db->query("
            INSERT INTO Utilisateur 
            (nom, prenom, email, telephone, mot_de_passe, verification_token, token_expiry, role) 
            VALUES 
            (:nom, :prenom, :email, :telephone, :mot_de_passe, :verification_token, :token_expiry, :role)
        ");
        
        $this->db->bindValue(':nom', $data['nom'], PDO::PARAM_STR);
        $this->db->bindValue(':prenom', $data['prenom'], PDO::PARAM_STR);
        $this->db->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $this->db->bindValue(':telephone', $data['telephone'], PDO::PARAM_STR);
        $this->db->bindValue(':mot_de_passe', $data['mot_de_passe'], PDO::PARAM_STR);
        $this->db->bindValue(':verification_token', $data['verification_token'], PDO::PARAM_STR);
        $this->db->bindValue(':token_expiry', $data['token_expiry'], PDO::PARAM_STR);
        $this->db->bindValue(':role', $data['role'] ?? 'client', PDO::PARAM_STR);
            
        return $this->db->execute();
    }

    /**
     * Update user
     */
    public function update(string $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE Utilisateur SET " . implode(", ", $fields) . " WHERE id_utilisateur = :id";

        $this->db->query($sql);

        foreach ($params as $param => $value) {
            $type = ($param === ':id') ? PDO::PARAM_INT : PDO::PARAM_STR;
            $this->db->bindValue($param, $value, $type);
        }

        return $this->db->execute();
    }

    /**
     * Delete user
     */
    public function delete(string $id): bool
    {
        $this->db->query("DELETE FROM Utilisateur WHERE id_utilisateur = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Verify user account
     */
    public function verifyAccount(string $email, string $token): bool
    {
        // Check if token is valid and not expired
        $this->db->query("
            SELECT id_utilisateur 
            FROM Utilisateur 
            WHERE email = :email 
            AND verification_token = :token 
            AND token_expiry > NOW()
            AND compte_verifie = 0
        ");

        $this->db->bindValue(':email', $email, PDO::PARAM_STR);
        $this->db->bindValue(':token', $token, PDO::PARAM_STR);

        $user = $this->db->result();

        if (!$user) {
            return false;
        }

        // Update user as verified
        $this->db->query("
            UPDATE Utilisateur 
            SET compte_verifie = 1, verification_token = NULL, token_expiry = NULL 
            WHERE id_utilisateur = :id
        ");

        $this->db->bindValue(':id', $user->id_utilisateur, PDO::PARAM_INT);

        return $this->db->execute();
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        $this->db->query("SELECT COUNT(*) as count FROM Utilisateur WHERE email = :email");
        $this->db->bindValue(':email', $email, PDO::PARAM_STR);
        
        $result = $this->db->result();
        return $result && $result->count > 0;
    }

    /**
     * Update user role
     */
    public function updateRole(int $userId, string $role): bool
    {
        $this->db->query("UPDATE Utilisateur SET role = :role WHERE id_utilisateur = :id");
        $this->db->bindValue(':role', $role, PDO::PARAM_STR);
        $this->db->bindValue(':id', $userId, PDO::PARAM_INT);
        
        return $this->db->execute();
    }
}