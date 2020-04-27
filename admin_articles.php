<?php

require_once('inc/connect.php');                            // connect to the DB

$sql='SELECT * FROM `articles` ORDER BY `title` ASC ;';    // write the query
$query=$db->query($sql);                                   // query method
$articles=$query->fetchAll(PDO::FETCH_ASSOC);            // fetch datas

require_once('inc/close.php');                             // disconnect from the DB

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog's articles'</title>
</head>
<body>
    <h1>Articles list</h1>
    <table>
        <thead>
            <th>ID</th>
            <th>Title</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <?php
                foreach($articles as $article):?>
                    <tr>     
                        <td><?=$article['id']?></td>    
                        <td><a href="article.php?id=<?=$article['id']?>"><?=$article['title']?></td>
                        <td>
                            <a href="update_article.php?id=<?=$article['id']?>">update </a> 
                            <a href="delete_article.php?id=<?=$article['id']?>"> delete</a>
                        </td>
                    </tr> 
                <?php endforeach; ?>
        </tbody>
    </table>
    <a href="new_article.php">Add an article</a>
</body>
</html>