<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

$config = require 'config.php';

try {
    /* Connect to the database */
    $conn = new PDO(
        'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database_name'],
        $config['database']['username'],
        $config['database']['password']
    );
    
    /* Fetch Client Credentials from DB*/
    $stmt = $conn->prepare("SELECT client_id, redirect_uri, state, scope, auth_endpoint, audience FROM epic_clients WHERE id = 1");
    $stmt->execute();

    /* If the query returns a row, store the row in the $loginConfig variable */
    $loginConfig = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    /* If the connection fails, output the error message */
        die('Database connection failed: ' . $e->getMessage());
}

/* Clear the connection */
$conn = null;
    
/* Output the config as JSON */
header('Content-Type: application/json');
echo json_encode($loginConfig);
?>