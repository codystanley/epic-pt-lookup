



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

document.addEventListener('DOMContentLoaded', function() {
document.getElementById('myForm').addEventListener('submit', function(e) {
    
    e.preventDefault();

    var formData = new FormData(e.target);
    var dob = formData.get('dob');
    var ptLastName = formData.get('ptLastName');
    var ptFirstName = formData.get('ptFirstName');

    fetch('/epicSearch.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json' 
        },
        body: JSON.stringify({ dob, ptLastName, ptFirstName })
    })

    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
});

