<?php


    $content = file_get_contents("http://www.omdbapi.com/?t=goodfellas");


    $parse = json_decode($content);

    var_dump($parse);


    echo "</br>";
    echo "</br>";
    echo "</br>";




    $contents = file_get_contents("dictionary.txt");


    $parsel = json_decode($contents);

    foreach($parsel->feeds as $feed){

        echo $feed->from."</br>";

    }











?>