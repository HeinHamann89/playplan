<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Upload Form - PlayPlan</title>
</head>
<body>
    <h2>Upload Event</h2>
    <form action="upload_event.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>

        <label for="start_date">Start Date:</label><br>
        <input type="date" id="start_date" name="start_date" required><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" required><br>

        <label for="host">Host:</label><br>
        <input type="text" id="host" name="host" required><br>

        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" required><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br>

        <label for="perfect_for">Perfect For:</label><br>
        <input type="text" id="perfect_for" name="perfect_for" required><br>

        <label for="tickets_link">Tickets Link:</label><br>
        <input type="url" id="tickets_link" name="tickets_link"><br>

        <label for="map_url">Map URL:</label><br>
        <input type="url" id="map_url" name="map_url"><br>

        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image"><br><br>

        <input type="submit" value="Upload Event">
    </form>
</body>
</html>
