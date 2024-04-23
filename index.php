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
<?php
include 'footer.php';
?>