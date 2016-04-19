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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>


<form action="solar.php">
    <input type="submit" value="Home">
</form>


<form action="search.php">
    <input type="submit" value="Search By Barcode">
</form>





<form action="searchitem.php" method="post">

    Item Name : <input type="text" name="itname" value="<?php echo $itname;?>"><br><br>


    <input type="submit" name="Find" value="Find Data">

</form>



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
            <th>Image:</th>
            <td><?php echo $image;?></td>
        </tr>
        <tr>
            <th>Retail Price:</th>
            <td><?php echo $retailprice;?></td>
        </tr>
        <tr>
            <th>Part Number:</th>
            <td><?php echo $partnumber;?></td>
        </tr>
        <tr>
            <th>Technotes:</th>
            <td><?php echo $technotes;?></td>
        </tr>
    </table>



    <?php

    echo '<a href="update.php?id=' . $id . '">Update</a>'
    ?>

    <?php

    echo '<a href="delete.php?id=' . $id . '">Delete</a>'
    ?>




</body>

</html>
