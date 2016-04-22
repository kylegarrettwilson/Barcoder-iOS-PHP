<?php


    // Kyle Wilson
    // Server Side
    // Week 3
    // Delete fruit


    // establish a connection to the database using PDO

    $user = 'root';
    $pass = 'root';
    $dbh = new PDO('mysql:host=localhost; dbname=ssl; port:8889', $user, $pass);


    // get fruit id

    $fruitid = $_GET['id'];


    // this is using DELETE to target the fruit id and then delete that item from the database

    $stmt = $dbh->prepare("DELETE FROM fruits where fruitid IN (:fruitid)");


    $stmt->bindParam(':fruitid', $fruitid);

    $stmt->execute();

    header('Location: fruitsads.php');   // redirect back to the application




?>