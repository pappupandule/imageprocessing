<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo base_url("assets/css/main.css"); ?>" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/js/main.js"); ?>"></script>
</head>

<body>
    <div class="divcenter" style="height: 20em;">
        <center><h2>Login</h2></center>
        <hr width="70%" />
        <center><p id="message"><font color='red'><?php echo $message; ?></font></p></center>
        <form action="<?php echo base_url("/index.php/imageprocess"); ?>" id="login" name="login" method="post">
            Username : <input type="text" name="username" id="username" placeholder="Enter Username" value="<?php echo $username; ?>"><br>
            Password : <input type="password" name="password" id="password" placeholder="Enter Password" value="<?php echo $password; ?>"><br>
            <input type="button" value="Login" onclick="validateUser();">
        </form>
    </div>
</body>

</html>
