<?php

/**
 * This file handes the redirect after the user logs in to Epic from index.php.
 * It retrieves the authorization code and exchanges it for an OAuth2 token.
 * The token is stored in the session.
 * The access token, the refresh token and the id token are also encrytpted and stored in the database.
 * The user is redirected to the intake application.
**/

require_once 'vendor/autoload.php'; // Guzzle loader. Guzzle makes it easier to handle HTTP requests in PHP.
require_once 'config.php';

$config = require 'config.php';

try {
    $db = new PDO(
        'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database_name'],
        $config['database']['username'],
        $config['database']['password']
    );

    /* 1. Fetch Client Credentials from DB*/
    $stmt = $db->prepare("SELECT client_id, client_secret, redirect_uri, token_endpoint FROM epic_clients WHERE id = 1");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $clientId = $row['client_id'];
        $clientSecret = $row['client_secret'];
        $redirectURI = $row['redirect_uri'];
        $tokenEndpoint = $row['token_endpoint'];
    }
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

session_start(); // Start the session

/* Retrieve Authorization Code*/
$authorizationCode = $_POST['code'];  

/* Start Guzzle */
$client = new GuzzleHttp\Client();

/* Token Request */ 
try {
    $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
            'grant_type' => 'authorization_code', 
            'code' => $authorizationCode,
            'redirect_uri' => $redirectURI, 
        ],
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        'auth' => [$clientId, $clientSecret] // Basic Auth
    ]);

    /* Handle Success */

    if ($response->getStatusCode() == 200) {
        // Store the response in an array variable.
        $tokenData = json_decode($response->getBody(), true);
        
        /* -- Debugging -- */
        /*
        echo "<pre>"; 
        print_r($tokenData); 
        echo "</pre>";
        */
        // Store token in the session
        $_SESSION['access_token'] = $tokenData['access_token'];

        // Token encryption for production
        //$method = $config['encryption']['method'];
        //$key = hex2bin($config['encryption']['key']);
        //$iv = hex2bin($config['encryption']['iv']);
        //$encryptedAccessToken = openssl_encrypt($tokenData['access_token'], $method, $key, 0, $iv);
        //$encryptedRefreshToken = openssl_encrypt($tokenData['refresh_token'], $method, $key, 0, $iv);
        //$encryptedIdToken = openssl_encrypt($tokenData['id_token'], $method, $key, 0, $iv);
        
        // Store response in the database
        try {
            $db = new PDO(
                'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database_name'],
                $config['database']['username'],
                $config['database']['password']
            );

            $stmt = $db->prepare("
                INSERT INTO epic_tokens (access_token, token_type, expires_in, scope, state, refresh_token, id_token)
                VALUES (:access_token, :token_type, :expires_in, :scope, :state, :refresh_token, :id_token)
            ");

            /* Unencrypted token storage for debugging*/
            $stmt->bindParam(':access_token', $tokenData['access_token']);
            $stmt->bindParam(':token_type', $tokenData['token_type']);
            $stmt->bindParam(':expires_in', $tokenData['expires_in']);
            $stmt->bindParam(':scope', $tokenData['scope']);
            $stmt->bindParam(':state', $tokenData['state']);
            $stmt->bindParam(':refresh_token', $tokenData['refresh_token']);
            $stmt->bindParam(':id_token', $tokenData['id_token']);

            /* Encrytpted Token Storage for Production
            $stmt->bindParam(':access_token', $encryptedAccessToken);
            $stmt->bindParam(':token_type', $tokenData['token_type']);
            $stmt->bindParam(':expires_in', $tokenData['expires_in']);
            $stmt->bindParam(':scope', $tokenData['scope']);
            $stmt->bindParam(':state', $tokenData['state']);
            $stmt->bindParam(':refresh_token', $encryptedRefreshToken);
            $stmt->bindParam(':id_token', $encryptedIdToken);
            */

            $stmt->execute();

        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }

        // Load the intake application - And we're off!
        header('Location: /intake.php');
        exit;

    } else {
        /* Handle token exchange errors gracefully */

        // Log the error
        error_log("Token exchange failed. HTTP status: " . $response->getStatusCode());
        error_log("Response body: " . $response->getBody()->getContents()); 
    
        // Display an error message to the user (redirect or show a message)
        header('Location: /error.php?message=token_exchange_failed');
    }

/* Handle Guzzle Errors */
}   catch (GuzzleHttp\Exception\ClientException $e) { 
    // Log the error
    error_log("Guzzle error: " . $e->getMessage());
}