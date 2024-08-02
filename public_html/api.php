<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY start_date");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
} catch(PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>