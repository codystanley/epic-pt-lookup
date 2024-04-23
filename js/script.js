/* Functionality for the Epic OAuth2 Authorization Code Flow */
/* --------------------------------------------------------- */

// Event listener for the 'click' event of the '#initiateEpicLogin' button
document.getElementById('initiateEpicLogin').addEventListener('click', initiateEpicLogin);

// Function to initiate the Epic OAuth2 Authorization Code Flow
async function initiateEpicLogin() {
    const response = await fetch('epicLogin.php');
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



$(document).ready(function() {
    /**
     * Event handler for the 'change' event of the '#dob' input element.
     * When a date is entered, this function calculates the age based on the entered date.
     * Then the label is updated with the calculated age.
     *
     * @listens #dob:change
     */

    $("#dob").change(function() {
        // Get the entered date
        var dob = new Date($(this).val());

        // Calculate the age
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        // Update the label
        $("label[for='dob']").text("Date of Birth* (Age: " + age + ")");
    });
});


// Patient Search Functionality
$(document).ready(function(){
    $("#nextPatientButton").click(function(e){
        e.preventDefault();

        $.ajax({
            url: 'epicSearch.php', // The PHP script that interacts with the EPIC API
            type: 'post',
            success: function(response){
                // Display the results in the modal
                $("#modalContent").html(response);

                // Show the modal
                $("#myModal").show();
            }
        });
    });

    // When the user clicks on <span> (x), close the modal
    $(".close").click(function() {
        $("#myModal").hide();
    });
});
