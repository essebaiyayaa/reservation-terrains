<?php
/**
 * API Endpoint: Search Terrains
 * Returns terrains filtered by type and/or size
 */

error_reporting(0);
ini_set('display_errors', 0);

while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');

try {
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../../database/PDOdatabase.php';



    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $taille = isset($_GET['taille']) ? trim($_GET['taille']) : '';

    $db = new PDODatabase();

    $sql = "SELECT * FROM Terrain WHERE 1=1";
    
    if (!empty($type)) {
        $sql .= " AND type = :type";
    }
    
    if (!empty($taille)) {
        $sql .= " AND taille = :taille";
    }
    
    $sql .= " ORDER BY nom_terrain";

    $db->query($sql);
    
    if (!empty($type)) {
        $db->bindValue(':type', $type, PDO::PARAM_STR);
    }
    
    if (!empty($taille)) {
        $db->bindValue(':taille', $taille, PDO::PARAM_STR);
    }

    $terrains = $db->results();

    $terrains_array = array_map(function($terrain) {
        return (array) $terrain;
    }, $terrains);

    ob_clean();
    echo json_encode($terrains_array, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

ob_end_flush();
exit;