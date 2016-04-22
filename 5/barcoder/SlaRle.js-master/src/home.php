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



    // this is using PDO to make a connection to the mysql database


    $user = 'root';
    $password = 'root';
    $mysql = 'mysql:host=localhost;dbname=ssl;port=8889';
    $dbh = new PDO($mysql, $user, $password);

    // This is doing two parts, first it is using isset to check if the form has been submitted correctly
    // Secondly it inserts user input into the fruits database


    if (isset($_POST['submit'])){

        $itname = $_POST['itname'];
        $barcode = $_POST['barcode'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        $retailprice = $_POST['retailprice'];
        $partnumber = $_POST['partnumber'];
        $technotes = $_POST['technotes'];


        $dbh = new PDO($mysql, $user, $password);
        $stmt = $dbh->prepare("INSERT INTO solar (itname, barcode, quantity, description, image, retailprice, partnumber, technotes) VALUES (:itname, :barcode, :quantity, :description, :image, :retailprice, :partnumber, :technotes)");
        $stmt->bindParam(':itname', $itname);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':retailprice', $retailprice);
        $stmt->bindParam(':partnumber', $partnumber);
        $stmt->bindParam(':technotes', $technotes);
        $stmt->execute();
    }


?>







    <!-- beginning of the html page -->

<!DOCTYPE html>
<html>
<head>
    <title>Warehouse App</title>
    <link rel="stylesheet" href="css/home.css">
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


    <!-- using a wrapping div to contain the form -->

<div id="app">

    <h1>Warehouse App</h1>

    <h3>Data Input</h3>

    <form action="searchbarcode.php">
        <input type="submit" value="Search By Barcode">
    </form>


    <form action="searchitem.php">
        <input type="submit" value="Search By Item Name">
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











    <!-- form section -->

    <section>
        <form id="form" action="home.php" method="post">
            <label><b>Item Name:    </b><br><input type="text" name="itname" value="" placeholder="Item Name" required ></label><br>
            <label><b>Barcode:  </b><br><input type="text" id='barcode' name='barcode' value="" placeholder="Barcode Numbers" required ></label><br>
            <label><b>Quantity: </b><br><input type="number" name="quantity" min="0" max="10000" value="1" required ></label><br>
            <label><b>Description:  </b><br><input type="text" name="description" value="" placeholder="Description" required ></label><br>
            <label><b>Image Url:    </b><br><input type="text" name="image" value="" placeholder="Image Url" required ></label><br>
            <label><b>Retail Price: </b><br><input type="text" name="retailprice" value="" placeholder="Retail Price" required ></label><br>
            <label><b>Part Number:  </b><br><input type="text" name="partnumber" value="" placeholder="Part Number" required ></label><br>
            <label><b></b><textarea name="technotes" rows="5" cols="80" placeholder="Tech Notes" required ></textarea></label><br>
            <input type="submit" name="submit" value="Submit">

        </form>
    </section>


    <!-- this is the beginning of the table section. What is awesome about this part is the fact that
        there is php code within the table element in order to inject the form data into the table from
         the database -->


    <section>
        <table>
            <tr>
                <th>Id</th>
                <th>Item Name</th>
                <th>Barcode</th>
                <th>Quantity</th>
                <th>Retail Price</th>
                <th>Part Number</th>
            </tr>


            <!-- first we are saying grab ALL from the fruits database and order by the
                primary key which is the fruitid and present it is ascending order -->

            <!-- Then we are using fetchall to run the data through a foreach loop in order to
                collect and display all of the data within the table. As you can see, the $rw
                variable is printing each item to the table through an echo. -->

<?php


    $stmt = $dbh->prepare('SELECT * FROM solar ORDER BY id ASC');

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($results as $rw){

        echo '<tr><td>' . $rw['id'] . '</td><td>' . $rw['itname'] . '</td><td>' . $rw['barcode'] . '</td><td>' .
            $rw['quantity'] . '</td><td>' . $rw['retailprice'] . '</td><td>' . $rw['partnumber'] . '</td><td><a href="delete.php?id=' . $rw['id'] . '">Delete</a></td>' . '</td><td><a href="update.php?id=' . $rw['id'] . '">Update</a></td>';
    }




?>


     </table></section>






</div>







</body>
</html>
