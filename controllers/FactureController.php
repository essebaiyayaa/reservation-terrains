<?php
class FactureController extends Controller {

    //  Liste des factures
    public function index() {
        $factureModel = new Facture();
        $factures = $factureModel->getAllFactures();
        $this->render('facture/list', ['factures' => $factures]);
    }

    // Afficher une facture spécifique
    public function show($id) {
        $factureModel = new Facture();
        $facture = $factureModel->getFactureById($id);
        if ($facture) {
            $this->render('facture/show', ['facture' => $facture]);
        } else {
            echo "<p> Facture introuvable.</p>";
        }
    }

    //  Générer une facture après réservation validée
    public function generate($reservationId) {
        $reservationModel = new Reservation();
        $reservation = $reservationModel->getReservationById($reservationId);

        if ($reservation) {
            $factureModel = new Facture();
            $montant = $reservation['prix_total'];
            $factureId = $factureModel->createFacture($reservationId, $montant, "carte");

            header("Location: /facture/show/" . $factureId);
        } else {
            echo "<p> Réservation introuvable, impossible de générer la facture.</p>";
        }
    }
}
?>