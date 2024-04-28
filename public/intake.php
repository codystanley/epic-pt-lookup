<?php
require_once 'vendor/autoload.php';

session_start();

if (isset($_SESSION['access_token'])) {
    
    /* If we have a token, render the search form */
    include 'header.php';
?>

<div class="container rounded-3" style="margin-top: 3rem; margin-bottom: 3rem; border: 0.2rem solid #0968b8;">

        <form id="intakeForm">
            <fieldset>
                <div class="row py-2" id="formHeaderRow">
                    <legend class="col-auto me-auto">Search for a Patient</legend>
                    <div class="col-auto" >
                        <!-- Future: Add a badge to show the age of the current token -->
                        <?php
                        echo '<div class="badge bg-success text-truncate" style="max-width: 200px">Logged In: ' . $_SESSION['access_token'] . '</div>';
                        ?>
                    </div>
                </div>

                <!-- First Row Inputs -->
                <div class="row row-cols-1 row-cols-lg-5 gy-2 py-2 align-items-center" id="formRow1" style="margin-top: 1rem;">
                    
                    <div class="col form-group" id="phoneDiv">
                        <label for="phone">Phone*</label>
                        <input type="tel" class="form-control" id="phone" value="123-123-1234" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="000-000-0000" required>
                    </div><!-- #phoneDiv -->
                    
                    <!-- <div class="col form-group" id="nextPhoneButtonDiv"> -->
                        <!-- <button type="submit" class="btn btn-primary standard-blue" id="nextPhoneButton">Next</button> -->
                    <!-- </div> --><!-- nextButtonDiv -->

                    <div class="col form-group" id="dobDiv">
                        <label for="dob">Date of Birth* (Age: )</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div><!-- #dobDiv -->

                    <div class="col form-group" id="ptLastNameDiv">
                        <label for="ptLastName">Patient Last Name*</label>
                        <input type="text" class="form-control" id="ptLastName" name="ptLastName" required>
                    </div><!-- ptLastNameDiv -->

                    <div class="col form-group" id="ptFirstNameDiv">
                        <label for="ptFirstName">Patient First Name*</label>
                        <input type="text" class="form-control" id="ptFirstName" name="ptFirstName" required>
                    </div><!-- ptFirstNameDiv -->

                    <div class="col form-group" id="nextPatientButtonDiv">
                        <button type="submit" class="btn btn-primary standard-blue" id="nextPatientButton" >Next</button>
                    </div><!-- nextPatientButtonDiv -->

                    <div class="col form-group" id="ptBiologicalSexDiv" style="display: none;">
                        <label for="sex">Biological Sex</label>
                        <select class="form-control" id="ptBiologicSex">
                            <option value="">Select</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div><!-- ptBiologicalSexDiv-->

                </div><!-- #formRow1 -->

<!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalContent"></p>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>

<?php 
    



} else {
    /* If we don't have a token, redirect to the login page */
    header('Location: /index.php');
};
?>