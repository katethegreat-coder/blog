<?php

require_once('inc/connect.php');

$sql='SELECT* FROM `users` ORDER BY `email`ASC ;';
$query=$db->query($sql);
$users=$query->fetchAll(PDO::FETCH_ASSOC);

require_once('inc/close.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog's users</title>
</head>
<body>
    <h1>Users' list</h1>
    <table>
        <thead>
            <th>ID</th>
            <th>Email</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <?php
                foreach($users as $user): ?>
                    <tr> 
                        <td><?=$user ['id'] ?></td>                                        <!-- Echo short code -->
                        <td><?=$user ['email'] ?></td>
                        <td><a href="users.php?id=<?=$user ['id']?>">Afficher</a></td>     <!-- Inject the page --> 
                    </tr> 
                <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>