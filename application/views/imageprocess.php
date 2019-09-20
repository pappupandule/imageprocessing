<html>

<head>
    <title>Upload Image</title>
    <link rel="stylesheet" href="<?php echo base_url("assets/css/main.css"); ?>" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/js/main.js"); ?>"></script>

</head>

<body>
    <div class="divcenter">
        <center><h2>Upload Image</h2></center>
        <hr width="70%" />
        <center><p id="message"></p></center>
        <form action="<?php echo base_url("/index.php/imageprocess/upload"); ?>" id="upload" name="upload" method="post">
            Image Path<font color="red">*</font>: <input type="text" name="path" id="path" placeholder="eg : https://i.imgur.com/zo5c7F8.jpg" value=""><br>
            Width(in pixel)<font color="red">*</font>: <input type="text" name="width" id="width" class="numberOnly" placeholder="eg : 70" value=""><br>
            Height(in pixel)<font color="red">*</font>: <input type="text" name="height" id="height" class="numberOnly" placeholder="eg : 30" value=""><br>
            <input type="button" value="Submit" onclick="validateUploadImageData()">
        </form>
    </div>
</body>

</html>
