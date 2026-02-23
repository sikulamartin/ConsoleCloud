<?php

$host = 'localhost';
$dbname = 'consolecloud';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Zapnutí vyhazování výjimek při chybách v SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Nastavení výchozího formátu dat z DB
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}
?>