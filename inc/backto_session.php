<?php

// restore the session if needed, check first if cookie exists
if(isset($_COOKIE['remember']) && !empty($_COOKIE['remember'])) {
    // find the user with the cookie's token
    require_once('connect.php');
    $sql='SELECT * FROM `users` WHERE `remember_token`= :token;';
    $query=$db->prepare($sql);
    $query->bindValue(':token', $_COOKIE['remember'], PDO::PARAM_STR);
    $query->execute();
    $user=$query->fetch(PDO::FETCH_ASSOC);

    if($user){
        $_SESSION['user']=[
            'id' => $user['id'],
            'email' => $user['email'], 
            'name' => $user['name']
        ];
    }
}