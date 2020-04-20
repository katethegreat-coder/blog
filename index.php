<?php
 
require_once('inc/connect.php');

$sql='SELECT * FROM `articles` ORDER BY `created_at` DESC ;';
$query=$db->query($sql);
$articles=$query->fetchAll(PDO::FETCH_ASSOC);           // always a foreach after

require_once('inc/close.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <h1>Derniers articles</h1>
    <?php
    foreach($articles as $article): ?>
        <article>
            <h2><a href="article.php?id=<?=$article ['id']?>"><?=$article ['title'] ?></a></h2>
            <!-- set up the date format & transform our date in iNT ($timestamp)-->
            <p>publié le <?=date('d/m/Y à H:i', strtotime($article ['created_at'])) ?></p>
            <!-- display only 300 first digits of the content-->
            <div><?=substr(strip_tags($article ['content']), 0, 300).'...' ?></div>
        </article>
    <?php endforeach; ?>
   
</body>
</html>