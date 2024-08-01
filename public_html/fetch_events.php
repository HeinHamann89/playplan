<?php
header('Content-Type: application/json');

try {
    // Update these values with your Hostinger database credentials
    $mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

    // Check for connection errors
    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }

    $query = "SELECT * FROM events WHERE approval_status = 'approved'";
    $result = $mysqli->query($query);

    if (!$result) {
        throw new Exception('Query failed: ' . $mysqli->error);
    }

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events);

} catch (Exception $e) {
    // Log the error message
    error_log($e->getMessage());
    // Return an error response
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(500);
} finally {
    if (isset($mysqli) && $mysqli->ping()) {
        $mysqli->close();
    }
}
?>
