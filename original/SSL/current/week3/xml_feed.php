<?php


    //Kyle Wilson
    //Server Side


    header("Content-type:text/xml");

    $xmlfile = "<?xml version='1.0' encoding='UTF-8'?>";
    $xmlfile .= "<feeds>";

    $xmlfile .= "<feed>";
    $xmlfile .= "<from>Kevin</from>";
    $xmlfile .= "<message>Yo</message>";
    $xmlfile .= "</feed>";

    $xmlfile .= "<feed>";
    $xmlfile .= "<from>Todd</from>";
    $xmlfile .= "<message>Hey man</message>";
    $xmlfile .= "</feed>";

    $xmlfile .= "<feed>";
    $xmlfile .= "<from>Ned</from>";
    $xmlfile .= "<message>Im outside</message>";
    $xmlfile .= "</feed>";

    $xmlfile .= "</feeds>";

    echo $xmlfile;




?>