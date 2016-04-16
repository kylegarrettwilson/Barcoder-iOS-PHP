<?php

    // Kyle Wilson
    // Server Side

    header("Content-type:application/json");

    $jsonfile = '{';
    $jsonfile .= '"music_catalog":[';
    $jsonfile .= '{"artist":"Yo Gabba Gabba", "album":"Steppin Out"},';
    $jsonfile .= '{"artist":"The Green Team", "album":"Lets Lose"},';
    $jsonfile .= '{"artist":"My Band", "album":"Ohhhh Yah"}';
    $jsonfile .= ']';
    $jsonfile .= '}';


    echo $jsonfile;





?>