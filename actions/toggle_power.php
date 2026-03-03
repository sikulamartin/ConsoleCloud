<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Musíte být přihlášeni.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $id_pronajem = (int) $_POST['id_pronajem'];
    $new_state = (int) $_POST['new_state'];

    try {
        // Zkontrolujeme, zda tento pronájem skutečně patří přihlášenému uživateli
        $stmt_check = $pdo->prepare("SELECT id_pronajem FROM PRONAJMY WHERE id_pronajem = ? AND UZIVATELE_id_osoba = ?");
        $stmt_check->execute([$id_pronajem, $user_id]);

        if ($stmt_check->rowCount() > 0) {
            // Pokud ano, přepneme stav
            $stmt_update = $pdo->prepare("UPDATE PRONAJMY SET power_state = ? WHERE id_pronajem = ?");
            $stmt_update->execute([$new_state, $id_pronajem]);
        }

        // Přesměrujeme zpět na dashboard
        header("Location: ../dashboard.php");
        exit;

    } catch (Exception $e) {
        die("Chyba při ovládání konzole: " . $e->getMessage());
    }
} else {
    header("Location: ../dashboard.php");
    exit;
}
?>