<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($email) || empty($password) || empty($password_confirm)) {
        $_SESSION['error'] = "Vyplňte všechny údaje.";
        header("Location: ../register.php");
        exit;
    }
    if ($password !== $password_confirm) {
        $_SESSION['error'] = "Hesla se neshodují.";
        header("Location: ../register.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT id_osoba FROM OSOBY WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Tento e-mail je již zaregistrován.";
        header("Location: ../register.php");
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $pdo->beginTransaction();
        $stmt_osoba = $pdo->prepare("INSERT INTO OSOBY (jmeno, prijmeni, email, telefon, adresa_ulice, adresa_mesto, adresa_psc) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // Sloupce heslo a google_id se plní podle typu přihlášení. U lokálního plníme jen password_hash (a to tak, že přidáme do dotazu)
        $stmt_osoba = $pdo->prepare("INSERT INTO OSOBY (jmeno, prijmeni, email, telefon, adresa_ulice, adresa_mesto, adresa_psc, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_osoba->execute(['Nezadáno', 'Nezadáno', $email, '000000000', 'Nezadáno', 'Nezadáno', '00000', $password_hash]);

        $id_osoba = $pdo->lastInsertId();

        $stmt_uzivatel = $pdo->prepare("INSERT INTO UZIVATELE (id_osoba, datum_registrace, preferovany_jazyk) VALUES (?, NOW(), 'EN')");
        $stmt_uzivatel->execute([$id_osoba]);

        $pdo->commit();

        $_SESSION['user_id'] = $id_osoba;
        header("Location: ../index.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Chyba při registraci: " . $e->getMessage();
        header("Location: ../register.php");
        exit;
    }
} else {
    header("Location: ../register.php");
    exit;
}
?>