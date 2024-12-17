<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'goals';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Deleting all data from the progress_log table
    $stmt = $pdo->prepare("TRUNCATE TABLE progress_log");
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
