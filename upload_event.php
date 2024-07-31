<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }
}

// Check file size
if ($_FILES["image"]["size"] > 500000) {
    die("Sorry, your file is too large.");
}

// Allow certain file formats
if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
) {
    die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
}

// Check if $uploadOk is set to 0 by an error
if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    die("Sorry, there was an error uploading your file.");
}

try {
    $mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

    // Check for connection errors
    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO events (title, start_date, location, host, category, description, perfect_for, tickets_link, map_url, image, approval_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssssss",
        $_POST['title'],
        $_POST['start_date'],
        $_POST['location'],
        $_POST['host'],
        $_POST['category'],
        $_POST['description'],
        $_POST['perfect_for'],
        $_POST['tickets_link'],
        $_POST['map_url'],
        basename($_FILES["image"]["name"]),
        $approval_status
    );

    $approval_status = 'pending'; // Set initial status to pending

    if ($stmt->execute()) {
        echo "New event uploaded successfully!";
    } else {
        throw new Exception('Failed to insert event: ' . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
