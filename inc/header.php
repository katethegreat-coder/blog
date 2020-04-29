<?php
// check if user is connected, if $_SESSION exists and is not empty
if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {

    // user connected
?>
    <p>Hello <?= $_SESSION['user']['name']?> <a href="logout.php">Log out</a></p>
    
<?php 
} else {
    // user not connected
?>
    <p><a href="connection.php">Log in</a><a href="registration.php">Sign in</a></p>
<?php
}


