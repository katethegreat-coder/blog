<?php

session_start();

// Use PHPMailer, import PHPMailer files ALWAYS IN THE BEGINNING OF THE FILE
require_once('PHPMailer/Exception.php');
require_once('PHPMailer/PHPMailer.php');
require_once('PHPMailer/SMTP.php');

// PHPMailer is in PHP POO
// Call Exception and PHPMailer classes
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

//check if we have received an email in the post
if(isset($_POST['email']) && !empty($_POST['email'])) {
    $email=strip_tags($_POST['email']);

    // check if email exists in the DB ...
    require_once('inc/connect.php');
    $sql='SELECT * FROM `users` WHERE `email`= :email;';
    $query=$db->prepare($sql); 
    $query->bindValue(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $user=$query->fetch(PDO::FETCH_ASSOC);

    // does the user exist ? 
    if(!$user) {
        $_SESSION['message']= 'Unregistered e-mail';
    } else {
        // the email exists, the token is generated
        $token=md5(uniqid());
       // stock the token into the DB^
        $sql="UPDATE `users` SET `reset_token`= '$token' WHERE `id`=".$user['id'];
        $query=$db->query($sql);
        
        require_once('inc/close.php');

        // Instantiate PHPMailer
        $email= new PHPMailer();

        // Config PHPMailer, using SMTP
        $email->isSMTP();

        // Define SMTP server
        $email->Host = 'localhost';

        // Define the port
        $email->Port = 1025;

        // Allow spcials characters
        $email->CharSet= 'utf-8';

        // Attempt to send an email
        try {
            // from ...
            $email->setFrom('aureliedutrey@hotmail.com', 'Blog Blablah');
            // to ...
            $email->addAddress($user['email'], $user['name']);
            // object
            $email->Subject = 'Password reset link';
            // content in HTML
            $email->isHTML();
            $email->Body= '
                    <h1> Password reset</h1>
                    <p>If you requested a password reset, please find below the link to reset your password:</p>
                    <a href="http://localhost/blog/password_reset.php?token='.$token.'">http://localhost/blog/password_reset.php?token='.$token.'</a>
                    <p>If the link does not work, please copy the link and copy it n your browser</p>
                    ';
            // send the email
            $email->send();

        } catch(Exception $e) {
            echo $e->errorMessage();
        }

        $_SESSION['message']= 'An email was sent to you with the password reset link';
        header('Location:index.php');
    }
}
        
                   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgotten password</title>
</head>
<body>
    <h1>Forgotten password?</h1>
    <p>Enter your email, once we have checked that your are registered, you will receive by email a password reset link.</p>
    <p>See you in a bit!</p>
    <form  method="post">
        <div>
            <label for="mail">e-mail:</label>
            <input type="email" name="email" id="email">
        </div>
        <button>Password reset</button>
    </form>
</body>
</html>