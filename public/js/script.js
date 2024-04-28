$(document).ready(function() {
    /**
     * Event handler for the 'change' event of the '#dob' input element.
     * When a date is entered, this function calculates the age based on the entered date.
     * Then the label is updated with the calculated age.
     *
     * @listens #dob:change
     **/

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

    // Patient Search Functionality
    const patientSearchForm = document.getElementById('intakeForm');
    document.getElementById('nextPatientButton').addEventListener('click', epicPatientSearch);

    function epicPatientSearch() {
        fetch ('/epicSearch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dob, lastName, firstName })
        })

        .then(response => {
    //const searchResultsDiv = document.getElementById('searchResults');




});


