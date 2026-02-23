<?php
session_start();
require '../vendor/autoload.php';

// SEM VLOŽ SVÉ ÚDAJE Z GOOGLE CLOUD CONSOLE
$clientID = '340250586116-pjobkmr668gqn3cbfvq3ku8q3lc5n0hu.apps.googleusercontent.com';
$clientSecret = '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';
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