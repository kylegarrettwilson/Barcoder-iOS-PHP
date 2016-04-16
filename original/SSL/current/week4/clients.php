<?php

    // Kyle Wilson
    // Server Side
    // Week 4 Development


    // starting the session so our message echos out correctly

    session_start();

    if (isset($_SESSION["message"])){

        echo $_SESSION["message"];
        unset($_SESSION["message"]);
    }



    // establishing a database connection using pdo

    $user = 'root';
    $password = 'root';
    $mysql = 'mysql:host=localhost;dbname=ssl;port=8889';
    $dbh = new PDO($mysql, $user, $password);



    // using isset to make sure user entered in form correctly and then inserting into the database and echo out the message

    if (isset($_POST['submit'])){


        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $website = $_POST['website'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];


        $dbh = new PDO($mysql, $user, $password);
        $stmt = $dbh->prepare("INSERT INTO clientsonline (fname, lname, phone, email, website) VALUES (:fname, :lname, :phone, :email, :website)");
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':website', $website);
        $stmt->execute();

        echo "<div class='message'>Your Client's Information Is Saved</div>";



    }



?>



    <!-- start of html document -->


<!DOCTYPE html>
<html>
    <head>
        <title>Client Contact Manager</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,300,100,700' rel='stylesheet' type='text/css'>
    </head>



    <body>


        <h1>Client Contact Manager</h1>

        <div id="meeting"></div>




        <form id='add' action="clients.php" method="POST">
            <h2>First Name: </h2><input type="text" name="fname" value="" required /><br>
            <h2>Last Name: </h2><input type="text" name="lname" value="" required /><br>
            <h2>Phone Number: </h2><input type="text" name="phone" value="" required /><br>
            <h2>Email: </h2><input type="text" name="email" value="" required /><br>
            <h2>Website: </h2><input type="text" name="website" value="" required /><br>
            <input type="submit" name="submit" value="Submit"/>
        </form>







        <?php


            // establishing a pdo connection and the selecting all from table and order by id


            $dbh = new PDO($mysql, $user,  $password);
            $stmt = $dbh->prepare("SELECT * FROM clientsonline ORDER BY id");
            $stmt->execute();
            $result = $stmt->fetchAll();


            // using a for each loop to loop through the info of database and display each client's info

            foreach ($result as $row){
                echo "<div class='info'>";

                echo '<h2><font>First: </font>'.$row['fname']."</h2>";
                echo '<h2><font>Last: </font>'.$row['lname']."</h2>";
                echo '<h2><font>Phone: </font>'.$row['phone']."</h2>";
                echo '<h2><font>Email: </font>'.$row['email']."</h2>";
                echo '<h2><font>Website: </font>'.$row['website']."</h2><br>";

                echo ' <a href="delete.php?id='.$row['id'].'"><button id="delete" class="buttondelete">X</button></a>';
                echo ' <a href="update.php?id='.$row['id'].'"><button id="update" class="buttonupdate">UPDATE</button></a><br>';

                echo "</div>";

            }




        ?>

            <!-- using jQuery to do switch images and then a click function to show form -->

        <script type="text/javascript" src="js/jquery.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script>

            $("#meeting").mouseover(function(){

                $("#meeting").css("background-image", "url(images/meeting_clicked.jpg)");

            });

            $("#meeting").mouseout(function(){

                $("#meeting").css("background-image", "url(images/meetings.jpg)");

            });




            $("#meeting").click(function(){

                $("form").show("bounce", {times:3}, 300);

            });



        </script>



    </body>


</html>
