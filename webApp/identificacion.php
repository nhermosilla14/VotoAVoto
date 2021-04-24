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
        $reason = $rol = $usr = $dom = "";
        include './lib/php/error_info.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['rol']) and isset($_POST['user']) and isset($_POST['domain']) and isset($_POST['error_reason'])){
                $rol = $_POST['rol'];
                $usr = $_POST['user'];
                $dom = $_POST['domain'];
                $reason = $_POST['error_reason'];
            }
        }
    ?>
    <body class="gradient">
        <div class="w3-row">
            <div class="w3-col l3 m1 s1 w3-container">
            </div>
            <div class="form w3-col l6 m10 s10">
                <form action="./request_access.php" method="post">
                    <h1>Elecciones CEE-ELO 2020</h1>
                    <h2>Identificación</h2>
                    <div class="alarm">
                        <i class="fas fa-info-circle">
                            <h3>Ingresa tus datos.</h3>
                            <h4>Te enviaremos un código de acceso por correo. Con él podrás subir tu voto a nuestra urna virtual.</h4>
                        </i>
                    </div>
                    <div class="select">
                        <div class="w3-row">
                            <div class="w3-col l3 m3 s12">
                                Rol:
                            </div>
                            <div class="w3-col l5 m5 s12">
                                <input type="text"  id="rol" name="rol" maxlength="11" size="11" placeholder="202021090-8" value ="<?php echo $rol;?>" required>
                            </div>
                            <div class="w3-col l4 m4 s12">
                            </div>
                        </div>
                        <br>
                        <div class="w3-row">
                            <div class="w3-col l3 m3 s12">
                                Email:
                            </div>
                            <div class="w3-col l5 m5 s12">
                                <input type="text" id="user" name="user" maxlength="64" size="15" value ="<?php echo $usr;?>" required>
                            </div>
                            <div class="w3-col l4 m4 s12">
                                <select name ="domain">
                                    <option value="@sansano.usm.cl" <?php if($dom=="@sansano.usm.cl") echo "selected";?>> @sansano.usm.cl </option>
                                    <option value="@usm.cl" <?php if($dom=="@usm.cl") echo "selected";?>> @usm.cl </option>
                                    <option value="@alumnos.usm.cl" <?php if($dom=="@alumnos.usm.cl") echo "selected";?>> @alumnos.usm.cl </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="w3-row">
                        <div class="w3-col l3 m3 s1">
                        </div>
                        <div class="w3-col l5 m5 s10 w3-center" style="float:none;display: inline-block;">
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
            <div class="w3-col l3 m1 s1 w3-container">
            </div>
        </div>
    </body>
</html>
