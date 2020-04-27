<?php


// connect to the DB
require_once('inc/connect.php');

// get the list of all categories 
// SQL query
$sql= 'SELECT * FROM `categories` ORDER BY `name` ASC ;';
// prepare the query
$query=$db->query($sql);
// get datas
$categories= $query->fetchAll(PDO::FETCH_ASSOC);

// get the content of the article through URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // get the id and clean it
    $id=strip_tags($_GET['id']);
    // SQL query
    $sql= 'SELECT * FROM `articles` WHERE `id`=:id;';
    // prepare the query
    $query=$db->prepare($sql);
    // insert values in the query
    $query->bindValue(':id', $id, PDO::PARAM_INT); 
    // execute the query
    $query->execute();
    // get datas
    $article= $query->fetch(PDO::FETCH_ASSOC);

    // if article  doesn't exist
    if(!$article){
    // echo "We could not find the article";
    // die;
        header('Location: admin_articles.php');
    }

    // get categories from the article
    $sql='SELECT * FROM `articles_categories` WHERE `articles_id`=:id;';
    $query=$db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $articleCategories=$query->fetchAll(PDO::FETCH_ASSOC);


} else {
    // redirection if id false
    header('Location: admin_articles.php');
}   

// get the update : check the form
if(isset($_POST) && !empty($_POST)) {    
        require_once('inc/lib.php');

        // check if the form is complete
        if(verifForm($_POST, ['title', 'content', 'categories'])) {
            // get and clean datas
            $title=strip_tags($_POST['title']);
            $content=strip_tags($_POST['content'], '<div><p><h1><h2><img><strong>');
            // get the featured_image name in the DB
            $name=$article['featured_image'];

            // check if there is an image (error=4 no file)
            if(isset($_FILES['picture']) && !empty($_FILES['picture']) && $_FILES['picture']['error'] !=4) {
                // get the $_post and clean it
                $picture=$_FILES['picture'];
                    // if transfer error
                    if($picture['error'] !=0) {
                        echo 'An error has occured';
                        die;
                    }
                    //limit to png & jpg images
                    $types=['image/png', 'image/jpeg'];
        
                    // check if file's type is not in the list
                    if(!in_array($picture['type'], $types)) {
                    echo "The file must be a png or a jpg"; 
                    die; 
                    }

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

                    // delete original pictures
                    // get first part of the old picture's name into the DB (before extension)
                    if($article['featured_image'] !=null) {
                        $nameBegining=pathinfo($article['featured_image'], PATHINFO_FILENAME);
                        // get the list of uploads files into an array
                        $files=scandir(__DIR__ . '/uploads/');
                        // loop on files
                        foreach($files as $file) {
                            // if the file's name begins by $nameBegining, we delete it strpos returns the index position number 
                            if(strpos($file, $nameBegining)===0) {
                            // delete the file
                            unlink(__DIR__ . '/uploads/' . $file);
                            }
                        }
                    }
                    

                    }

                    
        } 
            // Update the article
            // SQL query
            $sql= 'UPDATE `articles` SET `title`=:title, `content`=:content, `featured_image`=:picture WHERE `id`=:id;';
            // prepare the query
            $query=$db->prepare($sql);
            // insert values in the query
            $query->bindValue(':title', $title, PDO::PARAM_STR); 
            $query->bindValue(':content', $content, PDO::PARAM_STR); 
            $query->bindValue(':picture', $name, PDO::PARAM_STR); 
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            // execute the query
            $query->execute();

            // update categories by deleting and creating again accurate for forms with multiple choices
            $sql='DELETE FROM `articles_categories` WHERE `articles_id`=:id;'; 
            $query=$db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT).
            $query->execute();

            // get $_POST of checked categories
            $categories = $_POST['categories'];

            //add categories of the article into aticles_categories, go through the array
            foreach($categories as $category) {
                $sql='INSERT INTO `articles_categories`(`articles_id`,`categories_id`) VALUES (:articleid, :categoryid);';
                $query=$db->prepare($sql);
                $query->bindValue(':articleid', $id, PDO::PARAM_INT);
                $query->bindValue(':categoryid', strip_tags($category), PDO::PARAM_INT);
                $query->execute();
                }

            header('Location: index.php');

            } else {
            echo 'You should fill in all fields!';
                } 


    // disconnect the DB
    require_once('inc/close.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update a narticle</title>
</head>
<body>
<h1>Update an article</h1>
    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="title">Name of the article to be updated:</label>
            <input type="text" id="title" name="title" value="<?=$article['title']?>">
        </div>
        <div>
            <h3>Category</h3>
            <?php foreach ($categories as $category):
                //check if the category should be checked 
                $checked='';
                foreach($articleCategories as $articleCat) {
                    if ($articleCat['categories_id'] == $category['id']){
                        $checked='checked';
                    }
                }
                ?> 
                <div>
                    <input type="checkbox" name="categories[]" id="categories<?=$category['id']?>" value="<?=$category['id' ]?>"<?=$checked?>>
                    <label for="categories"><?=$category ['name'] ?></label> 
                </div>
            <?php endforeach; ?>
        </div>
        <label for="content">Content of the Article:</label>
        <div>
            <textarea name="content" id="content" cols="30" rows="10"><?=$article['content']?></textarea>
        </div>
        <h2>Photo</h2>
        <div>
            <label for="picture">Picture:</label>
            <input type="file" name="picture" id="picture" multiple>
        </div>


        <button>Update</button>
    </form>
</body>
</html>