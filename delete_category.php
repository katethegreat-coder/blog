<?php

// get the id of a category to be deleted
if(isset($_GET['id']) && !empty($_GET ['id'])){
// get the id and clean it
    $id=strip_tags($_GET['id']);
// connect to the DB
    require_once('inc/connect.php');
// SQL query
    $sql= 'DELETE FROM `categories` WHERE `id`=:id;';
// prepare the query
    $query=$db->prepare($sql);
// insert values in the query
    $query->bindValue(':id', $id, PDO::PARAM_STR); 
// execute the query
    $query->execute();
// disconnect the DB
    require_once('inc/close.php');
// redirection once deleted
header('Location: admin_categories.php');

    } else {
        
    // redirection if id false
    header('Location: admin_categories.php');
}       

?>
