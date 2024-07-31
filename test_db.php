<?php
try {
    $mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }

    echo "Database connection successful!";

    $mysqli->close();

} catch (Exception $e) {
    echo $e->getMessage();
}
?>