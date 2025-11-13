<?php
/**
 * API Endpoint: Get Available Time Slots
 * Returns booked time slots for a specific terrain and date
 */


error_reporting(0);
ini_set('display_errors', 0);

while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

// Headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Cache-Control: no-cache, must-revalidate');

try {

    require_once __DIR__ . '/../../config/config.php';
     require_once __DIR__ . '/../../database/PDOdatabase.php';

    $terrain_id = isset($_GET['terrain_id']) ? (int)$_GET['terrain_id'] : null;
    $date = isset($_GET['date']) ? trim($_GET['date']) : null;

    if (!$terrain_id || !$date) {
        throw new Exception('Paramètres manquants: terrain_id et date requis');
    }


    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        throw new Exception('Format de date invalide. Utilisez YYYY-MM-DD');
    }

    $db = new PDODatabase();


    $sql = "
        SELECT heure_debut 
        FROM Reservation 
        WHERE id_terrain = :terrain_id 
        AND date_reservation = :date_reservation 
        AND statut != 'Annulée'
        ORDER BY heure_debut
    ";

    $db->query($sql);
    $db->bindValue(':terrain_id', $terrain_id, PDO::PARAM_INT);
    $db->bindValue(':date_reservation', $date, PDO::PARAM_STR);

    $results = $db->results();

    $booked_slots = array_map(function($row) {
        return $row->heure_debut;
    }, $results);

    ob_clean();
    echo json_encode([
        'success' => true,
        'booked_slots' => $booked_slots,
        'date' => $date,
        'terrain_id' => $terrain_id,
        'timestamp' => time(),
        'count' => count($booked_slots)
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'file' => __FILE__,
            'line' => $e->getLine()
        ]
    ], JSON_UNESCAPED_UNICODE);
}

ob_end_flush();
exit;