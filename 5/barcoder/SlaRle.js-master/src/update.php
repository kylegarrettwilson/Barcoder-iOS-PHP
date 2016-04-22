<?php

/* --------------------------------------------------
  SlaRle.js by BobbyJay <https://github.com/BobbyJay/SlaRle.js>
  This software is provided under the MIT license, http://opensource.org/licenses/MIT.
  All use of this software must include this
  text, including the reference to the creator of the original source code. The
  originator accepts no responsibility of any kind pertaining to
  use of this software.
  Copyright (c) 2015 BobbyJay
  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:
  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.
  ------------------------ */




// Kyle Wilson, Developer





error_reporting(0);





// establishing a pdo connection to database

$user = 'root';
$pass = 'root';
$dbh = new PDO('mysql:host=localhost; dbname=ssl; port:8889', $user, $pass);



// first get the client by id with this variable to use later

$solarid = $_GET['id'];


// select all from database where the id equals the id that we are targeting (using the $clientid variable above


$stmt = $dbh->prepare("SELECT * FROM solar WHERE id = :id");
$stmt->bindParam(':id', $solarid);
$stmt->execute();
$result = $stmt->fetchAll();


// this is where the magic happens, there is a isset to make sure the fields are entered correctly and then
// using post and update to target the cleintsonline table and then setting the new post information to change the
// respected field on the database table, using $clientsid to make sure we only target THAT ONE CLIENT, NOT ALL OF THEM

if (isset($_POST['submit'])){

    $itname = $_POST['itname'];
    $barcode = $_POST['barcode'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $retailprice = $_POST['retailprice'];
    $partnumber = $_POST['partnumber'];
    $technotes = $_POST['technotes'];



    $stmt = $dbh->prepare("UPDATE solar SET itname='" . $itname . "', barcode='" . $barcode . "', quantity='" . $quantity . "', description='" . $description . "', image='" . $image . "', retailprice='" . $retailprice . "', partnumber='" . $partnumber . "', technotes='" . $technotes . "' WHERE id = '$solarid'");
    $stmt->execute();


    // send us back to the main app window

    header('Location: home.php');
    die();

}

?>


<!-- this is just the form with the php to echo out the results from what is currently saved in the database -->

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Update</title>
    <link rel="stylesheet" type="text/css" href="css/update.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,300,100,700' rel='stylesheet' type='text/css'>



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

<div id="wrapper">
    <h1>Inventory Update</h1><br>


    <form action="home.php">
        <input type="submit" value="Home">
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












<form action="" id="add" method="POST">

    <h2>Item Name: </h2><input type="text" name="itname" value=<?php echo '"'.$result[0]['itname'].'"';?>required /><br>
    <h2>Barcode: </h2><input type="text" id="barcode" name="barcode" value=<?php echo '"'.$result[0]['barcode'].'"';?>required /><br>
    <h2>Quantity: </h2><input type="text" name="quantity" value=<?php echo '"'.$result[0]['quantity'].'"';?>required /><br>
    <h2>Description: </h2><input type="text" name="description" value=<?php echo '"'.$result[0]['description'].'"';?>required /><br>
    <h2>Image: </h2><input type="text" name="image" value=<?php echo '"'.$result[0]['image'].'"';?>required /><br>
    <h2>Retail Price: </h2><input type="text" name="retailprice" value=<?php echo '"'.$result[0]['retailprice'].'"';?>required /><br>
    <h2>Part Number: </h2><input type="text" name="partnumber" value=<?php echo '"'.$result[0]['partnumber'].'"';?>required /><br>
    <h2>Technotes: </h2><input type="text" name="technotes" value=<?php echo '"'.$result[0]['technotes'].'"';?>required /><br><br>

    <input type="submit" name="submit" value="Update"/>


</form>
</div>
</body>

</html>



