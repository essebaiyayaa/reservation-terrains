<?php

/**
 * Class ReservationModel
 * 
 * Handles all reservation-related database operations
 * 
 * @package Models
 * @author  Jihane Chouhe
 * @version 1.2 - Fixed $db initialization without modifying BaseModel
 */
class ReservationModel extends BaseModel
{
    protected string $table = 'Reservation';
    protected string $primaryKey = 'id_reservation';
    protected string $schema;

    /**
     * Constructor - Initialise $db manuellement
     */
    public function __construct()
    {
        // Initialiser $db manuellement puisque BaseModel::__construct() est commenté
        $this->db = new PDODatabase();
        $this->init();
    }

    /**
     * Initialize the model
     */
    protected function init(): void
    {
        $this->table = 'Reservation';
        $this->schema = "
            CREATE TABLE IF NOT EXISTS Reservation (
                id_reservation INT AUTO_INCREMENT PRIMARY KEY,
                id_utilisateur INT NOT NULL,
                id_terrain INT NOT NULL,
                date_reservation DATE NOT NULL,
                heure_debut TIME NOT NULL,
                heure_fin TIME NOT NULL,
                prix_total DECIMAL(10,2) NOT NULL,
                statut_paiement ENUM('en_attente', 'paye', 'annule') DEFAULT 'en_attente',
                date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
                FOREIGN KEY (id_terrain) REFERENCES Terrain(id_terrain)
            )
        ";
    }

    /**
     * Get all reservations with filters
     * 
     * @param array $filters Array of filter criteria
     * @return array List of reservations
     */
    public function getAll(array $filters = []): array
    {
        $sql = "
            SELECT 
                r.*, 
                t.nom_terrain, 
                t.ville,
                u.prenom as client_prenom, 
                u.nom as client_nom, 
                u.telephone,
                g.prenom as gerant_prenom,
                g.nom as gerant_nom
            FROM Reservation r
            JOIN Terrain t ON r.id_terrain = t.id_terrain
            JOIN Utilisateur u ON r.id_utilisateur = u.id_utilisateur
            LEFT JOIN Utilisateur g ON t.id_utilisateur = g.id_utilisateur
            WHERE 1=1
        ";
        
        $params = [];
        
        // Filter by statut
        if (!empty($filters['statut'])) {
            $sql .= " AND r.statut_paiement = :statut";
            $params[':statut'] = $filters['statut'];
        }
        
        // Filter by date
        if (!empty($filters['date'])) {
            $sql .= " AND r.date_reservation = :date";
            $params[':date'] = $filters['date'];
        }
        
        // Filter by terrain
        if (!empty($filters['terrainId'])) {
            $sql .= " AND r.id_terrain = :terrain_id";
            $params[':terrain_id'] = $filters['terrainId'];
        }
        
        $sql .= " ORDER BY r.date_reservation DESC, r.heure_debut DESC";
        
        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bindValue($key, $value, PDO::PARAM_STR);
        }
        
        return $this->db->results();
    }

    /**
     * Get reservations by terrain ID
     * 
     * @param string $terrainId Terrain ID
     * @return array List of reservations
     */
