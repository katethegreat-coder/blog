<?php
try {
    $db=new PDO('mysql:host=localhost;dbname=blog', 'root', '');
    $db->exec('SETNAMES"UTF8"');
} catch(PDOException $e) {
    echo"Error:".$e->getMessage();
    die;
}