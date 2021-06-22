<?php
    include 'lib/php/error_info.php';
    $reason =  "";
    if(isset($_REQUEST['error_reason'])){
        $reason = $_REQUEST['error_reason'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>TRICEL FEUTFSM CASA CENTRAL</title>
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="./css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/v4-shims.css">
        <link rel='icon' href='./img/favicon_tricel.ico' type='image/x-icon'/>
    </head>
    <body class="gradient">
        <div class="w3-row">
            <div class="w3-col l3 m1 s1 w3-container">
            </div>
            <div class="form w3-col l6 m10 s10">
                <h1>Elecciones Mesa Interina 2021</h1>
                <br>
                <div class="w3-row">
                  <?php if($reason != "") get_error_info($reason); ?>
                </div>
                <br>
                <button class="btn" onclick="window.location.href='index.html';">Volver al Inicio</button>
                <br>
                <image src= "./img/logo_tricel_plataforma.png">
            </div>
        </div>
        <div class="w3-col l3 m1 s1 w3-container">
        </div>
    </body>
</html>
