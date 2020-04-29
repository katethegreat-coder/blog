<?php

session_start();

// connect to the DB
require_once('inc/connect.php');

// get the list of all categories by alphabetical order
$sql='SELECT * FROM `categories` ORDER BY `name` ASC;';
$query=$db->query($sql);
$categories=$query->fetchAll(PDO::FETCH_ASSOC);

// insert datas into the DB to create the article

// handle the form - text fields
if(isset($_POST) && !empty($_POST)) {

    // connect to the lib file
    require_once('inc/lib.php');

    // call the function to check if fields are filled in
    if(verifForm($_POST, ['title', 'categories', 'content'])) {

        // prevent XSS vulnerabilities et get variables' content
        $title=strip_tags($_POST['title']);
        $content=strip_tags($_POST['content'], '<div><p><h1><h2><strong><img><a>');

        //check if there is an image & exclude error 4 (no file)
        if(isset($_FILES['picture']) && !empty($_FILES['picture']) && $_FILES ['picture']['error'] !=4){
            // get picture's information
            $picture=$_FILES['picture'];

            // transfer did not go through 'error'!=0
            if($picture['error']!=0) {
                echo 'The picture could not be uploaded';
                die;
            } 

            //limit to png & jpg images
            $types=['image/png', 'image/jpeg'];
        
            // check if file's type is not in the list
            if(!in_array($picture['type'], $types)) {
                $_SESSION['error']='The files must be a png or a jpg';
                header('Location:new_article.php');
                die;
            }

            // we limit the file size to 1Mo max
            // if($picture['size'] > 1048576) {
            //     echo 'The file exceeds the limit of 1Mo';
            //     die;
            // }

            // transfer went through, we give a name to the image and we move the temp image
            // get the file extension in lowercase
            $extension =strtolower(pathinfo($picture['name'], PATHINFO_EXTENSION));
            // generate a random name
            $name= md5(uniqid()).'.'.$extension;
            // generate the entire name with the absolute path & the name
            $entireName= __DIR__ . '/uploads/' . $name;   //file's folder found by __DIR__ /folder's name / extension
            // move the file into a folder

            if(!move_uploaded_file($picture['tmp_name'], $entireName)) {   // move the file from the temporary folder to the destination
                echo "the file was not deplaced";
                die;
            }

            // create different version of the picture: thumbnail of 300px and the picture resized -75%
            
            thumb(300, $name);
            resizedPicture($name, 75);
            resizedPicture($name, 25);
        }
    // write the query
    $sql='INSERT INTO `articles`(`title`, `content`, `featured_image`, `users_id`) VALUES (:title, :content, :picture, :usersid);';

    // prepare the query
    $query=$db->prepare($sql);

    // inject values
    $query->bindValue(':title', $title, PDO::PARAM_STR);
    $query->bindValue(':content', $content, PDO::PARAM_STR);
    $query->bindValue(':usersid', 1, PDO::PARAM_INT);
    $query->bindValue(':picture', $name, PDO::PARAM_STR);

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
    } else {
    echo 'You should fill in all fields!';
}

// redirect to categories' list
$_SESSION['message']= 'Your article was created with the id '.$articleId;
header('Location: admin_articles.php');    

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
    <?php if(isset($_SESSION['error']) && !empty($_SESSION['error'])) {
        echo $_SESSION['error'];
    }
    ?>
    <form method="post" enctype="multipart/form-data" >   <!-- do not forget enctype ! for file fields -->
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
        <h2>Photo</h2>
        <div>
            <label for="picture">Picture:</label>
            <input type="file" name="picture" id="picture" multiple>
        </div>
        <button>Publish</button>
    </form>
</body>
</html>