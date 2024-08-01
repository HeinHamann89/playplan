<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

        if ($mysqli->connect_error) {
            throw new Exception('Database connection failed: ' . $mysqli->connect_error);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: public_submission_form.php");
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "No user found with that username!";
        }

        $stmt->close();
        $mysqli->close();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
