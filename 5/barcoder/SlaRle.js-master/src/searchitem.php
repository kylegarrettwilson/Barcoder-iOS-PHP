<?php

error_reporting(0);

// php search data in mysql database using PDO
// set data in input text

$itname = "";


if(isset($_POST['Find']))
{
    // connect to mysql
    try {
        $pdoConnect = new PDO("mysql:host=localhost;dbname=ssl","root","root");
    } catch (PDOException $exc) {
        echo $exc->getMessage();
        exit();
    }

    // id to search
    $itname = $_POST['itname'];

    // mysql search query
    $pdoQuery = "SELECT * FROM solar WHERE itname = :itname";

    $pdoResult = $pdoConnect->prepare($pdoQuery);

    //set your id to the query id
    $pdoExec = $pdoResult->execute(array(":itname"=>$itname));

    if($pdoExec)
    {
        // if id exist
        // show data in inputs
        if($pdoResult->rowCount()>0)
        {
            foreach($pdoResult as $row)
            {
                $id = $row['id'];
                $barcode = $row['barcode'];
                $itname = $row['itname'];
                $quantity = $row['quantity'];
                $description = $row['description'];
                $image = $row['image'];
                $retailprice = $row['retailprice'];
                $partnumber = $row['partnumber'];
                $technotes = $row['technotes'];

            }
        }
        // if the id not exist
        // show a message and clear inputs
        else{
            echo 'No Data With This ID';
        }
    }else{
        echo 'ERROR Data Not Inserted';
    }
}


?>



<!DOCTYPE html>

<html>

<head>

    <title> PHP SEARCH DATA USING PDO </title>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="css/searchitem.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <script src="https://raw.githubusercontent.com/jseidelin/exif-js/master/exif.js"></script>
    <script src="https://raw.githubusercontent.com/stomita/ios-imagefile-megapixel/master/src/megapix-image.js"></script>

    <style>
        canvas { width: 100%; border: 1px solid #DDD; background: #FAFAFA; cursor: pointer; }
        input[type=file] { display: none; }
    </style>


</head>

<body>

    <h1>Item Search</h1>



    <form action="home.php">
        <input type="submit" value="Home">
    </form>


    <form action="searchbarcode.php">
        <input type="submit" value="Search By Barcode">
    </form><br><br>







    <form action="searchitem.php" method="post">

        Item Name : <br><input type="text" name="itname" value="<?php echo $itname;?>"><br><br>


        <input type="submit" name="Find" value="Find Data">

    </form>



    <section id="imagetwo">
        <?php echo $image;?>
    </section>



    <table>
        <tr>
            <th>Id:</th>
            <td><?php echo $id;?></td>
        </tr>
        <tr>
            <th>Barcode:</th>
            <td><?php echo $barcode;?></td>
        </tr>
        <tr>
            <th>Item Name:</th>
            <td><?php echo $itname;?></td>
        </tr>
        <tr>
            <th>Quantity:</th>
            <td><?php echo $quantity;?></td>
        </tr>
        <tr>
            <th>Description:</th>
            <td><?php echo $description;?></td>
        </tr>
        <tr>
            <th>Retail Price:</th>
            <td><?php echo $retailprice;?></td>
        </tr>
        <tr>
            <th>Part Number:</th>
            <td><?php echo $partnumber;?></td>
        </tr>




    <div id="updatebutton">

        <?php

        echo '<a href="update.php?id=' . $id . '">Update</a>'
        ?>

        <?php

        echo '<a href="delete.php?id=' . $id . '">Delete</a>'
        ?>

    </div>

    </table>



    <section id="techn">
        <h3>Technotes:</h3>
        <p><?php echo $technotes;?></p>
    </section>



</body>

</html>
