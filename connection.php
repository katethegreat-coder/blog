<?php

// check if $_POST exists and is not empty
if(isset($_POST) && !empty($_POST)) {
    // check all fields are filled in
    require_once('inc/lib.php');
    if(verifForm($_POST, ['email', 'password'])) {
        // get fields' values
        $email=strip_tags($_POST['email']);
        $password=$_POST['password'];

        // check if email exists in the DB ...
        require_once('inc/connect.php');
        $sql='SELECT * FROM `users` WHERE `email`= :email;';
        $query=$db->prepare($sql); 
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $users=$query->fetch(PDO::FETCH_ASSOC);

        // does the user exist ? 
        if(!$users) {
            echo 'Invalid e-mail and/or password';
        } else {
            // check if the password is the same as the one saved in the DB
            if (password_verify($password, $users ['password'])){     // the password 1 is password entered, password 2 is the DB one
                echo "You are logged in";
            } else {
                echo "Invalid e-mail and/or password!";
}
        }

    } else {
        echo "All fields must be filled in";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
</head>
<body>
    <h1>Log in</h1>
    <form  method="post">
        <div>
            <label for="mail">e-mail:</label>
            <input type="email" name="email" id="emaikl">
        </div>
        <div>
            <label for="pass">password:</label>
            <input type="password" name="password" id="password">
        </div>
        <button>Log in</button>
    </form>
</body>
</html>
