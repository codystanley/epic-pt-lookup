<?php
require 'vendor/autoload.php';
require 'config.php';
include 'header.php';
?>
        <section class="row m-3" id="loginSection">
            <div class="col-12" id="loginButtonDiv">

                <button class="btn btn-primary" id="initiateEpicLogin">Login with Epic</button> 

                <form id="codeForm" action="/callback.php" method="POST" style="display: none;"> 
                    <input type="hidden" name="code" /> 
                </form>

            </section><!-- #loginButtonDiv -->
        </div><!-- #loginSection -->
    </div> <!-- #main -->
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/login.js"></script>
</body>
</html>
