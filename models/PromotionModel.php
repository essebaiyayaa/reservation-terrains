<?php

/**
 * Class PromotionModel
 * 
 * Handles all database operations for the Promotion table
 * 
 * @package Models
 * @version 1.1
 */
class PromotionModel extends BaseModel
{
    public function __construct()
    {
        $this->db = new PDODatabase();
        $this->init();
    }

    public function init(): void
    {
        $this->table = 'Promotion';
    }

    /**
     * Ajouter une nouvelle promotion
     */
    public function add(array $data): bool
{
    $this->db->query("
        INSERT INTO Promotion 
        (id_terrain, description, pourcentage_remise, date_debut, date_fin, actif) 
        VALUES 
        (:id_terrain, :description, :pourcentage_remise, :date_debut, :date_fin, :actif)
    ");

    $this->db->bindValue(':id_terrain', $data['id_terrain'], PDO::PARAM_INT);
    $this->db->bindValue(':description', $data['description'], PDO::PARAM_STR);
    $this->db->bindValue(':pourcentage_remise', $data['pourcentage_remise'], PDO::PARAM_STR); // forcer string
    $this->db->bindValue(':date_debut', $data['date_debut'], PDO::PARAM_STR);
    $this->db->bindValue(':date_fin', $data['date_fin'], PDO::PARAM_STR);
    $this->db->bindValue(':actif', $data['actif'] ?? 1, PDO::PARAM_INT);

    return $this->db->execute();
}

    /**
     * Retourner toutes les promotions
     */
    public function getAll(): array
    {
        $this->db->query("SELECT * FROM Promotion ORDER BY date_debut DESC");
        return $this->db->results();
    }

    /**
     * Retourner une promotion par son ID
     */
    public function getById(string $id): ?object
{
    $this->db->query("SELECT * FROM Promotion WHERE id_promo = :id");
    $this->db->bindValue(':id', $id, PDO::PARAM_INT);
    $result = $this->db->single(); // retourne un tableau ou null
    return $result ? (object) $result : null; // transforme en objet ou null
}

    /**
     * Mettre à jour une promotion
     */
    public function update(string $id, array $data): bool
{
    $this->db->query("
        UPDATE Promotion SET
            id_terrain = :id_terrain,
            description = :description,
            pourcentage_remise = :pourcentage_remise,
            date_debut = :date_debut,
            date_fin = :date_fin,
            actif = :actif
        WHERE id_promo = :id
    ");

    $this->db->bindValue(':id_terrain', $data['id_terrain'], PDO::PARAM_INT);
    $this->db->bindValue(':description', $data['description'], PDO::PARAM_STR);
    $this->db->bindValue(':pourcentage_remise', $data['pourcentage_remise']);
    $this->db->bindValue(':date_debut', $data['date_debut'], PDO::PARAM_STR);
    $this->db->bindValue(':date_fin', $data['date_fin'], PDO::PARAM_STR);
    $this->db->bindValue(':actif', $data['actif'] ?? 1, PDO::PARAM_INT);
    $this->db->bindValue(':id', $id, PDO::PARAM_INT);

    return $this->db->execute();
}
    /**
     * Supprimer une promotion
     */
   public function delete(string $id): bool
{
    $this->db->query("DELETE FROM Promotion WHERE id_promo = :id");
    $this->db->bindValue(':id', $id, PDO::PARAM_INT); // reste PDO::PARAM_INT si id est un int dans la BDD
    return $this->db->execute();
}

    /**
     * Obtenir les promotions par terrain
     */
    public function getByTerrainId(int $terrainId): array
    {
        $this->db->query("SELECT * FROM Promotion WHERE id_terrain = :id_terrain ORDER BY date_debut DESC");
        $this->db->bindValue(':id_terrain', $terrainId, PDO::PARAM_INT);
        return $this->db->results();
    }

    /**
     * Désactiver une promotion
     */
    public function deactivate(int $idPromo): bool
    {
        $this->db->query("UPDATE Promotion SET actif = 0 WHERE id_promo = :id");
        $this->db->bindValue(':id', $idPromo, PDO::PARAM_INT);
        return $this->db->execute();
    }
}
?>