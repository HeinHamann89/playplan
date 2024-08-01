<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

        if ($mysqli->connect_error) {
            throw new Exception('Database connection failed: ' . $mysqli->connect_error);
        }

        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            throw new Exception('Failed to register user: ' . $stmt->error);
        }

        $stmt->close();
        $mysqli->close();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
