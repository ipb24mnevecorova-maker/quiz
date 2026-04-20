<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en" lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="css/firstpage.css">
</head>

<body>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?php 
        echo $_SESSION['error']; 
        unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php 
        echo $_SESSION['success']; 
        unset($_SESSION['success']);
        ?>
    </div> 
<?php endif; ?>

    <div class="wrapper">
        <div class="box">
            <div class="col">
                <h2 class="title">Welcome to Quiz</h2>
                <input type="submit" onclick="document.getElementById('id01').style.display='block'" value="Log in">
                <input type="submit" onclick="document.getElementById('id02').style.display='block'" value="Sign up">
            </div> 

            <!-- Login Modal -->
            <div id="id01" class="modal">
                <form class="modal-content animate" action="login.php" method="post">
                    <div class="imgcontainer">  
                        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close">&times;</span>
                    </div>

                    <div class="container">
                        <label for="username" class="userandpasw"><b>Username</b></label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="password" class="userandpasw"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="password" required>
                        
                        <button type="submit" class="btnpas">Login</button>
                    </div>
                </form>
            </div>

            <!-- Signup Modal -->
            <div id="id02" class="modal"> 
                <form class="modal-content animate" action="register.php" method="post">
                    <div class="imgcontainer">  
                        <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close">&times;</span>
                    </div>

                    <div class="container">
                        <p class="userandpasw">Please fill in this form to create an account.</p>
                        <hr>
                        
                        <label for="username" class="userandpasw"><b>Username</b></label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="password" class="userandpasw"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="password" required>

                        <label for="confirm_password" class="userandpasw"><b>Repeat Password</b></label>
                        <input type="password" placeholder="Repeat Password" name="confirm_password" required>

                        <div class="clearfix">
                            <button type="submit" class="signupbtn btnpas">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
// Get the modals
var modal1 = document.getElementById('id01');
var modal2 = document.getElementById('id02');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal1) {
        modal1.style.display = "none";
    }
    if (event.target == modal2) {
        modal2.style.display = "none";
    }
}
    </script> 
</body>
</html>