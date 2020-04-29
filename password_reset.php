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

    if (isset ($_GET['token']) && !empty($_GET['token'])){
        require_once('inc/connect.php');
        $token= strip_tags($_GET['token']);
        $sql='SELECT * FROM `users` WHERE `reset_token`= :token;';
        $query=$db->prepare($sql);
        $query ->bindValue(':token', $token, PDO::PARAM_STR);
        $query->execute();
        $user=$query->fetch(PDO::FETCH_ASSOC);

            if(!$user){
                $_SESSION['error']='The password reset link was already used';
                header('Location:connection.php');
            } else {
                require_once('inc/lib.php');

                if(verifForm($_POST, ['password', 'passwordbis'])) {
                    $password=$_POST['password']; 
                    $passwordbis=$_POST['passwordbis'];

                    if ($password==$passwordbis) {
                        $newpassword=password_hash($password, PASSWORD_ARGON2I);
                        $sql="UPDATE `users`SET `password`='$newpassword', `reset_token`=null WHERE `id`=".$user['id'];
                        $query=$db->query($sql);

                        // count Updated fields
                        // die("Updated fields: ".$query->rowCount());

                        // Send a confirmation e-mail
                        $email= new PHPMailer();
                        $email->isSMTP();
                        $email->Host = 'localhost';
                        $email->Port = 1025;
                        $email->CharSet= 'utf-8';

                        try {
                            $email->setFrom('aureliedutrey@hotmail.com', 'Blog Blablah');
                            $email->addAddress($user['email'], $user['name']);
                            $email->Subject = 'Password modified';
                            $email->isHTML();
                            $email->Body= '
                                    <h1> Password reset</h1>
                                    <p>You password was successfully modified</p>
                                    ';
                            $email->send();

                        } catch(Exception $e) {
                            echo $e->errorMessage();
                        }

                        $_SESSION['message']='Your password was successfully modified';
                        header('Location:connection.php');

                    }else {
                        $_SESSION['error']='Passwords do not match!';
                        header('Location: '.$_SERVER['HTTP_REFERER']); 
                        die; 
                    }
                }
            }

        } else  {
            $_SESSION['error']='The Account does not exist';
            header('Location:connection.php');
        }    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password reset</title>
</head>
<body>
    <h1>Password reset</h1>
    <?php if(isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    ?>
        <div style="color:red; font-weight:bold"><?=$_SESSION['error']?></div>
  
    <?php 
        unset($_SESSION['error']); 
    }
    ?>
        <form  method="post">
            <div>
                <label for="password">password:</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="passwordbis">Confirm the password:</label>
                <input type="password" name="passwordbis" id="passwordbis">
            </div>
            <button>Password reset</button>
        </form>
</body>
</html>