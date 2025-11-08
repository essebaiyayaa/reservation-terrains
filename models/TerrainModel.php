<?php

/**
 * Class TerrainModel
 * 
 * Handles all database operations for Terrain table
 * 
 * @package Models
 * @author  Jihane Chouhe
 * @version 1.0
 */
class TerrainModel extends BaseModel
{
    public function __construct()
    {
        $this->db = new PDODatabase();
        $this->init();
    }

    protected function init(): void
    {
        $this->table = 'Terrain';
    }

    /**
     * Get all terrains with optional filters
     * 
     * @param array $filters Associative array of filter criteria
     * @return array List of terrains with gerant info
     */
    public function getAll(array $filters = []): array
    {
        $sql = "
            SELECT 
                t.*,
                u.prenom as gerant_prenom,
                u.nom as gerant_nom,
                u.email as gerant_email,
                (SELECT COUNT(*) FROM Reservation r WHERE r.id_terrain = t.id_terrain) as nb_reservations
            FROM Terrain t
            LEFT JOIN Utilisateur u ON t.id_utilisateur = u.id_utilisateur
            WHERE 1=1
        ";

        $conditions = [];
        $params = [];

        if (!empty($filters['ville'])) {
            $conditions[] = "t.ville = :ville";
            $params[':ville'] = $filters['ville'];
        }

        if (!empty($filters['type'])) {
            $conditions[] = "t.type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['taille'])) {
            $conditions[] = "t.taille = :taille";
            $params[':taille'] = $filters['taille'];
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY t.nom_terrain ASC";

        $this->db->query($sql);

        foreach ($params as $key => $value) {
            $this->db->bindValue($key, $value, PDO::PARAM_STR);
        }

        return $this->db->results();
    }

    /**
     * Get terrain by ID with gerant info
     */
    public function getById(string $id): ?object
    {
        $this->db->query("
            SELECT 
                t.*,
                u.prenom as gerant_prenom,
                u.nom as gerant_nom,
                u.email as gerant_email,
                u.telephone as gerant_telephone
            FROM Terrain t
            LEFT JOIN Utilisateur u ON t.id_utilisateur = u.id_utilisateur
            WHERE t.id_terrain = :id
        ");

        $this->db->bindValue(':id', $id, PDO::PARAM_INT);

        return $this->db->result();
    }

    /**
     * Get all distinct cities
     */
    public function getDistinctVilles(): array
    {
        $this->db->query("SELECT DISTINCT ville FROM Terrain ORDER BY ville");
        $results = $this->db->results();
        
        return array_map(fn($row) => $row->ville, $results);
    }

    /**
     * Get terrains by gerant ID
     */
    public function getByGerantId(string $gerantId): array
    {
        $this->db->query("
            SELECT t.*,
                   (SELECT COUNT(*) FROM Reservation r WHERE r.id_terrain = t.id_terrain) as nb_reservations
            FROM Terrain t
            WHERE t.id_utilisateur = :gerant_id
            ORDER BY t.nom_terrain ASC
        ");

        $this->db->bindValue(':gerant_id', $gerantId, PDO::PARAM_INT);

        return $this->db->results();
    }
    public function getTotalCount(): int
{
    $this->db->query("SELECT COUNT(*) as total FROM Terrain");
    $result = $this->db->result();
    return $result ? intval($result->total) : 0;
}

    /**
     * Add new terrain
     */
    public function add(array $data): bool
    {
        $this->db->query("
            INSERT INTO Terrain 
            (nom_terrain, adresse, ville, taille, type, prix_heure, id_utilisateur) 
            VALUES 
            (:nom_terrain, :adresse, :ville, :taille, :type, :prix_heure, :id_utilisateur)
        ");

        $this->db->bindValue(':nom_terrain', $data['nom_terrain'], PDO::PARAM_STR);
        $this->db->bindValue(':adresse', $data['adresse'], PDO::PARAM_STR);
        $this->db->bindValue(':ville', $data['ville'], PDO::PARAM_STR);
        $this->db->bindValue(':taille', $data['taille'], PDO::PARAM_STR);
        $this->db->bindValue(':type', $data['type'], PDO::PARAM_STR);
        $this->db->bindValue(':prix_heure', $data['prix_heure'], PDO::PARAM_STR);
        $this->db->bindValue(':id_utilisateur', $data['id_utilisateur'], 
                            $data['id_utilisateur'] ? PDO::PARAM_INT : PDO::PARAM_NULL);

        return $this->db->execute();
    }

    /**
     * Update terrain
     */
    public function update(string $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE Terrain SET " . implode(", ", $fields) . " WHERE id_terrain = :id";

        $this->db->query($sql);

        foreach ($params as $param => $value) {
            $type = PDO::PARAM_STR;
            if ($param === ':id' || $param === ':id_utilisateur') {
                $type = $value ? PDO::PARAM_INT : PDO::PARAM_NULL;
            }
            $this->db->bindValue($param, $value, $type);
        }

        return $this->db->execute();
    }

    /**
     * Delete terrain
     */
    public function delete(string $id): bool
    {
        // First, delete related options supplementaires
        $this->db->query("DELETE FROM OptionSupplementaire WHERE id_terrain = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        $this->db->execute();

        // Then delete the terrain
        $this->db->query("DELETE FROM Terrain WHERE id_terrain = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);

        return $this->db->execute();
    }

    /**
     * Check if terrain belongs to gerant
     */
    public function belongsToGerant(string $terrainId, string $gerantId): bool
    {
        $this->db->query("
            SELECT COUNT(*) as count 
            FROM Terrain 
            WHERE id_terrain = :terrain_id AND id_utilisateur = :gerant_id
        ");

        $this->db->bindValue(':terrain_id', $terrainId, PDO::PARAM_INT);
        $this->db->bindValue(':gerant_id', $gerantId, PDO::PARAM_INT);

        $result = $this->db->result();
        return $result && $result->count > 0;
    }
}