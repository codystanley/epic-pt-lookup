/* Functionality for the Epic OAuth2 Authorization Code Flow */
/* --------------------------------------------------------- */

// Event listener for the 'click' event of the '#initiateEpicLogin' button


document.getElementById('initiateEpicLogin').addEventListener('click', initiateEpicLogin);

// Function to initiate the Epic OAuth2 Authorization Code Flow
async function initiateEpicLogin() {
    const response = await fetch('../epicLogin.php');
    const config = await response.json();

    const clientId = config.client_id;
    const authorizationBaseUrl = config.auth_endpoint;
    const redirectUri = config.redirect_uri;
    const scopes = config.scope;
    const audience = config.audience;
    const state = config.state;

    // Contruct the Authorization URL
    const authorizationUrl = `${authorizationBaseUrl}?response_type=code&redirect_uri=${encodeURIComponent(redirectUri)}&client_id=${clientId}&scope=${scopes}&aud=${encodeURIComponent(audience)}&state=${state}`; 
    
    // Direct user to Epic for authorization login
    window.location.href = authorizationUrl;
}

//Find the code and save it in a variable
const urlParams = new URLSearchParams(window.location.search);
const authorizationCode = urlParams.get('code'); 

// If the code is found, update the hidden form with the code and submit it to our server
if (authorizationCode) { 

    //Update hidden form with code and submit it to our server for use by callback.php
    document.getElementById('codeForm').elements['code'].value = authorizationCode;
    document.getElementById('codeForm').submit();
}