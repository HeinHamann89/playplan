<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$event = [
    'id' => '',
    'title' => '',
    'start_date' => '',
    'location' => '',
    'host' => '',
    'category' => '',
    'description' => '',
    'perfect_for' => '',
    'tickets_link' => '',
    'map_url' => '',
    'image' => ''
];

if (isset($_GET['id'])) {
    // Fetch event details if editing
    try {
        $mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

        if ($mysqli->connect_error) {
            throw new Exception('Database connection failed: ' . $mysqli->connect_error);
        }

        $stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $_GET['id'], $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $event = $result->fetch_assoc();
        } else {
            echo "Event not found or you don't have permission to edit this event!";
            exit();
        }

        $stmt->close();
        $mysqli->close();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Event - PlayPlan</title>
</head>
<body>
    <h2><?php echo $event['id'] ? 'Edit' : 'Submit'; ?> Event</h2>
    <form action="submit_event.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required><br>

        <label for="start_date">Start Date:</label><br>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($event['start_date']); ?>" required><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required><br>

        <label for="host">Host:</label><br>
        <input type="text" id="host" name="host" value="<?php echo htmlspecialchars($event['host']); ?>" required><br>

        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($event['category']); ?>" required><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br>

        <label for="perfect_for">Perfect For:</label><br>
        <input type="text" id="perfect_for" name="perfect_for" value="<?php echo htmlspecialchars($event['perfect_for']); ?>" required><br>

        <label for="tickets_link">Tickets Link:</label><br>
        <input type="url" id="tickets_link" name="tickets_link" value="<?php echo htmlspecialchars($event['tickets_link']); ?>"><br>

        <label for="map_url">Map URL:</label><br>
        <input type="url" id="map_url" name="map_url" value="<?php echo htmlspecialchars($event['map_url']); ?>"><br>

        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image"><br><br>

        <input type="submit" value="<?php echo $event['id'] ? 'Update' : 'Submit'; ?> Event">
    </form>
</body>
</html>
