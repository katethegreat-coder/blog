<?php

if(isset($_GET['id']) && !empty($_GET ['id'])) {
    $id=$_GET['id'];

require_once('inc/connect.php');

$sql='SELECT `articles`.*, GROUP_CONCAT(`categories`.`name`) as category_name FROM `articles` LEFT JOIN `articles_categories` ON `articles`.`id` = `articles_categories`.`articles_id` LEFT JOIN `categories` ON `articles_categories`.`categories_id` = `categories`.`id` WHERE `articles`.`id` = :id GROUP BY `articles`.`id`;';


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
        <p>publié le <?=date('d/m/Y à H:i', strtotime($article ['created_at']))?> dans
        <?php 
                // display article's categories
                $categories=explode(',', $article['category_name']);
                foreach($categories as $category) {
                echo'<a href="#">'. $category.'</a> ';
                }
        ?>
        </p>
        <div><?=$article ['content'] ?></div>
        <img src="<?=$_FILES['featured_image ']?>" alt="image">
        <div>
            <a href="update_article.php?id=<?= $article['id']?>">update </a>
        </div>
        <div>
            <a href="delete_article.php?id=<?= $article['id']?>"> delete</a>
        </div>
        <a href="<?=$_SERVER['HTTP_REFERER'];?>">Retour</a>
    </article>
</body>
</html>



        
        
                

   
        