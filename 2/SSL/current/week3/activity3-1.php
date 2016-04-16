<?php


    // Kyle Wilson
    // Server Side


    $contents = simplexml_load_file("fruitxml.xml");

    foreach($contents->fruitname as $fruit){

        echo $fruit->fname."<br />";
    }

    foreach($contents->fruitcolor as $color){

        echo $color->fcolor."<br />";
    }


    //var_dump($contents);



?>