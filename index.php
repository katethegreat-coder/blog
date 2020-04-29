<?php

session_start();
require_once('inc/backto_session.php');
require_once('inc/connect.php');

$sql='SELECT `articles`.*, GROUP_CONCAT(`categories`.`name`) AS category_name FROM `articles` LEFT JOIN `articles_categories` ON `articles`.`id`=`articles_categories`.`articles_id` LEFT JOIN `categories` ON `articles_categories`.`categories_id`=`categories`.`id` GROUP BY `articles`.`id` ORDER BY `created_at` DESC;';
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
    <?php include_once('inc/header.php') ?>
    <h1>Derniers articles</h1>
    <?php
    foreach($articles as $article): ?>
        <article>
            <h2><a href="article.php?id=<?=$article ['id']?>"><?=$article ['title'] ?></a></h2>
            <!-- set up the date format & transform our date in iNT ($timestamp)-->
            <p>publié le <?=date('d/m/Y à H:i', strtotime($article ['created_at']))?> dans 
            <?php 
                // in case of several categories per article, everytime there is a coma, a categgory is added
                $categories=explode(',', $article['category_name']);
                // explode always with a groupconcat: explode the string returned by the GROUP_CONCAT 
                foreach($categories as $category) {
                echo'<a href="#">'. $category.'</a> ';
                }
            ?> 
            </p>
            <!-- display only 300 first digits of the content-->
            <div><?=substr(strip_tags($article ['content']), 0, 300).'...'?></div>
        </article>
    <?php endforeach; ?>
   
</body>
</html> 