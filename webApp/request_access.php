<?php
    /*
        --------------------------------------------------------------------
                                    PREAMBLE
        --------------------------------------------------------------------
    */

    // Random Code Generation using random.org API.
    include './lib/php/RandDotOrg.class.php';
    $rdotorg = new RandDotOrg;

    // Definition and implementation of additional functions.
    include './lib/php/input_handling.php';
    include './lib/php/fail_handling.php';
    include './lib/php/comm_handling.php';

    /*
        --------------------------------------------------------------------
                                FORM VERIFICATION
        --------------------------------------------------------------------
    */

    // Get vars from POST. Check if they have been set. Fail otherwise.
    $rol = $usr = $dom = $captcha = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['rol'])){
            $rol = $_POST['rol'];
        } else{
            fail_helper_access("ROL_EMPTY",$rol,$usr,$dom);
        }
        if(isset($_POST['user'])){
            $usr = $_POST['user'];
        } else{
            fail_helper_access("USER_EMPTY",$rol,$usr,$dom);
        }
        if(isset($_POST['domain'])){
            $dom = $_POST['domain'];
        } else{
            fail_helper_access("DOMAIN_EMPTY",$rol,$usr,$dom);
        }
        if(isset($_POST['g-recaptcha-response'])){
            $captcha = $_POST['g-recaptcha-response'];
        } else{
            fail_helper_access("CAPTCHA_EMPTY",$rol,$usr,$dom);
        }
    }else{
        fail_helper_access("REQUEST_ERROR",$rol,$usr,$dom);
    }
    /*
        --------------------------------------------------------------------
                                INPUT VALIDATION
        --------------------------------------------------------------------
    */

    // Check captcha.
    if(!check_captcha($captcha)) {
        fail_helper_access("BOT_ALERT",$rol,$usr,$dom);
    }

    // Test data format. Sanitize possible code injection too. Fail otherwise.
    $rol = sanitize_input($rol);
    $rol = strtoupper($rol);
    if(!rol_format($rol)){
        fail_helper_access("ROL_FORMAT",$rol,$usr,$dom);
    }
    $usr = sanitize_input($usr);
    $dom = sanitize_input($dom);
    $email = $usr.$dom;
    if (!email_format($email)) {
        fail_helper_access("MAIL_FORMAT",$rol,$usr,$dom);
    }
    /*
        --------------------------------------------------------------------
                                    DB HANDLING
        --------------------------------------------------------------------
    */

    // Open db connection.
    $conn = db_conn();

    // Check connection
    if ($conn->connect_error) {
        fail_helper_access("DB_CONN",$rol,$usr,$dom);
    }
    // Check that ROL exists within db. Get its associated data.
    $find_query = "SELECT * FROM nomina WHERE email = '$email'";
    $result = $conn->query($find_query);
    if(!$result) {
        fail_helper_access("DB_QUERY",$rol,$usr,$dom);
    }
    if ($result->num_rows == 1) { // Check that we found one.
        $result = $result->fetch_assoc();
        // Check that user hasn't voted yet. Fail otherwise.
        $estado = $result["estado"];
        switch ($estado) {
            case 1:
                break;
            case 2:
                break;
            case 3:
                fail_helper_access("ALREADY_VOTED",$rol,$usr,$dom);
            default:
                fail_helper_access("UNKNOWN_STATE",$rol,$usr,$dom);
                break;
        }

        // Further check that email hasn't been used to vote before.
        $find_query = "SELECT * FROM nomina WHERE (rol='$rol' AND estado=3)";
        $result = $conn->query($find_query);
        if ($result->num_rows != 0) { // Fail if we found one.
            fail_helper_access("ROL_USED",$rol,$usr,$dom);
        }

        // Generate access code.
        $access_code = $rdotorg->get_strings(1, 8, TRUE, TRUE, TRUE, FALSE);
        $access_code = $access_code[0];

        // Update db.
        $update_query = "UPDATE nomina SET codigo='$access_code', rol='$rol', stamp=now(), estado=2 WHERE email = '$email'";
        if (!$conn->query($update_query)) {
            fail_helper_access("DB_QUERY",$rol,$usr,$dom);
        }

        // Send email. Fail if couldn't get through.
        $msg = access_code_email_content($access_code, $usr);
        if(!mail($email,"[TRICEL] Tu codigo de acceso",$msg,"From: tricel@cee-elo.cl\r\n")){
            // Make sure to go back to state 1.
            $update_query = "UPDATE nomina SET estado=1 WHERE email = '$email'";
            if (!$conn->query($update_query)) {
                fail_helper_access("DB_QUERY",$rol,$usr,$dom);
            }
            fail_helper_access("MAIL_SERVICE",$rol,$usr,$dom);
        }
    } else {
        // Fail otherwise.
        fail_helper_access("MAIL_SEARCH",$rol,$usr,$dom);
    }

    /*
        --------------------------------------------------------------------
                                EXIT CLEANUP
        --------------------------------------------------------------------
    */

    // Close db connection.
    $conn -> close();
    // Redirect the user to the next step.
    goto_upload($email,$rol);
    exit();
?>
