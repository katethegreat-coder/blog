<?php

// the user signs up

if(isset($_POST) && !empty($_POST)) {

    // connect to the lib file
    require_once('inc/lib.php');

    // call the function to check if fields are filled in
    if(verifForm($_POST, ['email','password'])) {

        // prevent XSS vulnerabilities et get variables' content
        $email=strip_tags($_POST['email']);

        // get the password and encrypt it
        $password=password_hash($_POST['password'], PASSWORD_ARGON2I);

        // connect to the DB
        require_once('inc/connect.php');
        
        // write the query
        $sql='INSERT INTO `users`(`email`, `password`) VALUES (:email, :password);';        //:email => SQL variable

        // prepare the query
        $query=$db->prepare($sql);

        // inject values
        $query ->bindValue(':email', $email, PDO::PARAM_STR);
        $query ->bindValue(':password', $password, PDO::PARAM_STR);

        // execute the query
        $query->execute();

        // disconnect from the DB
        require_once('inc/close.php');

        // redirect to categories' list
        header('Location: index.php');

    } else {
        echo 'You should fill in all fields!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up here!</h1>
    <form method="post">
        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email">            <!--   better choose different words from DB entries -->
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
        </div>
        <button>Register</button>
    </form>
</body>
</html>