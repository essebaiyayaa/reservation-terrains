<?php

/**
 * Class OptionSupplementaireModel
 * 
 * Handles all database operations for OptionSupplementaire table
 * 
 * @package Models
 * @author  System
 * @version 1.0
 */
class OptionSupplementaireModel extends BaseModel
{
    public function __construct()
    {
        $this->db = new PDODatabase();
        $this->init();
    }

    protected function init(): void
    {
        $this->table = 'OptionSupplementaire';
    }

    /**
     * Get all options (not typically used)
     */
    public function getAll(): array
    {
        $this->db->query("SELECT * FROM OptionSupplementaire ORDER BY nom_option");
        return $this->db->results();
    }

    /**
     * Get option by ID
     */
    public function getById(string $id): ?object
    {
        $this->db->query("SELECT * FROM OptionSupplementaire WHERE id_option = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        return $this->db->result();
    }

    /**
     * Get all options for a specific terrain
     */
    public function getByTerrainId(string $terrainId): array
    {
        $this->db->query("
            SELECT * FROM OptionSupplementaire 
            WHERE id_terrain = :terrain_id 
            ORDER BY nom_option
        ");
        
        $this->db->bindValue(':terrain_id', $terrainId, PDO::PARAM_INT);
        return $this->db->results();
    }

    /**
     * Add new option
     */
    public function add(array $data): bool
    {
        $this->db->query("
            INSERT INTO OptionSupplementaire (id_terrain, nom_option, prix) 
            VALUES (:id_terrain, :nom_option, :prix)
        ");

        $this->db->bindValue(':id_terrain', $data['id_terrain'], PDO::PARAM_INT);
        $this->db->bindValue(':nom_option', $data['nom_option'], PDO::PARAM_STR);
        $this->db->bindValue(':prix', $data['prix'], PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Update option
     */
    public function update(string $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE OptionSupplementaire SET " . implode(", ", $fields) . " WHERE id_option = :id";

        $this->db->query($sql);

        foreach ($params as $param => $value) {
            $type = ($param === ':id' || $param === ':id_terrain') ? PDO::PARAM_INT : PDO::PARAM_STR;
            $this->db->bindValue($param, $value, $type);
        }

        return $this->db->execute();
    }

    /**
     * Delete option
     */
    public function delete(string $id): bool
    {
        $this->db->query("DELETE FROM OptionSupplementaire WHERE id_option = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Delete all options for a terrain
     */
    public function deleteByTerrainId(string $terrainId): bool
    {
        $this->db->query("DELETE FROM OptionSupplementaire WHERE id_terrain = :terrain_id");
        $this->db->bindValue(':terrain_id', $terrainId, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Check if option exists
     */
    public function exists(string $terrainId, string $nomOption): bool
    {
        $this->db->query("
            SELECT COUNT(*) as count 
            FROM OptionSupplementaire 
            WHERE id_terrain = :terrain_id AND nom_option = :nom_option
        ");

        $this->db->bindValue(':terrain_id', $terrainId, PDO::PARAM_INT);
        $this->db->bindValue(':nom_option', $nomOption, PDO::PARAM_STR);

        $result = $this->db->result();
        return $result && $result->count > 0;
    }


    public function getAllOptions(): array
    {
        try {
            $this->db->query("SELECT * FROM OptionSupplementaire ORDER BY nom_option");
            return $this->db->results();
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des options : " . $e->getMessage());
            return [];
        }
    }
    // Dans OptionSupplementaireModel
public function getOptionsByReservation(int $reservationId): array
{
    $sql = "
        SELECT o.*
        FROM OptionReservation orr
        JOIN OptionSupplementaire o ON orr.id_option = o.id_option
        WHERE orr.id_reservation = :reservation_id
    ";

    $this->db->query($sql);
    $this->db->bindValue(':reservation_id', $reservationId, PDO::PARAM_INT);
    $results = $this->db->results(); // tableau d'objets

    return $results;
}

}