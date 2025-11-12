<?php

/**
 * Class NewsletterModel
 * 
 * Gère les newsletters dans la base de données
 * 
 * @package Models
 * @author   Jihane
 * @version 1.0
 */
class NewsletterModel extends BaseModel
{
    /**
     * Constructeur - Initialise la connexion DB
     */
    public function __construct()
    {
        $this->db = new PDODatabase();
        $this->init();
    }

    protected function init(): void
    {
        $this->table = 'newsletter';
        
        // Créer la table si elle n'existe pas
        $this->schema = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id_newsletter INT AUTO_INCREMENT PRIMARY KEY,
            id_expediteur INT NOT NULL,
            sujet VARCHAR(255) NOT NULL,
            contenu TEXT NOT NULL,
            date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
            nombre_destinataires INT DEFAULT 0,
            piece_jointe VARCHAR(255) DEFAULT NULL,
            statut ENUM('en_cours', 'envoye', 'echoue') DEFAULT 'en_cours',
            FOREIGN KEY (id_expediteur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->db->query($this->schema);
        $this->db->execute();
    }

    /**
     * Get all newsletters with sender info
     * 
     * @return array List of newsletters
     */
    public function getAll(): array
    {
        $this->db->query("
            SELECT n.*, 
                   u.prenom, 
                   u.nom, 
                   u.role as role_expediteur
            FROM {$this->table} n
            LEFT JOIN utilisateur u ON n.id_expediteur = u.id_utilisateur
            ORDER BY n.date_envoi DESC
        ");
        return $this->db->results();
    }

    /**
     * Get newsletter by ID
     * 
     * @param string $id Newsletter ID
     * @return object|null Newsletter object or null
     */
    public function getById(string $id): ?object
    {
        $this->db->query("
            SELECT n.*, 
                   u.prenom, 
                   u.nom, 
                   u.role as role_expediteur
            FROM {$this->table} n
            LEFT JOIN utilisateur u ON n.id_expediteur = u.id_utilisateur
            WHERE n.id_newsletter = :id
        ");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        return $this->db->result();
    }

    /**
     * Add new newsletter
     * 
     * @param array $data Newsletter data
     * @return bool Success status
     */
    public function add(array $data): bool
    {
        $this->db->query("
            INSERT INTO {$this->table} 
            (id_expediteur, sujet, contenu, nombre_destinataires, piece_jointe, statut) 
            VALUES 
            (:id_expediteur, :sujet, :contenu, :nombre_destinataires, :piece_jointe, :statut)
        ");
        
        $this->db->bindValue(':id_expediteur', $data['id_expediteur'], PDO::PARAM_INT);
        $this->db->bindValue(':sujet', $data['sujet'], PDO::PARAM_STR);
        $this->db->bindValue(':contenu', $data['contenu'], PDO::PARAM_STR);
        $this->db->bindValue(':nombre_destinataires', $data['nombre_destinataires'], PDO::PARAM_INT);
        $this->db->bindValue(':piece_jointe', $data['piece_jointe'] ?? null, PDO::PARAM_STR);
        $this->db->bindValue(':statut', $data['statut'], PDO::PARAM_STR);
        
        return $this->db->execute();
    }

    /**
     * Update newsletter
     * 
     * @param string $id Newsletter ID
     * @param array $data Data to update
     * @return bool Success status
     */
    public function update(string $id, array $data): bool
    {
        $this->db->query("
            UPDATE {$this->table} 
            SET statut = :statut,
                nombre_destinataires = :nombre_destinataires
            WHERE id_newsletter = :id
        ");
        
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        $this->db->bindValue(':statut', $data['statut'], PDO::PARAM_STR);
        $this->db->bindValue(':nombre_destinataires', $data['nombre_destinataires'], PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    /**
     * Delete newsletter
     * 
     * @param string $id Newsletter ID
     * @return bool Success status
     */
    public function delete(string $id): bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id_newsletter = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Get all user emails
     * 
     * @return array List of users with emails
     */
    public function getAllUserEmails(): array
    {
        $this->db->query("
            SELECT email, prenom, nom 
            FROM utilisateur 
            
            ORDER BY email
        ");
        return $this->db->results();
    }

    /**
     * Get total newsletter count
     * 
     * @return int Total count
     */
    public function getTotalCount(): int
    {
        $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $result = $this->db->result();
        return $result ? (int)$result->total : 0;
    }
}