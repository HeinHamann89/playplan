<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

$mysqli = new mysqli("localhost", "u899626571_playplan_admin", "Gold3nm4c!", "u899626571_playplan_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $id = $_POST['id'];
        $mysqli->query("UPDATE events SET approval_status = 'approved' WHERE id = $id");
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $mysqli->query("DELETE FROM events WHERE id = $id");
    } else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $start_date = $_POST['start_date'];
        $location = $_POST['location'];
        $host = $_POST['host'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $perfect_for = $_POST['perfect_for'];
        $tickets_link = $_POST['tickets_link'];
        $map_url = $_POST['map_url'];

        // Handle file upload
        $image = ''; // Default to empty in case no file is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '/Applications/XAMPP/htdocs/playplan_admin/uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $image = $newFileName;
            } else {
                $message = 'There was an error moving the uploaded file to the destination directory.';
                echo $message;
            }
        }

        if ($id) {
            // If editing an event, keep the old image if no new image is uploaded
            if ($image == '') {
                $stmt = $mysqli->prepare("SELECT image FROM events WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->bind_result($existing_image);
                $stmt->fetch();
                $stmt->close();
                $image = $existing_image;
            }

            $stmt = $mysqli->prepare("UPDATE events SET title=?, start_date=?, location=?, host=?, category=?, description=?, perfect_for=?, tickets_link=?, image=?, map_url=? WHERE id=?");
            $stmt->bind_param("ssssssssssi", $title, $start_date, $location, $host, $category, $description, $perfect_for, $tickets_link, $image, $map_url, $id);
        } else {
            $stmt = $mysqli->prepare("INSERT INTO events (title, start_date, location, host, category, description, perfect_for, tickets_link, image, map_url, approval_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("ssssssssss", $title, $start_date, $location, $host, $category, $description, $perfect_for, $tickets_link, $image, $map_url);
        }
        $stmt->execute();
        $stmt->close();
    }
}

$results = $mysqli->query("SELECT * FROM events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" id="event_id">
        <label>Title: <input type="text" name="title" id="title"></label><br>
        <label>Date: <input type="date" name="start_date" id="start_date"></label><br>
        <label>Location: <input type="text" name="location" id="location"></label><br>
        <label>Host: <input type="text" name="host" id="host"></label><br>
        <label>Category: <input type="text" name="category" id="category"></label><br>
        <label>Description: <textarea name="description" id="description"></textarea></label><br>
        <label>Perfect For: <input type="text" name="perfect_for" id="perfect_for"></label><br>
        <label>Tickets Link: <input type="text" name="tickets_link" id="tickets_link"></label><br>
        <label>Image: <input type="file" name="image" id="image"></label><br>
        <label>Map URL: <input type="text" name="map_url" id="map_url"></label><br>
        <input type="submit" name="edit" value="Save">
    </form>
    <hr>
    <h2>Events</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date</th>
            <th>Location</th>
            <th>Host</th>
            <th>Category</th>
            <th>Description</th>
            <th>Perfect For</th>
            <th>Tickets Link</th>
            <th>Image</th>
            <th>Map URL</th>
            <th>Approval Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $results->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['start_date']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><?php echo $row['host']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['perfect_for']; ?></td>
            <td><?php echo $row['tickets_link']; ?></td>
            <td><img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" width="100"></td>
            <td><?php echo $row['map_url']; ?></td>
            <td><?php echo $row['approval_status']; ?></td>
            <td>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="submit" name="approve" value="Approve">
                </form>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="submit" name="delete" value="Delete">
                </form>
                <button onclick="editEvent(<?php echo $row['id']; ?>, '<?php echo $row['title']; ?>', '<?php echo $row['start_date']; ?>', '<?php echo $row['location']; ?>', '<?php echo $row['host']; ?>', '<?php echo $row['category']; ?>', '<?php echo addslashes($row['description']); ?>', '<?php echo $row['perfect_for']; ?>', '<?php echo $row['tickets_link']; ?>', '<?php echo $row['image']; ?>', '<?php echo $row['map_url']; ?>')">Edit</button>
            </td>
        </tr>
        <?php } ?>
    </table>

    <script>
    function editEvent(id, title, start_date, location, host, category, description, perfect_for, tickets_link, image, map_url) {
        document.getElementById('event_id').value = id;
        document.getElementById('title').value = title;
        document.getElementById('start_date').value = start_date;
        document.getElementById('location').value = location;
        document.getElementById('host').value = host;
        document.getElementById('category').value = category;
        document.getElementById('description').value = description;
        document.getElementById('perfect_for').value = perfect_for;
        document.getElementById('tickets_link').value = tickets_link;
        // Note: The image field remains empty as we can't pre-populate file inputs for security reasons.
        document.getElementById('map_url').value = map_url;
    }
    </script>
</body>
</html>
