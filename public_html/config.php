<?php
$host = 'localhost';
$dbname = 'playplan_cms';
$username = 'root';
$password = 'root';
$port = 8889; // MAMP default MySQL port

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>