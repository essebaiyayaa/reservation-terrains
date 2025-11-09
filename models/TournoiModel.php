<?php

/**
 * Class TournoiModel
 * 
 * Handles all database operations for Tournoi table
 * 
 * @package Models
 * @extends BaseModel
 */
class TournoiModel extends BaseModel
{
    /**
     * Constructor - Initialize database connection and table
     */
    public function __construct()
    {
        $this->db = new PDODatabase();
        $this->init();
    }

    /**
     * Initialize model by setting table name
     * 
     * @return void
     */
    protected function init(): void
    {
        $this->table = 'Tournoi';
    }

    /**
     * Get all tournaments with complete details
     * 
     * @return array List of all tournaments with terrain and gerant info
     */
    protected function getAll(): array
    {
        $this->db->query("
            SELECT 
                t.*,
                ter.nom_terrain,
                ter.ville,
                ter.adresse,
                u.nom as nom_gerant,
                u.prenom as prenom_gerant,
                (SELECT COUNT(*) FROM Equipe WHERE id_tournoi = t.id_tournoi) as nombre_equipes_inscrites
            FROM {$this->table} t
            INNER JOIN terrain ter ON t.id_terrain = ter.id_terrain
            INNER JOIN utilisateur u ON t.id_gerant = u.id_utilisateur
            ORDER BY t.date_debut DESC
        ");
        
        return $this->db->results();
    }

    /**
     * Get tournament by ID
     * 
     * @param string $id Tournament ID
     * @return object|null Tournament object or null if not found
     */
    protected function getById(string $id): ?object
    {
        return $this->getTournoiById((int)$id);
    }

    /**
     * Add new tournament (Not implemented)
     * 
     * @param array $data Tournament data
     * @return bool
     */
    protected function add(array $data): bool
    {
        // TODO: Implement tournament creation
        return false;
    }

    /**
     * Update tournament (Not implemented)
     * 
     * @param string $id Tournament ID
     * @param array $data Updated tournament data
     * @return bool
     */
    protected function update(string $id, array $data): bool
    {
        // TODO: Implement tournament update
        return false;
    }

    /**
     * Delete tournament (Not implemented)
     * 
     * @param string $id Tournament ID
     * @return bool
     */
    protected function delete(string $id): bool
    {
        // TODO: Implement tournament deletion
        return false;
    }

    // ============================================================
    // TOURNAMENT-SPECIFIC METHODS
    // ============================================================

    /**
     * Get all available tournaments for clients
     * DEBUG VERSION - Returns all tournaments with complete details
     * 
     * @return array List of available tournaments
     */
    public function getTournoisDisponibles(): array
    {
        // TEST 1: Fetch ALL tournaments without filters
        $this->db->query("SELECT * FROM {$this->table}");
        $allTournois = $this->db->results();
        error_log("=== DEBUG TOURNOIS ===");
        error_log("ALL tournaments in database: " . count($allTournois));
        error_log(print_r($allTournois, true));
        
        // TEST 2: With JOIN but without WHERE filters
        $this->db->query("
            SELECT 
                t.*,
                ter.nom_terrain,
                ter.ville,
                ter.adresse,
                u.nom as nom_gerant,
                u.prenom as prenom_gerant,
                COALESCE((SELECT COUNT(*) FROM Equipe WHERE id_tournoi = t.id_tournoi), 0) as nombre_equipes_inscrites,
                (t.nombre_max_equipes - COALESCE((SELECT COUNT(*) FROM Equipe WHERE id_tournoi = t.id_tournoi), 0)) as places_disponibles
            FROM {$this->table} t
            LEFT JOIN terrain ter ON t.id_terrain = ter.id_terrain
            LEFT JOIN utilisateur u ON t.id_gerant = u.id_utilisateur
            ORDER BY t.date_debut ASC
        ");
        
        $result = $this->db->results();
        error_log("Tournaments WITH JOIN (no filters): " . count($result));
        error_log(print_r($result, true));
        error_log("======================");
        
        return $result;
    }

    /**
     * Get tournament by ID with complete details
     * 
     * @param int $id Tournament ID
     * @return object|null Tournament object or null if not found
     */
    public function getTournoiById(int $id): ?object
    {
        $this->db->query("
            SELECT 
                t.*,
                ter.nom_terrain,
                ter.ville,
                ter.adresse,
                u.nom as nom_gerant,
                u.prenom as prenom_gerant,
                u.telephone as telephone_gerant,
                COALESCE((SELECT COUNT(*) FROM Equipe WHERE id_tournoi = t.id_tournoi), 0) as nombre_equipes_inscrites,
                (t.nombre_max_equipes - COALESCE((SELECT COUNT(*) FROM Equipe WHERE id_tournoi = t.id_tournoi), 0)) as places_disponibles
            FROM {$this->table} t
            INNER JOIN terrain ter ON t.id_terrain = ter.id_terrain
            INNER JOIN utilisateur u ON t.id_gerant = u.id_utilisateur
            WHERE t.id_tournoi = :id
        ");
        
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        $this->db->execute();
        
        $result = $this->db->result();
        return $result ?: null;
    }

    /**
     * Check if client is already registered for a tournament
     * 
     * @param int $idTournoi Tournament ID
     * @param int $idClient Client ID
     * @return bool True if already registered, false otherwise
     */
    public function isClientDejaInscrit(int $idTournoi, int $idClient): bool
    {
        $this->db->query("
            SELECT COUNT(*) as count 
            FROM Equipe 
            WHERE id_tournoi = :id_tournoi 
            AND id_responsable = :id_client
        ");
        
        $this->db->bindValue(':id_tournoi', $idTournoi, PDO::PARAM_INT);
        $this->db->bindValue(':id_client', $idClient, PDO::PARAM_INT);
        $this->db->execute();
        
        $result = $this->db->result();
        return $result && $result->count > 0;
    }

    /**
     * Register a team for a tournament
     * 
     * @param array $data Team data (nom_equipe, id_responsable, id_tournoi, nombre_joueurs)
     * @return bool True on success, false on failure
     */
    public function inscrireEquipe(array $data): bool
    {
        $this->db->query("
            INSERT INTO Equipe (nom_equipe, id_responsable, id_tournoi, nombre_joueurs) 
            VALUES (:nom_equipe, :id_responsable, :id_tournoi, :nombre_joueurs)
        ");
        
        $this->db->bindValue(':nom_equipe', $data['nom_equipe'], PDO::PARAM_STR);
        $this->db->bindValue(':id_responsable', $data['id_responsable'], PDO::PARAM_INT);
        $this->db->bindValue(':id_tournoi', $data['id_tournoi'], PDO::PARAM_INT);
        $this->db->bindValue(':nombre_joueurs', $data['nombre_joueurs'], PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    /**
     * Check if tournament has available spots
     * 
     * @param int $idTournoi Tournament ID
     * @return bool True if spots available, false otherwise
     */
    public function hasPlacesDisponibles(int $idTournoi): bool
    {
        $this->db->query("
            SELECT 
                t.nombre_max_equipes,
                COALESCE((SELECT COUNT(*) FROM Equipe WHERE id_tournoi = t.id_tournoi), 0) as inscrites
            FROM {$this->table} t
            WHERE t.id_tournoi = :id
        ");
        
        $this->db->bindValue(':id', $idTournoi, PDO::PARAM_INT);
        $this->db->execute();
        
        $result = $this->db->result();
        
        if (!$result) {
            return false;
        }
        
        return ($result->nombre_max_equipes - $result->inscrites) > 0;
    }

    /**
     * Get tournaments a client is participating in
     * 
     * @param int $idClient Client ID
     * @return array List of client's tournaments with team info
     */
    public function getMesTournois(int $idClient): array
    {
        $this->db->query("
            SELECT 
                t.*,
                ter.nom_terrain,
                ter.ville,
                e.nom_equipe,
                e.nombre_joueurs,
                e.date_inscription
            FROM {$this->table} t
            INNER JOIN Equipe e ON t.id_tournoi = e.id_tournoi
            INNER JOIN terrain ter ON t.id_terrain = ter.id_terrain
            WHERE e.id_responsable = :id_client
            ORDER BY t.date_debut DESC
        ");
        
        $this->db->bindValue(':id_client', $idClient, PDO::PARAM_INT);
        
        return $this->db->results();
    }
}

?>