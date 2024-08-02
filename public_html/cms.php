<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

// Check if the events table exists, if not, create it
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'events'");
    if ($stmt->rowCount() == 0) {
        $sql = "CREATE TABLE events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            start_date DATE NOT NULL,
            location VARCHAR(255),
            host VARCHAR(255),
            category VARCHAR(255),
            description TEXT,
            perfect_for VARCHAR(255),
            tickets_link VARCHAR(255),
            image_url VARCHAR(255),
            map_url VARCHAR(255)
        )";
        $pdo->exec($sql);
        echo "Table 'events' created successfully.<br>";
    }
} catch(PDOException $e) {
    die("Error creating table: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $sql = "INSERT INTO events (title, start_date, location, host, category, description, perfect_for, tickets_link, image_url, map_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_POST['title'], $_POST['start_date'], $_POST['location'], $_POST['host'], $_POST['category'], $_POST['description'], $_POST['perfect_for'], $_POST['tickets_link'], $_POST['image_url'], $_POST['map_url']]);
        } elseif ($_POST['action'] == 'edit') {
            $sql = "UPDATE events SET title=?, start_date=?, location=?, host=?, category=?, description=?, perfect_for=?, tickets_link=?, image_url=?, map_url=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_POST['title'], $_POST['start_date'], $_POST['location'], $_POST['host'], $_POST['category'], $_POST['description'], $_POST['perfect_for'], $_POST['tickets_link'], $_POST['image_url'], $_POST['map_url'], $_POST['id']]);
        } elseif ($_POST['action'] == 'delete') {
            $sql = "DELETE FROM events WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_POST['id']]);
        }
    }
}

// Fetch all events
$stmt = $pdo->query("SELECT * FROM events ORDER BY start_date");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayPlan CMS</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>PlayPlan CMS</h1>
    
    <h2>Add New Event</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="title" placeholder="Title" required>
        <input type="date" name="start_date" required>
        <input type="text" name="location" placeholder="Location">
        <input type="text" name="host" placeholder="Host">
        <input type="text" name="category" placeholder="Category">
        <textarea name="description" placeholder="Description"></textarea>
        <input type="text" name="perfect_for" placeholder="Perfect For">
        <input type="url" name="tickets_link" placeholder="Tickets Link">
        <input type="url" name="image_url" placeholder="Image URL">
        <input type="url" name="map_url" placeholder="Map URL">
        <button type="submit">Add Event</button>
    </form>

    <h2>Events</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Location</th>
            <th>Host</th>
            <th>Description</th>
            <th>Perfect For</th>
            <th>Tickets Link</th>
            <th>Image URL</th>
            <th>Map URL</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($events as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['title']) ?></td>
            <td><?= $event['start_date'] ?></td>
            <td><?= htmlspecialchars($event['location']) ?></td>
            <td><?= htmlspecialchars($event['host']) ?></td>
            <td><?= htmlspecialchars($event['category']) ?></td>
            <td>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
                    <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
                <button onclick="editEvent(<?= $event['id'] ?>)">Edit</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
    function editEvent(id) {
        // Fetch event details and populate a form for editing
        // This is a simplified example; you'd typically use AJAX for this
        alert('Edit event with ID: ' + id);
    }
    </script>
</body>
</html>