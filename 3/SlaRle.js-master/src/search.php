<?php

error_reporting(0);

// php search data in mysql database using PDO
// set data in input text

$barcode = "";


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
    $barcode = $_POST['barcode'];

    // mysql search query
    $pdoQuery = "SELECT * FROM solar WHERE barcode = :barcode";

    $pdoResult = $pdoConnect->prepare($pdoQuery);

    //set your id to the query id
    $pdoExec = $pdoResult->execute(array(":barcode"=>$barcode));

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

    <title>Warehouse App</title>
    <link rel="stylesheet" href="">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700' rel='stylesheet' type='text/css'>





    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <script src="https://raw.githubusercontent.com/jseidelin/exif-js/master/exif.js"></script>
    <script src="https://raw.githubusercontent.com/stomita/ios-imagefile-megapixel/master/src/megapix-image.js"></script>

    <style>
        canvas { width: 100%; border: 1px solid #DDD; background: #FAFAFA; cursor: pointer; }
        input[type=file] { display: none; }
    </style>










    <script>
        $(document).ready(function() {
            var startTime;
            var endTime;
            var tempCanvas = document.getElementById('image');
            var fileInput = document.getElementById('upload');
            var SlaRleWorker = new Worker('./SlaRle.js');

            $('#trigger').click(function() {
                $('#upload').trigger("click");
            });
            $('canvas').click(function() {
                $('#upload').trigger("click");
            });

            // image chosen or taken
            $('#upload').change(function() {
                var file = fileInput.files[0];
                var canvas = document.getElementById('image');

                // MegaPixImage constructor accepts File/Blob object.
                var mpImg = new MegaPixImage(file);

                // get EXIF data for orientation
                EXIF.getData(file, function() {
                    var orientation = EXIF.getTag(file, "Orientation") || 0;
                    var max = $('#resolution').val();
                    // render image to canvas with maximum resolution and correct orientation
                    mpImg.render(canvas, { maxWidth: max, maxHeight: max, orientation: orientation });
                });
            });

            // scan initiated
            $('#scan').click(function() {
                $('#time').html("0");
                $('#result').html("-");
                // start time measurement
                startTime = new Date().getTime();

                var tempCanvasCtx = tempCanvas.getContext("2d");
                var data = tempCanvasCtx.getImageData(0, 0, tempCanvas.width, tempCanvas.height).data;

                // prepare message
                var message = {
                    img: data,
                    width: tempCanvas.width,
                    height: tempCanvas.height
                };

                SlaRleWorker.postMessage(message);
                message = null;
            });

            // receive worker messages
            function receiveMessage(e) {
                endTime = new Date().getTime();

                // decoding result
                if (e.data.decoding && e.data.result) {
                    endTime = new Date().getTime();
                    $('#time').html((endTime - startTime) / 1000);
                    $('#result').html(e.data.EAN.toString());
                    document.getElementById("barcode").value = (e.data.EAN.toString());
                }

            }

            SlaRleWorker.onmessage = receiveMessage;

        });
    </script>

</head>

<body>


    <form action="solar.php">
        <input type="submit" value="Home">
    </form>


    <form action="searchitem.php">
        <input type="submit" value="Search Item Name">
    </form>






    <div class="container">


        <h3>Result: <span id="result">-</span></h3>



        <h4>Time: <span id="time">0</span> s</h4>



        <button id="trigger" type="button" class="btn btn-primary btn-lg btn-block">Load Image</button>
        <input id="upload" type="file" name="image" accept="image/*" capture>


        <button id="scan" type="button" class="btn btn-success btn-lg btn-block">Scan</button>


        <hr/>



        <div class="input-group">
            <span class="input-group-addon">Max. <wbr/>Resolution</span>
            <input id="resolution" type="text" class="form-control" value="640">
            <span class="input-group-addon">px</span>
        </div>



        <canvas id="image"></canvas>

    </div>






    <form action="search.php" method="post">

        Barcode : <input type="text" id="barcode" name="barcode" value="<?php echo $barcode;?>"><br><br>


        <input type="submit" name="Find" value="Find Data">

    </form>



    <table style="width:100%">
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
            <th>Quantity</th>
            <td><?php echo $quantity;?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?php echo $description;?></td>
        </tr>
        <tr>
            <th>Image</th>
            <td><?php echo $image;?></td>
        </tr>
        <tr>
            <th>Retail Price</th>
            <td><?php echo $retailprice;?></td>
        </tr>
        <tr>
            <th>Part Number</th>
            <td><?php echo $partnumber;?></td>
        </tr>
        <tr>
            <th>Technotes</th>
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
