<?php

session_start();

if(isset($_POST) && !empty($_POST)) {

    // connect to the lib file
    require_once('inc/lib.php');

    // call the function to check if fields are filled in
    if(verifForm($_POST, ['name'])) {

        // prevent XSS vulnerabilities et get variables' content
        $name=strip_tags($_POST['name']);

        // connect to the DB
        require_once('inc/connect.php');
        
        // write the query
        $sql='INSERT INTO `categories`(`name`) VALUES (:name);';

        // prepare the query
        $query=$db->prepare($sql);

        // inject values
        $query->bindValue(':name', $name, PDO::PARAM_STR);

        // execute the query
        $query->execute();

        // disconnect from the DB
        require_once('inc/close.php');

        // inform of the creation of the category in admin_categories.php

        // redirect to categories' list
        $_SESSION['message']= 'The category was created';
        header('Location: admin_categories.php');

    } else {
        echo 'You should fill in all fields!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a category</title>
</head>
<body>
    <h1>Add a category</h1>
    <form method="post">
        <div>
            <label for="name">Name of the category:</label>
            <input type="text" id="name" name="name">
        </div>
        <button>Add the category</button>
    </form>

</body>
</html>