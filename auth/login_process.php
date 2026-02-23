<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Zadejte e-mail i heslo.";
        header("Location: ../login.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id_osoba, password_hash FROM OSOBY WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Uživatelé z Googlu nemusí mít heslo, proto kontrolujeme, zda hash vůbec existuje
        if ($user && $user['password_hash'] && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id_osoba'];
            header("Location: ../index.php"); 
            exit;
        } else {
            $_SESSION['error'] = "Nesprávný e-mail nebo heslo. (Pokud jste se registrovali přes Google, použijte Google přihlášení).";
            header("Location: ../login.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Chyba databáze: " . $e->getMessage();
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>