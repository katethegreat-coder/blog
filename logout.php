<?php
//Log out erasing all session's data

// Log out the user
session_start();
unset($_SESSION['user']);

// delete remember cookie
setcookie('remember', '', 1);

header('Location: '.$_SERVER['HTTP_REFERER']);