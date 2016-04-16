<?php


    // Kyle Wilson
    // Server Side


    $dbh = new PDO("mysql:host=localhost;port=8889;dbname=ssl", "root", "root");
    $sth = $dbh->prepare('SELECT fruitname, fruitcolor FROM fruits');
    $sth->execute();


    $results = $sth->fetchAll();

    header("Content-type:application/json");


    echo json_encode($results);

    $fruitjson = json_encode($results);

    file_put_contents('fjson.json', $fruitjson);





?>

