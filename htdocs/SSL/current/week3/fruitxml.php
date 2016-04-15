<?php


    //Kyle Wilson
    // Server Side


    $dbh = new PDO("mysql:host=localhost;port=8889;dbname=ssl", "root", "root");
    $sth = $dbh->prepare('SELECT fruitname, fruitcolor FROM fruits');
    $sth->execute();


    $result = $sth->fetchAll();

    header("Content-type:application/xml");
    $xmlfile = '<?xml version="1.0" encoding="UTF-8"?>';
    $xmlfile .= '<fruits>';

    foreach($result as $fruit){
        $xmlfile .= '<fruitname>';
        $xmlfile .= "<fname>" . $fruit["fruitname"] . "</fname>";
        $xmlfile .= '</fruitname>';

        $xmlfile .= '<fruitcolor>';
        $xmlfile .= "<fcolor>" . $fruit["fruitcolor"] . "</fcolor>";
        $xmlfile .= '</fruitcolor>';
    };


    $xmlfile .= '</fruits>';

    echo $xmlfile;



    $dom = new DOMDocument("1.0");
    $dom->loadxml($xmlfile);
    $dom->save("fruitxml.xml");




?>