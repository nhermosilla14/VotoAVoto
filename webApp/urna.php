<?php
  include 'lib/php/comm_handling.php';
  include 'lib/php/token_validation.php';
  include 'lib/php/fail_handling.php';

  // check that user has been logged in first
  if (!is_session_cookie_set()) {
    redirect_to_page("identificacion.php");
  }

  // get connection object and check proper connection
  $conn = db_conn();
  if ($conn->connect_error) {
    redirect_to_error_page("DB_CONN");
  }

  // check that user hasn't voted, otherwise redirect to last phase
  if (has_voted($_COOKIE['voting_signature'], $conn)) {
    $conn -> close();
    redirect_to_page("gracias.html");
  }

  $conn -> close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>TRICEL UTFSM</title>
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="./css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/v4-shims.css">
        <link rel='icon' href='./img/favicon.ico' type='image/x-icon'/>
    </head>
    <body class="gradient">
        <div class="w3-row">
            <div class="w3-col l3 m1 s1 w3-container">
            </div>
            <div class="form w3-col l6 m10 s10">
                <form enctype="multipart/form-data" action="insert_vote.php" method="post">
                    <h1>Elecciones TRICEL 2021</h1>
                    <h2>Urna Virtual</h2>
                    <div class="alarm">
                        <i class="fas fa-info-circle">
                            <h3>Ingresa tu voto virtual.</h3>
                            <h4>Este es el último paso de la votación. Ya casi!</h4>
                        </i>
                    </div>
                    <div class="select">
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
                    <input type="submit" value= "Ingresar Voto">
                    <br>
                    <button class="btn" onclick="window.location.href='index.html';">Volver al Inicio</button>
                    <br>
                    <image src= "./img/logo_tricel_plataforma.png">
                </form>
            </div>
        </div>
        <div class="w3-col l3 m1 s1 w3-container">
        </div>
    </body>
</html>
