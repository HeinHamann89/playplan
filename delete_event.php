<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No event ID provided!";
    exit();
}

try {
    $mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $_GET['id'], $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "Event deleted successfully!";
    } else {
        throw new Exception('Failed to delete event: ' . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
