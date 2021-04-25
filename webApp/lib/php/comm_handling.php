<?php
    /*
        Stablish database connection.
    */
    function db_conn(){
        // Parse php.ini file.
        $ini_vars = parse_ini_file('../../php.ini');
        $servername = "localhost";
        $username = $ini_vars['VotingAPP.cfg.DB_USER'];
        $password = $ini_vars['VotingAPP.cfg.DB_PASS'];
        $db = "ceeelocl_tricel2021";
        $conn = new mysqli($servername, $username, $password, $db);
        return $conn;
    }

    /*
        Verify Google Re-Captcha response.
    */
    function check_captcha($captcha){
        $ini_vars = parse_ini_file('../../php.ini');
        $secretKey = $ini_vars['VotingAPP.cfg.CAPTCHA_SECRET'];
        $ip = $_SERVER['REMOTE_ADDR'];
        // Post request to re-captcha server
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretKey).'&response='.urlencode($captcha);
        $response = file_get_contents($url);
        $responseKeys = json_decode($response,true); // should return JSON with success as true
        return $responseKeys["success"];
    }
    /*
        Totally unnecessary, but I don't like to have this kind of stuff un the main
        file. It assembles the content of the email informing the user its access code.
    */
    function access_code_email_content($access_code, $user){
        $name = explode(".", $user);
        $msg = "Hola ".ucfirst($name[0])."!\n";
        $msg = $msg."Hemos recibido tu solicitud para participar de la elección ";
        $msg = $msg."de Mesa Ejecutiva CEE-ELO 2020. Para emitir tu voto, debes ";
        $msg = $msg."ingresar el siguiente código en el formulario al que fuiste ";
        $msg = $msg."redireccionado/a:\n\n";
        $msg = $msg.$access_code."\n\n";
        $msg = $msg."Este código es generado de forma aleatoria para ti y ";
        $msg = $msg."caducará luego de 15 minutos. Para asegurar la integridad ";
        $msg = $msg."del proceso, no lo compartas con nadie.\n\n";
        $msg = $msg."Si no fuiste redireccionado/a, por favor ingresa tu mismo/a al ";
        $msg = $msg."siguiente enlace para continuar con el proceso:\n\n";
        $msg = $msg."www.cee-elo.cl/votaciones/urna.php\n\n";
        $msg = $msg."Si tu código ya caducó, por favor repite el proceso y te ";
        $msg = $msg."enviaremos un nuevo código. Si necesitas ayuda, no dudes ";
        $msg = $msg."en contactarnos al correo tricel@cee-elo.cl .\n\n";
        $msg = $msg."Muchas gracias por participar! Por favor cuidate y no ";
        $msg = $msg."salgas si no es necesario.\n\n Atte.\n\n TRICEL CEE-ELO 2020.";
        return $msg;
    }
    /*
    */
    function get_email_sent_msg($email){
        $name = explode(".", $email);
        $name = ucfirst($name[0]);
        echo "<br>";
        echo "<div class=\"alarm\"> <i class=\"fas fa-envelope\">";
        echo "<h3> $name! Revisa tu correo.</h3>";
        echo "</i>";
        echo "<h4>Enviamos tu código de acceso a $email. Si no lo encuentras, revisa que hayas ingresado bien tu correo. También podría estar en tu bandeja de SPAM.</h4>";
        echo "</div>";
        echo "<br>";
    }
?>
