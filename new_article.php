<?php

// connect to the DB
require_once('inc/connect.php');

// get the list of all categories by alphabetical order
$sql='SELECT * FROM `categories` ORDER BY `name` ASC;';
$query=$db->query($sql);
$categories=$query->fetchAll(PDO::FETCH_ASSOC);

// insert datas into the DB to create the article

// handle the form
if(isset($_POST) && !empty($_POST)) {

    // connect to the lib file
    require_once('inc/lib.php');

    // call the function to check if fields are filled in
    if(verifForm($_POST, ['title', 'categories', 'content'])) {

        // prevent XSS vulnerabilities et get variables' content
        $title=strip_tags($_POST['title']);
        $content=strip_tags($_POST['content'], '<div><p><h1><h2><strong><img><a>');
        
        // write the query
        $sql='INSERT INTO `articles`(`title`, `content`, `users_id`) VALUES (:title, :content, :usersid);';

        // prepare the query
        $query=$db->prepare($sql);

        // inject values
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':content', $content, PDO::PARAM_STR);
        $query->bindValue(':usersid', 1, PDO::PARAM_INT);

        // execute the query
        $query->execute();

        // get the id of the last created element
        $articleId=$db->lastInsertId();

        //get in the $_POST checked categories, impossible to clean an array
        $categories=$_POST['categories'];

        //add categories of the article into aticles_categories, go through the array
        foreach($categories as $category) {
            $sql='INSERT INTO `articles_categories`(`articles_id`,`categories_id`) VALUES (:articleid, :categoryid);';
            $query=$db->prepare($sql);
            $query->bindValue(':articleid', $articleId, PDO::PARAM_INT);
            $query->bindValue(':categoryid', strip_tags($category), PDO::PARAM_INT);
            $query->execute();
        }
        // redirect to categories' list
        header('Location: index.php');
        

    } else {
        echo 'You should fill in all fields!';
    }
}

// disconnect from the DB
        require_once('inc/close.php');
        
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New article</title>
</head>
<body>
    <h1>New article</h1>
    <form method="post">
        <div>
            <label for="title">Title of the Article:</label>
            <input type="text" name="title" id="title">
        </div>
        <div>
            <h2>Category of the Article</h2>
        </div>
        <?php foreach ($categories as $category):?> 
            <div>
                <!-- for checkbox : name= always an array, id= content of the for and must be different for each checkbox, value= id of the checkbox -->
                <input type="checkbox" name="categories[]" id="categories <?=$category['id']?>" value="<?=$category['id']?>">
                <!-- picks up names of the categories fetched in PHP -->
                <label for="categories"><?=$category ['name'] ?></label> 
            </div>
        <?php endforeach; ?>
        <label for="content">Content of the Article:</label>
        <div>
            <textarea name="content" id="content" cols="30" rows="10"></textarea>
        </div>
        <button>Publish</button>
    </form>
</body>
</html>