<html>

<head>
    <title>Upload Image</title>
    <link rel="stylesheet" href="<?php echo base_url("assets/css/main.css"); ?>" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/js/main.js"); ?>"></script>

</head>

<body>
    <div class="divcenter" style="height: 14em;">
        <center><h2>Upload Status</h2></center>
        <hr width="70%" />

        <left>
            <p id="message">Source Path : <?php echo $source; ?></p>
            <p id="message">Width : <?php echo $width; ?></p>
            <p id="message">Height : <?php echo $height; ?></p>
            <p id="message"><b>Image Path : <?php echo $imagePath; ?></b></p>
        </left>
    </div>
</body>
</html>