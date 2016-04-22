<?php


    // JSON file

    header("Content-type:application/json");


    /*

    $jsonfile = '{';
    $jsonfile .= '"feeds":[';
    $jsonfile .= '{"from":"joe","message":"hello"},';
    $jsonfile .= '{"from":"mike","message":"hello"},';
    $jsonfile .= '{"from":"ted","message":"hello"}';
    $jsonfile .= ']';
    $jsonfile .= '}';


    */



    $jsonfile = array("feeds" => array(array("from"=>"joe", "message"=>"hello"),
                                        array("from"=>"mike", "message"=>"hello"),
                                        array("from"=>"ted", "message"=>"hello")));


    echo json_encode($jsonfile);


   // echo $jsonfile;





?>