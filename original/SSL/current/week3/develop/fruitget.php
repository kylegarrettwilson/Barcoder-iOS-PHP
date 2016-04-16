<?php

    // Kyle Wilson
    // Week 3
    // fruit get API


    // establish a connection to the database using PDO

    $user = 'root';
    $pass = 'root';
    $dbh = new PDO('mysql:host=localhost; dbname=ssl; port:8889', $user, $pass);



    // query all data from the database and pick one at random and then do this when the page is reloaded everytime

    $sqlquery = $dbh->prepare("SELECT fruitid, fruitname, fruitcolor, fruitimage FROM fruits ORDER BY RAND() LIMIT 1");

    $sqlquery->execute();

    $result = $sqlquery->fetchAll();



    // make an array to store all of this data

    $result = array("fruits"=>$result);



    // this lets the browser know that the data will be in json format and then it encodes the fetchall data above

    header("Content-type:application/json");

    $jsonfile = json_encode($result);

    echo $jsonfile;









?>