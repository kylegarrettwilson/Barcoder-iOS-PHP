<?php

    // Kyle Wilson
    // Server Side
    // Week 4 development

    // start a session so our message echos out correctly

    session_start();


    // this is our message to echo out

    $_SESSION["message"] = "<div class='message'>You Deleted A Client</div>";


    // establish a database connection using pdo

    $user = 'root';
    $pass = 'root';
    $dbh = new PDO('mysql:host=localhost; dbname=ssl; port:8889', $user, $pass);



    // get the correct client id from the table so we delete the right one

    $clientid = $_GET['id'];


    // using DELETE and WHERE and our id targeting variable from above to delete the correct client

    $stmt = $dbh->prepare('DELETE FROM clientsonline WHERE id = :id');
    $stmt->bindParam(':id', $clientid);
    $stmt->execute();


    // send us back to the main app window

    header('Location: clients.php');
    die();

?>