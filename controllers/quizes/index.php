<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="css/firstpage.css">
    <link rel="stylesheet" href="css/sidebar.css">
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

<?php if (isset($_SESSION['username'])): ?>
    <!-- Logged in user with sidebar -->
    <!-- Menu Button - Left Side -->
    <div class="sidebar-trigger">
        <button id="menuToggle" class="menu-toggle">
            ☰
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Sidebar - Left Side -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3>Menu</h3>
            <button id="closeSidebar" class="close-sidebar">&times;</button>
        </div>
        
        <div class="user-info-sidebar">
            <div class="user-name-sidebar">
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </div>
            <div class="user-role-sidebar">
                <?php echo $_SESSION['role'] === 'admin' ? 'Administrator' : 'User'; ?>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="/profile" class="sidebar-menu-item">
                <span class="icon">🖼️</span>
                <span class="text">My Profile</span>
            </a>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/admin" class="sidebar-menu-item">
                    <span class="icon">⚙️</span>
                    <span class="text">Admin Panel</span>
                </a>
            <?php endif; ?>
            
            <div class="sidebar-divider"></div>
            
            <a href="/logout" class="sidebar-menu-item logout-item">
                <span class="icon">🚪</span>
                <span class="text">Logout</span>
            </a>
        </div>
    </div>

    <div class="wrapper">
        <div class="box1">
            <div class="col1">
               

<?php else: ?>
    <!-- Not logged in user -->
    <div class="wrapper">
        <div class="box">
            <div class="col">
                <h2 class="title">Welcome to Quiz</h2>
                <input type="submit" onclick="document.getElementById('id01').style.display='block'" value="Log in">
                <input type="submit" onclick="document.getElementById('id02').style.display='block'" value="Sign up">
            </div> 

            <!-- Login Modal -->
            <div id="id01" class="modal">
                <form class="modal-content animate" action="/login" method="post">
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
                <form class="modal-content animate" action="/register" method="post">
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
<?php endif; ?>

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
<script src="js/sidebar.js"></script>
</body>
</html>