public function getByTerrainId(string $terrainId): array
{
    $this->db->query("
        SELECT 
            r.*,
            t.nom_terrain,
            t.ville,
            u.prenom as client_prenom,
            u.nom as client_nom,
            u.email as client_email,
            u.telephone as client_telephone
        FROM Reservation r
        JOIN Utilisateur u ON r.id_utilisateur = u.id_utilisateur
        JOIN Terrain t ON r.id_terrain = t.id_terrain
        WHERE r.id_terrain = :terrain_id
        ORDER BY r.date_reservation DESC, r.heure_debut DESC
    ");
    
    $this->db->bindValue(':terrain_id', $terrainId, PDO::PARAM_INT);
    
    return $this->db->results();
}

    /**
     * Get reservations by user ID
     * 
     * @param string $userId User ID
     * @return array List of user's reservations
     */
    public function getByUserId(string $userId): array
    {
        $this->db->query("
            SELECT 
                r.*,
                t.nom_terrain,
                t.ville,
                t.adresse,
                t.type,
                t.prix_heure
            FROM Reservation r
            JOIN Terrain t ON r.id_terrain = t.id_terrain
            WHERE r.id_utilisateur = :user_id
            ORDER BY r.date_reservation DESC, r.heure_debut DESC
        ");
        
        $this->db->bindValue(':user_id', $userId, PDO::PARAM_INT);
        
        return $this->db->results();
    }

    /**
     * Get monthly revenue (current month)
     * 
     * @return float Total revenue for current month
     */
    public function getMonthlyRevenue(): float
    {
        $this->db->query("
            SELECT COALESCE(SUM(prix_total), 0) as ca_mois 
            FROM Reservation 
            WHERE MONTH(date_reservation) = MONTH(CURDATE()) 
            AND YEAR(date_reservation) = YEAR(CURDATE())
            AND statut_paiement = 'paye'
        ");
        
        $result = $this->db->result();
        return $result ? floatval($result->ca_mois) : 0;
    }

    /**
     * Get reservation by ID with full details
     * 
     * @param string $id Reservation ID
     * @return object|null Reservation object or null
     */
    public function getById(string $id): ?object
    {
        $this->db->query("
            SELECT 
                r.*,
                t.nom_terrain,
                t.ville,
                t.adresse,
                t.type,
                u.prenom as client_prenom,
                u.nom as client_nom,
                u.email as client_email,
                u.telephone as client_telephone
            FROM Reservation r
            JOIN Terrain t ON r.id_terrain = t.id_terrain
            JOIN Utilisateur u ON r.id_utilisateur = u.id_utilisateur
            WHERE r.id_reservation = :id
        ");
        
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        
        $result = $this->db->result();
        return $result ?: null;
    }





    public function getUserReservations(int $user_id): array
    {
        try {
            $this->db->query("
                SELECT 
                    r.id_reservation,
                    r.date_reservation,
                    r.heure_debut,
                    r.heure_fin,
                    r.commentaires,
                    r.statut,
                    r.date_creation,
                    t.nom_terrain,
                    t.ville,
                    t.taille,
                    t.type,
                    t.prix_heure,
                    GROUP_CONCAT(o.nom_option SEPARATOR ', ') AS options
                FROM Reservation r
                JOIN Terrain t ON r.id_terrain = t.id_terrain
                LEFT JOIN Reservation_Option ro ON r.id_reservation = ro.id_reservation
                LEFT JOIN OptionSupplementaire o ON ro.id_option = o.id_option
                WHERE r.id_utilisateur = :id
                GROUP BY r.id_reservation
                ORDER BY r.date_reservation DESC, r.heure_debut DESC
            ");
            
            $this->db->bindValue(':id', $user_id, PDO::PARAM_INT);
            $this->db->execute();
            return $this->db->results(); 

        } catch (Exception $e) {
            error_log("Error fetching user reservations: " . $e->getMessage());
            return [];
        }
    }









    /**
     * Update reservation
     * 
     * @param string $id Reservation ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public function update(string $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];
        
        // Build dynamic UPDATE query
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $sql = "UPDATE Reservation SET " . implode(', ', $fields) . " WHERE id_reservation = :id";
        
        $this->db->query($sql);
        
        foreach ($params as $param => $val) {
            $this->db->bindValue($param, $val, PDO::PARAM_STR);
        }
        
        return $this->db->execute();
    }

    /**
     * Add new reservation
     * 
     * @param array $data Reservation data
     * @return bool Success status
     */
    public function add(array $data): bool
    {
        $this->db->query("
            INSERT INTO Reservation (
                id_utilisateur,
                id_terrain,
                date_reservation,
                heure_debut,
                heure_fin,
                prix_total,
                statut_paiement,
                date_creation
            ) VALUES (
                :id_utilisateur,
                :id_terrain,
                :date_reservation,
                :heure_debut,
                :heure_fin,
                :prix_total,
                :statut_paiement,
                NOW()
            )
        ");
        
        $this->db->bindValue(':id_utilisateur', $data['id_utilisateur'], PDO::PARAM_INT);
        $this->db->bindValue(':id_terrain', $data['id_terrain'], PDO::PARAM_INT);
        $this->db->bindValue(':date_reservation', $data['date_reservation'], PDO::PARAM_STR);
        $this->db->bindValue(':heure_debut', $data['heure_debut'], PDO::PARAM_STR);
        $this->db->bindValue(':heure_fin', $data['heure_fin'], PDO::PARAM_STR);
        $this->db->bindValue(':prix_total', $data['prix_total'], PDO::PARAM_STR);
        $this->db->bindValue(':statut_paiement', $data['statut_paiement'] ?? 'en_attente', PDO::PARAM_STR);
        
        return $this->db->execute();
    }

    /**
     * Update reservation status
     * 
     * @param string $id Reservation ID
     * @param string $statut New status
     * @return bool Success status
     */
    public function updateStatus(string $id, string $statut): bool
    {
        $this->db->query("
            UPDATE Reservation 
            SET statut_paiement = :statut 
            WHERE id_reservation = :id
        ");
        
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        $this->db->bindValue(':statut', $statut, PDO::PARAM_STR);
        
        return $this->db->execute();
    }

    /**
     * Check if time slot is available
     * 
     * @param string $terrainId Terrain ID
     * @param string $date Reservation date
     * @param string $heureDebut Start time
     * @param string $heureFin End time
     * @param string|null $excludeId Reservation ID to exclude (for updates)
     * @return bool True if available
     */
    public function isTimeSlotAvailable(
        string $terrainId, 
        string $date, 
        string $heureDebut, 
        string $heureFin,
        ?string $excludeId = null
    ): bool {
        $sql = "
            SELECT COUNT(*) as count
            FROM Reservation
            WHERE id_terrain = :terrain_id
            AND date_reservation = :date
            AND statut_paiement != 'annule'
            AND (
                (heure_debut < :heure_fin AND heure_fin > :heure_debut)
            )
        ";
        
        if ($excludeId) {
            $sql .= " AND id_reservation != :exclude_id";
        }
        
        $this->db->query($sql);
        $this->db->bindValue(':terrain_id', $terrainId, PDO::PARAM_INT);
        $this->db->bindValue(':date', $date, PDO::PARAM_STR);
        $this->db->bindValue(':heure_debut', $heureDebut, PDO::PARAM_STR);
        $this->db->bindValue(':heure_fin', $heureFin, PDO::PARAM_STR);
        
        if ($excludeId) {
            $this->db->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        
        $result = $this->db->result();
        return $result && $result->count == 0;
    }

    /**
     * Get upcoming reservations for a user
     * 
     * @param string $userId User ID
     * @param int $limit Number of results
     * @return array List of upcoming reservations
     */
    public function getUpcomingByUserId(string $userId, int $limit = 5): array
    {
        $this->db->query("
            SELECT 
                r.*,
                t.nom_terrain,
                t.ville,
                t.adresse,
                t.type_terrain
            FROM Reservation r
            JOIN Terrain t ON r.id_terrain = t.id_terrain
            WHERE r.id_utilisateur = :user_id
            AND r.date_reservation >= CURDATE()
            AND r.statut_paiement != 'annule'
            ORDER BY r.date_reservation ASC, r.heure_debut ASC
            LIMIT :limit
        ");
        
        $this->db->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $this->db->bindValue(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->results();
    }

    /**
     * Delete reservation
     * 
     * @param string $id Reservation ID
     * @return bool Success status
     */
    public function delete(string $id): bool
    {
        $this->db->query("DELETE FROM Reservation WHERE id_reservation = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    /**
     * Get total reservations count
     * 
     * @return int Total count
     */
    public function getTotalCount(): int
    {
        try {
            $this->db->query("SELECT COUNT(*) as total FROM Reservation");
            $result = $this->db->result();
            
            return $result ? intval($result->total) : 0;
        } catch (Exception $e) {
            error_log("Error getting total count: " . $e->getMessage());
            return 0;
        }
    }



    public function getTodaysReservations($id): int {
    try {
        $this->db->query("
            SELECT COUNT(*) AS total
            FROM Reservation r
            JOIN Terrain t ON r.id_terrain = t.id_terrain
            WHERE t.id_utilisateur = :id
            AND r.date_reservation = CURDATE()
        ");

        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $this->db->result();
        
        return $result ? intval($result->total) : 0;
    } catch (Exception $e) {
        error_log("Error getting todays reservations count: " . $e->getMessage());
        return 0;
    }
}

    public function getChiffreAffairesMoisGerant($id): float {
    try {
        $this->db->query("
            SELECT COALESCE(SUM(t.prix_heure), 0) as total
            FROM Reservation r 
            JOIN Terrain t ON r.id_terrain = t.id_terrain 
            WHERE t.id_utilisateur = :id
            AND MONTH(r.date_reservation) = MONTH(CURDATE()) 
            AND YEAR(r.date_reservation) = YEAR(CURDATE())
        ");
        
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $this->db->result();
        
        return $result ? floatval($result->total) : 0.0;
    } catch (Exception $e) {
        error_log("Error getting monthly revenue: " . $e->getMessage());
        return 0.0;
    }
}



    /**
     * Get reservations statistics
     * 
     * @return array Statistics data
     */
    public function getStatistics(): array
    {
        $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN statut_paiement = 'paye' THEN 1 ELSE 0 END) as payes,
                SUM(CASE WHEN statut_paiement = 'en_attente' THEN 1 ELSE 0 END) as en_attente,
                SUM(CASE WHEN statut_paiement = 'annule' THEN 1 ELSE 0 END) as annules,
                SUM(CASE WHEN statut_paiement = 'paye' THEN prix_total ELSE 0 END) as revenue_total
            FROM Reservation
        ");
        
        return (array) $this->db->result();
    }







    /**
     * Get reservation details along with terrain and user info
     */
    public function getReservationDetails(int $id_reservation, int $id_utilisateur): ?object {
        try {
            $this->db->query("
                SELECT r.*, 
                       t.nom_terrain, t.adresse, t.ville, t.prix_heure, 
                       u.nom AS user_nom, u.prenom AS user_prenom, u.email, u.telephone
                FROM reservation r
                JOIN terrain t ON r.id_terrain = t.id_terrain
                JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
                WHERE r.id_reservation = :id_reservation
                  AND r.id_utilisateur = :id_utilisateur
            ");
            $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
            $this->db->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);

            return $this->db->result();
        } catch (Exception $e) {
            error_log("Erreur getReservationDetails: " . $e->getMessage());
            return null;
        }
    }


    public function getReservationOptions(int $id_reservation): array {
        try {
            $this->db->query("
                SELECT os.nom_option, os.prix
                FROM reservation_option ro
                JOIN optionsupplementaire os ON ro.id_option = os.id_option
                WHERE ro.id_reservation = :id_reservation
            ");
            $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);

            return $this->db->results();
        } catch (Exception $e) {
            error_log("Erreur getReservationOptions: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Calculate total price including options
     */
    public function calculateTotal(object $reservation, array $options): array
    {
        $total_options = 0.0;

        foreach ($options as $option) {
            $total_options += (float) $option->prix;
        }

       
        $total_general = (float) $reservation->prix_heure + $total_options;

        return [
            'total_options' => $total_options,
            'total_general' => $total_general
        ];
    }





    public function getReservationWithTerrain(int $id_reservation, int $user_id): ?object
    {
        $sql = "
            SELECT r.*, t.nom_terrain, t.ville, t.taille, t.type, t.prix_heure, t.id_terrain
            FROM Reservation r
            JOIN Terrain t ON r.id_terrain = t.id_terrain
            WHERE r.id_reservation = :id_reservation AND r.id_utilisateur = :user_id
        ";

        $this->db->query($sql);
        $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
        $this->db->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $result = $this->db->result();

        return $result ?: null;
    }



    public function getReservationOptionsSupp(int $id_reservation): array
    {
        $sql = "SELECT id_option FROM Reservation_Option WHERE id_reservation = :id_reservation";
        $this->db->query($sql);
        $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
        $rows = $this->db->results();

        return array_map(fn($r) => $r->id_option, $rows);
    }


     public function getAllTerrains(): array
    {
        $this->db->query("SELECT * FROM Terrain ORDER BY ville, nom_terrain");
        return array_map(fn($r) => $r, $this->db->results());
    }

    public function getAllSuppOptions(): array
    {
        $this->db->query("SELECT * FROM OptionSupplementaire ORDER BY nom_option");
        return $this->db->results();
    }




    public function updateReservation(
        int $id_reservation,
        int $user_id,
        string $date_reservation,
        string $heure_debut,
        string $heure_fin,
        int $id_terrain,
        string $commentaires
    ): bool {

        echo $id_reservation;
        echo $user_id;
        echo $date_reservation;
        echo $heure_debut;
        echo $heure_fin;
        echo $id_terrain;
        echo $commentaires;
        
        $sql = "
            UPDATE Reservation
            SET date_reservation = :date_reservation,
                heure_debut = :heure_debut,
                heure_fin = :heure_fin,
                id_terrain = :id_terrain,
                commentaires = :commentaires,
                statut = CASE WHEN statut = 'Confirmée' THEN 'Modifiée' ELSE statut END,
                date_modification = NOW()
            WHERE id_reservation = :id_reservation AND id_utilisateur = :user_id
        ";

        $this->db->query($sql);
        $this->db->bindValue(':date_reservation', $date_reservation);
        $this->db->bindValue(':heure_debut', $heure_debut);
        $this->db->bindValue(':heure_fin', $heure_fin);
        $this->db->bindValue(':id_terrain', $id_terrain,  PDO::PARAM_INT);
        $this->db->bindValue(':commentaires', $commentaires);
        $this->db->bindValue(':id_reservation', $id_reservation,  PDO::PARAM_INT);
        $this->db->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        return $this->db->execute();
    }



 public function hasTimeConflict(
    int $id_terrain,
    string $date_reservation,
    string $heure_debut,
    string $heure_fin,
    int $id_reservation
): bool {
    
    error_log("=== CHECK TIME CONFLICT ===");
    error_log("Terrain: $id_terrain, Date: $date_reservation");
    error_log("Horaire: $heure_debut - $heure_fin");
    error_log("Exclure réservation ID: $id_reservation");
    
    // ✅ Ajouter :00 aux heures si nécessaire
    if (strlen($heure_debut) === 5) {
        $heure_debut .= ':00';
    }
    if (strlen($heure_fin) === 5) {
        $heure_fin .= ':00';
    }
    
    // ✅ CORRECTION: Simplifier la requête SQL
    $sql = "
        SELECT COUNT(*) AS count 
        FROM Reservation 
        WHERE id_terrain = :id_terrain
        AND date_reservation = :date_reservation
        AND statut NOT IN ('Annulée')
        AND id_reservation != :id_reservation
        AND (
            (heure_debut < :heure_fin AND heure_fin > :heure_debut)
        )
    ";

    try {
        $this->db->query($sql);
        $this->db->bindValue(':id_terrain', $id_terrain, PDO::PARAM_INT);
        $this->db->bindValue(':date_reservation', $date_reservation, PDO::PARAM_STR);
        $this->db->bindValue(':heure_debut', $heure_debut, PDO::PARAM_STR);
        $this->db->bindValue(':heure_fin', $heure_fin, PDO::PARAM_STR);
        $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
        
        $result = $this->db->result();
        
        $hasConflict = $result && $result->count > 0;
        
        error_log("Conflits trouvés: " . ($result ? $result->count : 'NULL'));
        error_log("Résultat: " . ($hasConflict ? "CONFLIT" : "PAS DE CONFLIT"));
        
        if ($hasConflict && $result->count > 0) {
            // ✅ DEBUG: Afficher les réservations conflictuelles
            $debug_sql = "
                SELECT id_reservation, heure_debut, heure_fin, statut
                FROM Reservation 
                WHERE id_terrain = :id_terrain
                AND date_reservation = :date_reservation
                AND statut NOT IN ('Annulée')
                AND id_reservation != :id_reservation
            ";
            $this->db->query($debug_sql);
            $this->db->bindValue(':id_terrain', $id_terrain, PDO::PARAM_INT);
            $this->db->bindValue(':date_reservation', $date_reservation, PDO::PARAM_STR);
            $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
            
            $conflicts = $this->db->results();
            foreach ($conflicts as $conflict) {
                error_log("  Conflit avec résa #" . $conflict->id_reservation . 
                         " (" . $conflict->heure_debut . " - " . $conflict->heure_fin . 
                         ") statut: " . $conflict->statut);
            }
        }
        
        return $hasConflict;
        
    } catch (Exception $e) {
        error_log("❌ ERREUR SQL hasTimeConflict: " . $e->getMessage());
        error_log("SQL: $sql");
        return true; // En cas d'erreur, on considère qu'il y a conflit par sécurité
    }
}




    public function resetReservationOptions(int $id_reservation, array $selected_options): void
    {
        $this->db->query("DELETE FROM Reservation_Option WHERE id_reservation = :id_reservation");
        $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
        $this->db->execute();

        if (!empty($selected_options)) {
            foreach ($selected_options as $id_option) {
                $this->db->query("INSERT INTO Reservation_Option (id_reservation, id_option) VALUES (:id_reservation, :id_option)");
                $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $this->db->bindValue(':id_option', $id_option, PDO::PARAM_INT);
                $this->db->execute();
            }
        }
    }


    public function cancelReservation(int $id_reservation, int $user_id): bool
    {
        $sql = "
            UPDATE Reservation
            SET statut = 'Annulée',
                date_modification = NOW()
            WHERE id_reservation = :id_reservation
            AND id_utilisateur = :user_id
        ";

        $this->db->query($sql);
        $this->db->bindValue(':id_reservation', $id_reservation, PDO::PARAM_INT);
        $this->db->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        return $this->db->execute();
    }


    



    
}