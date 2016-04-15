<?php


    // Kyle Wilson
    // Server Side


    $contents = file_get_contents("fjson.json");

    $encode = json_decode($contents);

    foreach($encode->fruitspass as $fruitname){

        echo $fruitname->fruitname."<br />";
    }

    foreach($encode->fruitspass as $fruitcolor){

        echo $fruitcolor->fruitcolor."<br />";
    }






?>