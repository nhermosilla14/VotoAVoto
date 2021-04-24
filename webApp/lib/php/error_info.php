<?php
    function get_error_info($reason){
        echo "<br>";
        echo "<div class=\"alarm\"> <i class=\"fas fa-exclamation-circle\">";
        switch ($reason) {
            case "REQUEST_ERROR":
                echo "<h3>Debes completar este formulario primero!</h3>";
                echo "</i>";
                break;
            case "ROL_EMPTY":
                echo "<h3>Debes completar tu ROL antes de continuar!</h3>";
                echo "</i>";
                break;
            case "USER_EMPTY":
                echo "<h3>Debes completar tu correo institucional antes de continuar!</h3>";
                echo "</i>";
                break;
            case "DOMAIN_EMPTY":
                echo "<h3>Debes seleccionar un dominio antes de continuar!</h3>";
                echo "</i>";
                break;
            case "FILE_COUNT":
                echo "<h3>Debes seleccionar un archivo. Ni mas ni menos!</h3>";
                echo "</i>";
                break;
            case "ACCESS_EMPTY":
                echo "<h3>Debes ingresar tu código de acceso antes de continuar!</h3>";
                echo "</i>";
                break;
            case "CAPTCHA_EMPTY":
                echo "<h3>Debes completar el captcha antes de continuar!</h3>";
                echo "</i>";
                break;
            case "BOT_ALERT":
                echo "<h3>Los algoritmos de Re-Captcha indican que estás haciendo mal-uso de la plataforma.</h3>";
                echo "</i>";
                echo "<h4>Si este no es el caso, por favor contáctanos a tricel@cee-elo.cl. De otro modo, por favor no vuelvas :).</h4>";
                break;
            case "ROL_FORMAT":
                echo "<h3>Asegúrate de que tu ROL está bien escrito.</h3>";
                echo "</i>";
                echo "<h4>Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "MAIL_FORMAT":
                echo "<h3>Asegúrate de que tu correo está bien escrito.</h3>";
                echo "</i>";
                echo "<h4>Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "FILE_SIZE":
                echo "<h3>Tu voto excede el tamaño límite (1kB).</h3>";
                echo "</i>";
                echo "<h4>Asegúrate de haber escogido el archivo correcto. Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "FILE_EXTENSION":
                echo "<h3>Tu archivo de voto debe tener extensión .bvf.</h3>";
                echo "</i>";
                echo "<h4>Asegúrate de haber escogido el archivo correcto. Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "CODE_FORMAT":
                echo "<h3>Asegúrate de que tu código está bien escrito.</h3>";
                echo "</i>";
                echo "<h4>Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "DB_CONN":
                echo "<h3>Tuvimos un problema para conectarnos a la base de datos.</h3>";
                echo "</i>";
                echo "<h4>Por favor, intenta más tarde. Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "DB_QUERY":
                echo "<h3>Tuvimos un problema para conectarnos a la base de datos.</h3>";
                echo "</i>";
                echo "<h4>Por favor, intenta más tarde. Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "MAIL_SERVICE":
                echo "<h3>Tuvimos un problema para enviar el código de verificación a tu correo.</h3>";
                echo "</i>";
                echo "<h4>Por favor, verifica los datos que ingresaste y vuelve a intentar. Si el problema persiste, por favor contáctanos a tricel@cee-elo.cl.</h4>";
                break;
            case "ROL_SEARCH":
                echo "<h3>Tuvimos un problema para encontrarte en el listado de votantes.</h3>";
                echo "</i>";
                echo "<h4>Por favor, verifica que el ROL ingresado es correcto. Si todo está en orden, por favor contáctanos a tricel@cee-elo.cl indicando tu situación.</h4>";
                echo "<h4>Adjunta en el correo tu nombre completo, ROL y certificado de alumno de regular (lo puedes conseguir gratis en SIGA).</h4>";
                break;
            case "ALREADY_VOTED":
                echo "<h3>Nuestros registros indican que ya votaste.</h3>";
                echo "</i>";
                echo "<h4>Claramente no puedes votar más de una vez. Si aún no haz votado, verifica que los datos ingresados son correctos. Si todo está en orden y el problema persiste, por favor contáctanos a tricel@cee-elo.cl indicando tu situación.</h4>";
                break;
            case "EMAIL_USED":
                echo "<h3>Nuestros registros indican que ya votaste.</h3>";
                echo "</i>";
                echo "<h4>Tu correo ya fue empleado para votar. Si aún no haz votado, verifica que los datos ingresados son correctos. Si todo está en orden y el problema persiste, por favor contáctanos a tricel@cee-elo.cl indicando tu situación.</h4>";
                break;
            case "UNKNOWN_STATE":
                echo "<h3>Tuvimos un problema en el sistema. Tu usuario se encuentra en un estado desconocido.</h3>";
                echo "</i>";
                echo "<h4>Por favor, contáctanos a tricel@cee-elo.cl indicando tu situación.</h4>";
                break;
            case "CODE_MISMATCH":
                echo "<h3>El código ingresado no es correcto.</h3>";
                echo "</i>";
                echo "<h4>Verifica que lo ingresaste bien. Si todo está en orden, por favor genera un nuevo código e intenta otra vez. Si el problema persiste, contáctanos a tricel@cee-elo.cl indicando tu situación.</h4>";
                break;
            case "CODE_TIMEOUT":
                echo "<h3>El código ingresado es correcto, pero ya caducó.</h3>";
                echo "</i>";
                echo "<h4>Por favor genera un nuevo código e intenta otra vez. Recuerda que este tendrá también una validez de 15 minutos.</h4>";
                break;
            case "UPLOAD_ERROR":
                echo "<h3>Tuvimos un problema para procesar tu voto.</h3>";
                echo "</i>";
                echo "<h4>Lamentamos las molestias. Cierra esta ventana e intenta otra vez por favor. Si el problema persiste, contáctanos a tricel@cee-elo.cl indicando tu situación.</h4>";
                break;
            default:
                echo "<h3>ERROR: $reason.</h3>";
                echo "</i>";
                echo "<h4>Contáctanos a tricel@cee-elo.cl indicando tu situación.</h4>";
                break;
        }
        echo "</div>";
        echo "<br>";
    }
?>
