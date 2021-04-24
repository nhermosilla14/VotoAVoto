<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>CEE-ELO UTFSM</title>
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="./css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/v4-shims.css">
        <link rel='icon' href='./img/favicon.ico' type='image/x-icon'/>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <?php
        $email = $reason = $rol = $v_code = "";
        include './lib/php/comm_handling.php';
        include './lib/php/error_info.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['rol'])){
                $rol = $_POST['rol'];
            }
            if(isset($_POST['email'])){
                $email = $_POST['email'];
            }
            if(isset($_POST['access_code'])){
                $v_code = $_POST['access_code'];
            }
            if(isset($_POST['error_reason'])){
                $reason = $_POST['error_reason'];
            }
        }
    ?>
    <body class="gradient">
        <div class="w3-row">
            <div class="w3-col l3 m1 s1 w3-container">
            </div>
            <div class="form w3-col l6 m10 s10">
                <form enctype="multipart/form-data" action="./request_vote.php" method="post">
                    <h1>Elecciones CEE-ELO 2021</h1>
                    <h2>Urna Virtual</h2>
                    <div class="w3-row">
                        <?php if($email != "") get_email_sent_msg($email); ?>
                    </div>
                    <div class="alarm">
                        <i class="fas fa-info-circle">
                            <h3>Completa los campos y selecciona tu voto virtual.</h3>
                            <h4>Este es el último paso de la votación. Ya casi!</h4>
                        </i>
                    </div>
                    <div class="select">
                        <div class="w3-row">
                            <div class="w3-col l6 m6 s12">
                                Rol:
                            </div>
                            <div class="w3-col l6 m6 s12">
                                <input type="text"  id="rol" name="rol" maxlength="11" size="11" placeholder="202021090-8" value ="<?php echo $rol;?>" required>
                            </div>
                        </div>
                        <br>
                        <div class="w3-row">
                            <div class="w3-col l6 m6 s12">
                                Código de Verificación:
                            </div>
                            <div class="w3-col l6 m6 s12">
                                <input type="text" size="8" name="access_code" value ="<?php echo $v_code;?>" required>
                            </div>
                        </div>
                        <br>
                        <div class="w3-row">
                            <div class="w3-col l6 m6 s12">
                                Selecciona tu voto:
                            </div>
                            <div class="w3-col l6 m6 s12">
                                <input type="hidden" name="MAX_FILE_SIZE" value="1024" />
                                <input type="file" id="file" name="vote" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="w3-row">
                        <div class="w3-col l3 m4 s1">
                        </div>
                        <div class="w3-col l5 m4 s10 w3-center" style="float:none;display: inline-block;">
                            <div id="captcha" class="g-recaptcha" data-sitekey="6LeoqO8UAAAAAMb3tPGIdBcxPp91kZgPZR7Yfw3Q">
                            </div>
                        </div>
                        <div class="w3-col l4 m4 s1">
                        </div>
                    </div>
                    <input type="submit" value= "Ingresar">
                    <div class="w3-row">
                        <?php if($reason != "") get_error_info($reason); ?>
                    </div>
                    <br>
                    <image src= "./img/cee_elo_logo.png">
                </form>
            </div>
        </div>
        <div class="w3-col l3 m1 s1 w3-container">
        </div>
    </body>
</html>
