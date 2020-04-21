<?php

if(isset($_GET['id']) && !empty($_GET ['id'])) {
    $id=$_GET['id'];

require_once('inc/connect.php');

$sql='SELECT * FROM `articles` WHERE `id`=:id ;';
$query=$db->prepare($sql);
$query->bindvalue(':id', $id, PDO::PARAM_INT);
$query->execute();
$article=$query->fetch(PDO::FETCH_ASSOC);

require_once('inc/close.php');

if(!$article) {                         // if article is not available  
    echo "We could not find the article";
    die;
}

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
    <title><?=$article ['title'] ?></title>
</head>
<body>
    <article>
        <h1><?=$article ['title'] ?></h1>
        <p>publié le <?=date('d/m/Y à H:i', strtotime($article ['created_at']))?></p>
        <div><?=$article ['content'] ?></div>
        <a href="<?=$_SERVER['HTTP_REFERER'];?>">Retour</a>
    </article>
</body>
</html>