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

    $stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $_GET['id'], $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "No event found or you don't have permission to edit this event!";
        exit();
    }

    $stmt->close();

    $updateFields = "title = ?, start_date = ?, location = ?, host = ?, category = ?, description = ?, perfect_for = ?, tickets_link = ?, map_url = ?";
    $bindParams = [$_POST['title'], $_POST['start_date'], $_POST['location'], $_POST['host'], $_POST['category'], $_POST['description'], $_POST['perfect_for'], $_POST['tickets_link'], $_POST['map_url']];
    $types = "sssssssss";

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            throw new Exception("Sorry, there was an error uploading your file.");
        }

        $updateFields .= ", image = ?";
        $bindParams[] = basename($_FILES["image"]["name"]);
        $types .= "s";
    }

    $bindParams[] = $_GET['id'];
    $types .= "i";

    $stmt = $mysqli->prepare("UPDATE events SET $updateFields WHERE id = ?");
    $stmt->bind_param($types, ...$bindParams);

    if ($stmt->execute()) {
        echo "Event updated successfully!";
    } else {
        throw new Exception('Failed to update event: ' . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
