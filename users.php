<?php

if(isset($_GET['id']) && !empty($_GET ['id'])) {
    $id=$_GET['id'];

require_once('inc/connect.php');

$sql='SELECT * FROM `users` WHERE `id`=:id ;';
$query=$db->prepare($sql);
$query->bindvalue(':id', $id, PDO::PARAM_INT);
$query->execute();
$user=$query->fetch(PDO::FETCH_ASSOC);

require_once('inc/close.php');

} else {
    // if no ID, get back to <admin_user class="php"
    header('Location: admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's info</title>
</head>
<body>
    <h1>User <?=$user ['id'] ?> 's info</h1>
    <p> E-mail: <?=$user ['email'] ?></p>
    <p> Password: <?=$user ['password']?></p>
    <a href="<?=$_SERVER['HTTP_REFERER'];?>">Retour</a>
</body>
</html>