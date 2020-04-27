<?php

// get the id of the category to be updated 
// check if if fields are  filled in
if(isset($_GET['id']) && !empty($_GET ['id'])){
    // get the id and clean it
    $id=strip_tags($_GET['id']);

// connect to the DB
    require_once('inc/connect.php');
// SQL query
    $sql= 'SELECT * FROM `categories` WHERE `id`=:id;';
// prepare the query
    $query=$db->prepare($sql);
// insert values in the query
    $query->bindValue(':id', $id, PDO::PARAM_STR); 
// execute the query
    $query->execute();
// get datas
    $category= $query->fetch(PDO::FETCH_ASSOC);

// if category doesn't exist
if(!$category){
    echo "We could not find the category";
    die;
}

// get the update : check the form
    // check if if fields are  filled in
        if(isset($_POST['name']) && !empty($_POST['name'])) {
        // get the $_post and clean it
            $name=strip_tags($_POST['name']);
        // SQL query
            $sql= 'UPDATE `categories` SET `name`=:name WHERE `id`=:id;';
        // prepare the query
            $query=$db->prepare($sql);
        // insert values in the query
            $query->bindValue(':name', $name, PDO::PARAM_STR); 
            $query->bindValue(':id', $id, PDO::PARAM_INT);
        // execute the query
            $query->execute();

        // redirect 
            header('Location: admin_categories.php');
        
        } 
// disconnect the DB
    require_once('inc/close.php');

    } else {
        
    // redirection if id false
    header('Location: admin_categories.php');
}       

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update a category</title>
</head>
<body>
<h1>Update a category</h1>
    <form method="post">
        <div>
            <label for="name">Name of the category to be updated:</label>
            <input type="text" id="name" name="name" value="<?=$category['name']?>">
        </div>
        <button>Update</button>
    </form>
</body>
</html>