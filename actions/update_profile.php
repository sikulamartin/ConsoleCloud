<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vezmeme data z formuláře, pokud jsou prázdná, necháme tam původní zástupné hodnoty (kvůli NOT NULL v DB)
    $jmeno = !empty($_POST['jmeno']) ? trim($_POST['jmeno']) : 'Nezadáno';
    $prijmeni = !empty($_POST['prijmeni']) ? trim($_POST['prijmeni']) : 'Nezadáno';
    $telefon = !empty($_POST['telefon']) ? trim($_POST['telefon']) : '000000000';
    $adresa_ulice = !empty($_POST['adresa_ulice']) ? trim($_POST['adresa_ulice']) : 'Nezadáno';
    $adresa_mesto = !empty($_POST['adresa_mesto']) ? trim($_POST['adresa_mesto']) : 'Nezadáno';
    $adresa_psc = !empty($_POST['adresa_psc']) ? trim($_POST['adresa_psc']) : '00000';

    try {
        $stmt = $pdo->prepare("
            UPDATE OSOBY 
            SET jmeno = ?, prijmeni = ?, telefon = ?, adresa_ulice = ?, adresa_mesto = ?, adresa_psc = ? 
            WHERE id_osoba = ?
        ");
        $stmt->execute([$jmeno, $prijmeni, $telefon, $adresa_ulice, $adresa_mesto, $adresa_psc, $_SESSION['user_id']]);

        $_SESSION['success'] = "Váš profil byl úspěšně aktualizován!";
        header("Location: ../profile.php");
        exit;

    } catch (Exception $e) {
        die("Chyba při ukládání profilu: " . $e->getMessage());
    }
} else {
    header("Location: ../profile.php");
    exit;
}
?>