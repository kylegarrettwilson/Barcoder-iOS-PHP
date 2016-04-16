<?php


    // Kyle Wilson
    // Server Side
    // Week 4 development


    // starting the session so our message echos out correctly


    session_start();

    // this will be the message that the user sees when they update


    $_SESSION["message"] = "<div class='message'>You Updated Your Client</div>";


    // establishing a pdo connection to database

    $user = 'root';
    $pass = 'root';
    $dbh = new PDO('mysql:host=localhost; dbname=ssl; port:8889', $user, $pass);



    // first get the client by id with this variable to use later

    $clientid = $_GET['id'];


    // select all from database where the id equals the id that we are targeting (using the $clientid variable above


    $stmt = $dbh->prepare("SELECT * FROM clientsonline WHERE id = :id");
    $stmt->bindParam(':id', $clientid);
    $stmt->execute();
    $result = $stmt->fetchAll();


    // this is where the magic happens, there is a isset to make sure the fields are entered correctly and then
        // using post and update to target the cleintsonline table and then setting the new post information to change the
        // respected field on the database table, using $clientsid to make sure we only target THAT ONE CLIENT, NOT ALL OF THEM

    if (isset($_POST['submit'])){

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $website = $_POST['website'];



        $stmt = $dbh->prepare("UPDATE clientsonline SET fname='" . $fname . "', lname='" . $lname . "', phone='" . $phone . "', email='" . $email . "', website='" . $website . "' WHERE id = '$clientid'");
        $stmt->execute();


        // send us back to the main app window

        header('Location: clients.php');
        die();

    }

?>


    <!-- this is just the form with the php to echo out the results from what is currently saved in the database -->

<!DOCTYPE html>
<html>
    <head>
        <title>Client Contact Manager</title>
        <link rel="stylesheet" type="text/css" href="css/style2.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,300,100,700' rel='stylesheet' type='text/css'>
    </head>

    <body>


        <h1>Update Your Client Contacts</h1>


        <form action="" id="add" method="POST">

            <h2>First Name: </h2><input type="text" name="fname" value=<?php echo '"'.$result[0]['fname'].'"';?>required /><br>
            <h2>Last Name: </h2><input type="text" name="lname" value=<?php echo '"'.$result[0]['lname'].'"';?>required /><br>
            <h2>Phone: </h2><input type="text" name="phone" value=<?php echo '"'.$result[0]['phone'].'"';?>required /><br>
            <h2>Email: </h2><input type="text" name="email" value=<?php echo '"'.$result[0]['email'].'"';?>required /><br>
            <h2>Website: </h2><input type="text" name="website" value=<?php echo '"'.$result[0]['website'].'"';?>required /><br>

            <input type="submit" name="submit" value="Update"/>


        </form>

    </body>

</html>



