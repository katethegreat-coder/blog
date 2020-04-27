<?php

// get the id of the article to be deleted
if(isset($_GET['id']) && !empty($_GET ['id'])){
    // get the id and clean it
    $id=strip_tags($_GET['id']);
    // connect to the DB
    require_once('inc/connect.php');
    // SQL query
    $sql= 'SELECT * FROM `articles` WHERE `id`=:id;';
    // prepare the query
    $query=$db->prepare($sql);
    // insert values in the query
    $query->bindValue(':id', $id, PDO::PARAM_INT); 
    // execute the query
    $query->execute();
    // get the article
    $article=$query->fetch(PDO::FETCH_ASSOC);

    // redirection if does not exist
    if (!$article) {
         header('Location: admin_articles.php');
    }
    
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
    // delete links to categories
    $sql='DELETE FROM `articles_categories` WHERE `articles_id`=:id;';
    $query=$db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT); 
    $query->execute();

    // delete the article
    $sql='DELETE FROM `articles` WHERE `id`=:id;';
    $query=$db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT); 
    $query->execute();

    // disconnect the DB
    require_once('inc/close.php'); 
    
    header('Location: admin_articles.php');

        } else {
            
        // redirection if id false
        header('Location: admin_articles.php');
    }    