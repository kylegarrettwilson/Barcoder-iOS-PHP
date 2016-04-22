<?php


// Kyle Wilson



// establish a connection to the database using PDO

$user = 'root';
$pass = 'root';
$dbh = new PDO('mysql:host=localhost; dbname=ssl; port:8889', $user, $pass);


// get fruit id

$solarid = $_GET['id'];


// this is using DELETE to target the fruit id and then delete that item from the database

$stmt = $dbh->prepare("DELETE FROM solar where id IN (:id)");


$stmt->bindParam(':id', $solarid);

$stmt->execute();

header('Location: solar.php');   // redirect back to the application




?>