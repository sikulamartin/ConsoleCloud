<?php
session_start();
require '../vendor/autoload.php';
require '../config/secrets.php'; // Načtení našich tajných klíčů

$clientID = GOOGLE_CLIENT_ID;
$clientSecret = GOOGLE_CLIENT_SECRET;
$redirectUri = 'http://localhost/consolecloud/auth/google_callback.php';


// Inicializace Google Klienta
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

// Chceme z profilu získat email a základní údaje (jméno)
$client->addScope("email");
$client->addScope("profile");

// Vygenerování URL adresy pro přihlášení a přesměrování na ni
$loginUrl = $client->createAuthUrl();
header("Location: " . $loginUrl);
exit;
?>