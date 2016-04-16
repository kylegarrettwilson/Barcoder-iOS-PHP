<?php

    // Kyle Wilson
    // Server Side
    // Week Three


        // this is where I create the fruit API call
    $jcontent = file_get_contents("http://localhost:8888/develop/fruitget.php");


    // now that you have the fruitget encoded json, now decode it

    $contents = json_decode($jcontent);






    // this is using PDO to make a connection to the mysql database


    $user = 'root';
    $pass = 'root';
    $dbh = new PDO('mysql:host=localhost; dbname=ssl; port:8889', $user, $pass);

    // This is doing two parts, first it is using isset to check if the form has been submitted correctly
    // Secondly it inserts user input into the fruits database


    if (isset($_POST['submit'])){

        $name = $_POST['fruitname'];
        $color = $_POST['fruitcolor'];
        $image = $_POST['fruitimage'];



        $stmt = $dbh->prepare("INSERT INTO fruits (fruitname, fruitcolor, fruitimage) VALUES (:fruitname, :fruitcolor, :fruitimage);");

        $stmt->bindParam(':fruitname', $name);
        $stmt->bindParam(':fruitcolor', $color);
        $stmt->bindParam(':fruitimage', $image);
        $stmt->execute();
    }


?>







    <!-- beginning of the html page -->

<!DOCTYPE html>
<html>
<head>
    <title>My Fruit Database</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700' rel='stylesheet' type='text/css'>
</head>
<body>


    <!-- using a wrapping div to contain the form -->

<div id="app">

    <h1>Fruit Database App</h1>

    <div id="headerimg"></div>


    <!-- Display random selection to browser -->

    <?php

        foreach ($contents->fruits as $fruit){
            echo '<h1>Kyle\'s Fruit On Sale!</h1>
                  <h3>&nbsp;  '.$fruit->fruitcolor. ' '. $fruit->fruitname."</h3>";
            echo "<img src='".$fruit->fruitimage."'width='300'/>";

        }


    ?><br><br><br>


    <!-- form section -->

    <section>
        <form action="fruitsads.php" method="post">
            <label><b>Fruit Name:</b><input type="text" name="fruitname" value="" placeholder="Fruit Name" required ></label>
            <label><b>Fruit Color:</b><input type="text" name="fruitcolor" value="" placeholder="Add A Color" required ></label>
            <label><b>Fruit Image:</b><input type="text" name="fruitimage" value="" placeholder="Image URL" required ></label>
            <input type="submit" name="submit" value="Submit">
            <input type="reset" name="reset" value="Reset">
        </form>
    </section>


    <!-- this is the beginning of the table section. What is awesome about this part is the fact that
        there is php code within the table element in order to inject the form data into the table from
         the database -->


    <section>
        <table>
            <tr>
                <th>Fruit Id</th>
                <th>Fruit Name</th>
                <th>Fruit Color</th>
                <th>Fruit Image</th>
                <th>Action</th>
            </tr>


            <!-- first we are saying grab ALL from the fruits database and order by the
                primary key which is the fruitid and present it is ascending order -->

            <!-- Then we are using fetchall to run the data through a foreach loop in order to
                collect and display all of the data within the table. As you can see, the $rw
                variable is printing each item to the table through an echo. -->

<?php


    $stmt = $dbh->prepare('SELECT * FROM fruits ORDER BY fruitid ASC');

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($results as $rw){

        echo '<tr><td>' . $rw['fruitid'] . '</td><td>' . $rw['fruitname'] . '</td><td>' . $rw['fruitcolor'] . '</td><td>' .
            $rw['fruitimage'] . '</td><td><a href="deletefruit.php?id=' . $rw['fruitid'] . '">Delete</a></td>';
    }




?>


     </table></section>






</div>







</body>
</html>
