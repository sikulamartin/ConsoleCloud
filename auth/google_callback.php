<?php
session_start();
require '../config/db.php';
require '../vendor/autoload.php';
require '../config/secrets.php';

$clientID = GOOGLE_CLIENT_ID;
$clientSecret = GOOGLE_CLIENT_SECRET;
$redirectUri = 'http://localhost/consolecloud/auth/google_callback.php';


$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

// Pokud se uživatel úspěšně vrátil z Googlu s kódem
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        die("Chyba při získávání tokenu z Googlu.");
    }

    $client->setAccessToken($token['access_token']);

    // Získání informací o profilu uživatele
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    $google_id = $google_account_info->id;
    $email = $google_account_info->email;
    $jmeno = $google_account_info->givenName ?: 'Nezadáno';
    $prijmeni = $google_account_info->familyName ?: 'Nezadáno';

    try {
        // Kontrola, zda uživatel už existuje podle google_id NEBO podle emailu
        $stmt = $pdo->prepare("SELECT id_osoba FROM OSOBY WHERE google_id = ? OR email = ?");
        $stmt->execute([$google_id, $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Uživatel existuje -> Přihlásíme ho
            // Pokud měl dřív účet jen přes heslo, updatneme mu tam google_id, ať ho má propojený
            $stmt_update = $pdo->prepare("UPDATE OSOBY SET google_id = ? WHERE id_osoba = ?");
            $stmt_update->execute([$google_id, $user['id_osoba']]);

            $_SESSION['user_id'] = $user['id_osoba'];
        } else {
            // Uživatel neexistuje -> Automaticky ho zaregistrujeme
            $pdo->beginTransaction();

            $stmt_insert = $pdo->prepare("INSERT INTO OSOBY (jmeno, prijmeni, email, telefon, adresa_ulice, adresa_mesto, adresa_psc, google_id) VALUES (?, ?, ?, '000000000', 'Nezadáno', 'Nezadáno', '00000', ?)");
            $stmt_insert->execute([$jmeno, $prijmeni, $email, $google_id]);
            $new_user_id = $pdo->lastInsertId();

            // Přidáme i záznam do tabulky UZIVATELE
            $stmt_uzivatel = $pdo->prepare("INSERT INTO UZIVATELE (id_osoba, datum_registrace, preferovany_jazyk) VALUES (?, NOW(), 'EN')");
            $stmt_uzivatel->execute([$new_user_id]);

            $pdo->commit();

            // Rovnou ho přihlásíme
            $_SESSION['user_id'] = $new_user_id;
        }

        // Vše proběhlo ok, pošleme ho na domovskou stránku
        header("Location: ../index.php");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Chyba databáze: " . $e->getMessage());
    }
} else {
    // Pokud někdo vleze na script napřímo bez Googlu
    header("Location: ../login.php");
    exit;
}
?>