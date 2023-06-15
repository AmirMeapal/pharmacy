<?php

require_once 'vendor/autoload.php';

$clientID = '642288698810-lir1tdk154vclt1g378v2tjunlifeqia.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/pharmacy/index.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$dsn = 'mysql:host=localhost;dbname=login_db';
$user = 'root';
$pass = '';

try { 
    $pdo = new PDO($dsn, $user, $pass);
    
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}