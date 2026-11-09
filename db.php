<?php
// Database connection using SQLite
$db_file = __DIR__ . '/lab_sample.db';

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Automatically create tables if they do not exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'user'
        );

        CREATE TABLE IF NOT EXISTS Samples (
            sample_code TEXT PRIMARY KEY,
            source_location TEXT NOT NULL,
            parameter_name TEXT NOT NULL,
            result_value REAL,
            verification_status TEXT NOT NULL DEFAULT 'Unverified',
            verified_by TEXT
        );
    ");
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>