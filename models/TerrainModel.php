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



    public function getTailles(): array
    {
        $this->db->query("SELECT DISTINCT taille FROM terrain ORDER BY taille");
        return array_map(fn($r) => $r->taille, $this->db->results());
    }

    public function getTypes(): array
    {
        $this->db->query("SELECT DISTINCT type FROM terrain ORDER BY type");
        return array_map(fn($r) => $r->type, $this->db->results());
    }


public function reserverTerrain(
    int $id_terrain, 
    int $id_utilisateur,
    string $date_reservation, 
    string $heure_debut, 
    string $heure_fin, 
    string $commentaires, 
    array $options_selectionnees = []
): ?int 
{
    try {
        $this->db->beginTransaction();

        $this->db->query("
            SELECT id_reservation 
            FROM reservation
            WHERE id_terrain = :id_terrain
            AND date_reservation = :date_reservation
            AND heure_debut = :heure_debut
            AND statut != 'Annulée'
            FOR UPDATE
        ");
        $this->db->bindValue(':id_terrain', $id_terrain, PDO::PARAM_INT);
        $this->db->bindValue(':date_reservation', $date_reservation, PDO::PARAM_STR);
        $this->db->bindValue(':heure_debut', $heure_debut, PDO::PARAM_STR);
        $existing = $this->db->result();

        if ($existing) {
            $this->db->rollBack();
            return null;
        }

        $this->db->query("SELECT prix_heure FROM terrain WHERE id_terrain = :id_terrain");
        $this->db->bindValue(':id_terrain', $id_terrain, PDO::PARAM_INT);
        $terrain = $this->db->result();

        if (!$terrain) {
            $this->db->rollBack();
            throw new Exception("Terrain introuvable");
        }
        $prix_terrain = $terrain->prix_heure;

        $prix_options = 0;
        if (!empty($options_selectionnees)) {
            $placeholders = implode(',', array_fill(0, count($options_selectionnees), '?'));
            $this->db->query("SELECT SUM(prix) AS total FROM optionsupplementaire WHERE id_option IN ($placeholders)");
            foreach ($options_selectionnees as $i => $id_option) {
                $this->db->bindValue($i + 1, $id_option, PDO::PARAM_INT);
            }
            $result = $this->db->result();
            $prix_options = $result->total ?? 0;
        }

        $prix_total = $prix_terrain + $prix_options;

        $this->db->query("
            INSERT INTO reservation (
                date_reservation,
                heure_debut,
                heure_fin,
                id_utilisateur,
                id_terrain,
                commentaires,
                statut,
                prix_total,
                statut_paiement
            )
            VALUES (
                :date_reservation,
                :heure_debut,
                :heure_fin,
                :id_utilisateur,
                :id_terrain,
                :commentaires,
                'Confirmée',
                :prix_total,
                'en_attente'
            )
        ");
        $this->db->bindValue(':date_reservation', $date_reservation, PDO::PARAM_STR);
        $this->db->bindValue(':heure_debut', $heure_debut, PDO::PARAM_STR);
        $this->db->bindValue(':heure_fin', $heure_fin, PDO::PARAM_STR);
        $this->db->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $this->db->bindValue(':id_terrain', $id_terrain, PDO::PARAM_INT);
        $this->db->bindValue(':commentaires', $commentaires, PDO::PARAM_STR);
        $this->db->bindValue(':prix_total', $prix_total);
        $this->db->execute();

        $id_reservation = (int)$this->db->lastInsertId();

    
        if (!empty($options_selectionnees)) {
            foreach ($options_selectionnees as $id_option) {
                $this->db->query("INSERT INTO reservation_option (id_reservation, id_option) VALUES (:id_reservation, :id_option)");
                $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $this->db->bindValue(':id_option', $id_option, PDO::PARAM_INT);
                $this->db->execute();
            }
        }

        $this->db->commit();
        return $id_reservation;

    } catch (Exception $e) {
        $this->db->rollBack();
        error_log("Erreur reservation: " . $e->getMessage());
        throw $e; 
    }
}


    public function getTerrainsByTypeAndTaille(?string $type = null, ?string $taille = null): array
    {
        try {
            $sql = "SELECT * FROM terrain WHERE 1=1";

            
            if ($type !== null) {
                $sql .= " AND type = :type";
            }

            if ($taille !== null) {
                $sql .= " AND taille = :taille";
            }

            $sql .= " ORDER BY nom_terrain";

            $this->db->query($sql);

            
            if ($type !== null) {
                $this->db->bindValue(':type', $type, PDO::PARAM_STR);
            }
            if ($taille !== null) {
                $this->db->bindValue(':taille', $taille, PDO::PARAM_STR);
            }

            
            return $this->db->results();

        } catch (Exception $e) {
            error_log("Erreur getTerrains: " . $e->getMessage());
            return []; 
        }
    }


    public function getBookedSlots(int $terrain_id, string $date): array
    {
        $sql = "
            SELECT heure_debut 
            FROM Reservation 
            WHERE id_terrain = :terrain_id 
            AND date_reservation = :date_reservation 
            AND statut != 'Annulée'
        ";

        $this->db->query($sql);
        $this->db->bindValue(':terrain_id', $terrain_id, PDO::PARAM_INT);
        $this->db->bindValue(':date_reservation', $date);
        $results = $this->db->results(); 

       
        $booked_slots = array_map(fn($r) => $r->heure_debut, $results);

        return $booked_slots;
    }

public function getReservationById(int $id): ?object
{
    $sql = "
        SELECT r.*, t.nom_terrain, t.prix_heure 
        FROM Reservation r
        JOIN Terrain t ON r.id_terrain = t.id_terrain
        WHERE r.id_reservation = :id
    ";

    $this->db->query($sql);
    $this->db->bindValue(':id', $id, PDO::PARAM_INT);
    $result = $this->db->result(); // résultat unique

    return $result ?: null; 
}



}