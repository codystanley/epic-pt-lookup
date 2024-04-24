<?php
require_once 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Patient Search Logic (When the form is submitted)
function performPatientSearch($searchParams) { // A function to handle searches with varied parameters
    error_log(print_r($searchParams, true));

    $baseUrl = 'https://fhir.epic.com/interconnect-fhir-oauth/api/FHIR/R4/Patient';
    $client = new GuzzleHttp\Client();

    try {
        $response = $client->request('GET', $baseUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $_SESSION['access_token'],
                'Accept' => "application/json+fhir"
            ],
            'query' => $searchParams
        ]);

        if ($response->getStatusCode() == 200) {
            $searchResults = json_decode($response->getBody(), true);

            // Display the search results (basic example - you'll enhance this)
            // Display the search results 
            echo "<h2>Search Results:</h2>";

            if ($searchResults['total'] > 0) {
                $patient = $searchResults['entry'][0]['resource'];

                echo "<table>";
                echo "<tr><th>MRN</th><td>" . $patient['identifier'][5]['value'] . "</td></tr>";
                echo "<tr><th>Last Name</th><td>" . $patient['name'][0]['family'] . "</td></tr>";
                echo "<tr><th>First Name</th><td>" . $patient['name'][0]['given'][0] . "</td></tr>";
                echo "<tr><th>Date of Birth</th><td>" . $patient['birthDate'] . "</td></tr>";
                echo "<tr><th>Gender</th><td>" . $patient['gender'] . "</td></tr>";
                echo "<tr><th>State</th><td>" . $patient['address'][0]['state'] . "</td></tr>";
                echo "</table>";

            } else { // Handle no returned patients.
                echo "<p>No patients found.</p>";
            }

        } else { // Handle API request error (more specific error display later)
            echo "<p>Search failed. Please contact support.</p>"; 
        }

    } catch (GuzzleHttp\Exception\ClientException $e) {
        // Check if token expired by looking for 401 Unauthorized
        if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
            // Token likely expired - Redirect to login
            header('Location: /index.php');
            exit; 
        } else {
            // Handle other Guzzle errors  
            echo "<p>An error occurred. Please contact support.</p>";
        }
    }
}

// Get the POST body
$postData = file_get_contents('php://input');

// Parse the JSON object
$data = json_decode($postData, true);

if (isset($data['dob']) && isset($data['ptLastName']) && isset($data['ptFirstName'])) {
    $birthdate = $data['dob'];
    $lastName = $data['ptLastName'];
    $firstName = $data['ptFirstName'];
    $searchParams = [
        'given' => $firstName,  
        'family' => $lastName,
        'birthdate' => $birthdate
    ];

    performPatientSearch($searchParams);
} else {
    echo "<p>Search parameters missing. Please try again.</p>";
}

?>