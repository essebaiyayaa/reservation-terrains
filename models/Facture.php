<?php
class Facture extends BaseModel {
    protected $table = "factures";

    public $id;
    public $id_reservation;
    public $montant;
    public $date_emission;
    public $mode_paiement;
    public $statut;

    //  Créer une facture à partir d'une réservation
    public function createFacture($id_reservation, $montant, $mode_paiement = "espèces") {
        $this->id_reservation = $id_reservation;
        $this->montant = $montant;
        $this->date_emission = date('Y-m-d H:i:s');
        $this->mode_paiement = $mode_paiement;
        $this->statut = "payée";

        $query = "INSERT INTO factures (id_reservation, montant, date_emission, mode_paiement, statut)
                  VALUES (:id_reservation, :montant, :date_emission, :mode_paiement, :statut)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':id_reservation' => $this->id_reservation,
            ':montant' => $this->montant,
            ':date_emission' => $this->date_emission,
            ':mode_paiement' => $this->mode_paiement,
            ':statut' => $this->statut
        ]);

        return $this->db->lastInsertId();
    }

    //  Récupérer une facture par son ID
    public function getFactureById($id) {
        $query = "SELECT * FROM factures WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllFactures() {
        $query = "SELECT * FROM factures ORDER BY date_emission DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteFacture($id) {
        $query = "DELETE FROM factures WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
?>