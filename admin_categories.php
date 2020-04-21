<?php

require_once('inc/connect.php');                            // connect to the DB

$sql='SELECT * FROM `categories` ORDER BY `name`ASC ;';    // write the query
$query=$db->query($sql);                                   // query method
$categories=$query->fetchAll(PDO::FETCH_ASSOC);            // fetch datas

require_once('inc/close.php');                             // disconnect from the DB

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog categories</title>
</head>
<body>
    <h1>Categories list</h1>
    <table>
        <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <?php
                foreach($categories as $category):?>
                    <tr> 
                        <td><?=$category ['id'] ?></td>
                        <td><?=$category ['name'] ?></td>
                        <td>update delete</td>
                    </tr> 
                <?php endforeach; ?>
        </tbody>
    </table>
    <a href="new_category.php">Add a category</a>
</body>
</html>