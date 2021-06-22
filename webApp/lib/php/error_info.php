<?php
    include 'input_handling.php';

    function get_error_info($reason){
        $ini_vars = parse_ini_file('php.ini');
        $support_email = $ini_vars['VotingAPP.op.SUPPORT_EMAIL'];

        echo "<br>";
        echo "<div class=\"alarm\"> <i class=\"fas fa-exclamation-circle\">";
        switch ($reason) {
            case "FILE_COUNT":
                echo "<h3>Debes seleccionar un archivo. Ni mas ni menos!</h3>";
                echo "</i>";
                break;
            case "FILE_SIZE":
                echo "<h3>Tu voto excede el tamaño límite (1kB).</h3>";
                echo "</i>";
                echo "<h4>Asegúrate de haber escogido el archivo correcto. Si el problema persiste, por favor contáctanos a {$support_email}.</h4>";
                break;
            case "FILE_EXTENSION":
                echo "<h3>Tu archivo de voto debe tener extensión .bvf.</h3>";
                echo "</i>";
                echo "<h4>Asegúrate de haber escogido el archivo correcto. Si el problema persiste, por favor contáctanos a {$support_email}.</h4>";
                break;
            case "FILE_ENT":
                echo "<h3>Tu archivo no coincide con un voto encriptado .bvf!.</h3>";
                echo "</i>";
                echo "<h4>Asegúrate de haber escogido el archivo correcto, si no, intenta crear un voto nuevo. Si el problema persiste, por favor contáctanos a {$support_email}.</h4>";
                break;
            case "STATE_TAMP":
                echo "<h3>Modificacion del estado del usuario detectada (CSRF).</h3>";
                echo "</i>";
                echo "<h4>Por favor detente :).</h4>";
                break;
            case "SIGN_TAMP":
                echo "<h3>Tu token de sesión ha expirado o ha sido modificado.</h3>";
                echo "</i>";
                echo "<h4>Por favor, vuelve al paso de identificación para renovar este.</h4>";
                break;
            case "DB_CONN":
                echo "<h3>Tuvimos un problema para conectarnos a la base de datos. Error 0x65.</h3>";
                echo "</i>";
                echo "<h4>Por favor, intenta más tarde. Si el problema persiste, por favor contáctanos a {$support_email}.</h4>";
                break;
            case "DB_QUERY":
                echo "<h3>Tuvimos un problema para conectarnos a la base de datos. Error 0xAE.</h3>";
                echo "</i>";
                echo "<h4>Por favor, intenta más tarde. Si el problema persiste, por favor contáctanos a {$support_email}.</h4>";
                break;
            case "INVALID_USER":
                echo "<h3>No estás habilitado para sufragar en estas elecciones debido a estar desfederado o no ser un alumno de pregrado.</h3>";
                echo "</i>";
                echo "<h4>Contáctanos a {$support_email} si has cambiando de opinión.</h4>";
                break;
            case "UNKNOWN_STATE":
                echo "<h3>Tuvimos un problema en el sistema. Tu usuario se encuentra en un estado desconocido.</h3>";
                echo "</i>";
                echo "<h4>Por favor, contáctanos a {$support_email} indicando tu situación.</h4>";
                break;
            case "UPLOAD_ERROR":
                echo "<h3>Tuvimos un problema para procesar tu voto.</h3>";
                echo "</i>";
                echo "<h4>Lamentamos las molestias. Cierra esta ventana e intenta otra vez por favor. Si el problema persiste, contáctanos a {$support_email} indicando tu situación.</h4>";
                break;
            default:
                $reason = sanitize_input($reason);
                echo "<h3>ERROR: $reason.</h3>";
                echo "</i>";
                echo "<h4>Contáctanos a {$support_email} indicando tu situación.</h4>";
                break;
        }
        echo "</div>";
        echo "<br>";
    }
?>
