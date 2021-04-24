<?php
    /*
        --------------------------------------------------------------------
                                    PREAMBLE
        --------------------------------------------------------------------
    */

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
    $rol = $access_code = $captcha = $vote_data = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (count($_FILES) == 1) {
            $f_type = strtolower(pathinfo(basename($_FILES["vote"]["name"]),PATHINFO_EXTENSION));
            $f_size = $_FILES["vote"]["size"];
        } else {
            fail_helper_vote("FILE_COUNT",$rol,$access_code);
        }
        if(isset($_POST['rol'])){
            $rol = $_POST['rol'];
        } else {
            fail_helper_vote("ROL_EMPTY",$rol,$access_code);
        }
        if(isset($_POST['access_code'])){
            $access_code = $_POST['access_code'];
        } else {
            fail_helper_vote("ACCESS_EMPTY",$rol,$access_code);
        }
        if(isset($_POST['g-recaptcha-response'])){
            $captcha = $_POST['g-recaptcha-response'];
        } else {
            fail_helper_vote("CAPTCHA_EMPTY",$rol,$access_code);
        }
    }else{
        fail_helper_vote("REQUEST_ERROR",$rol,$access_code);
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

    // Test ROL and access_code format. Sanitize possible code injection too. Fail otherwise.
    $rol = sanitize_input($rol);
    $rol = strtoupper($rol);
    if(!rol_format($rol)){
        fail_helper_vote("ROL_FORMAT",$rol,$access_code);
    }
    $access_code = sanitize_input($access_code);
    if(!access_code_format($access_code)){
        fail_helper_vote("CODE_FORMAT",$rol,$access_code);
    }

    // Test file restrictions
    if($_FILES["vote"]["error"] == UPLOAD_ERR_OK) {
        if($f_type == "bvf"){
            if($f_size <= 1024){
                $vote_data = addslashes(file_get_contents($_FILES['vote']['tmp_name']));
            } else {
                fail_helper_vote("FILE_SIZE",$rol,$access_code);
            }
        } else {
            fail_helper_vote("FILE_EXTENSION",$rol,$access_code);
        }
    } else {
        fail_helper_vote("UPLOAD_ERROR",$rol,$access_code);
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
        fail_helper_vote("DB_CONN",$rol,$access_code);
    }

    // Check that ROL exists within db. Get its associated data.
    $find_query = "SELECT * FROM nomina WHERE rol = '$rol'";
    $result = $conn->query($find_query);
    if(!$result){
        fail_helper_vote("DB_QUERY",$rol,$access_code);
    }
    if ($result->num_rows == 1) { // Check that we found one.
        $result = $result->fetch_assoc();
        // Check that user was sent an access_code. Fail otherwise.
        $estado = $result["estado"];
        switch ($estado) {
            case 1:
                fail_helper_access("REQUEST_ERROR",$rol,"","");
                break;
            case 2:
                // Further verify that the access code hasn't expired.
                $t_diff = time() - strtotime($result["stamp"]); // seconds
                if($t_diff > 900) { // 15 minutes window
                    fail_helper_access("CODE_TIMEOUT",$rol,"","");
                }
                break;
            case 3:
                fail_helper_vote("ALREADY_VOTED",$rol,$access_code);
            default:
                fail_helper_vote("UNKNOWN_STATE",$rol,$access_code);
                break;
        }
        // Verify that the right access code was provided.
        if($result["codigo"] == $access_code){
            // If valid, insert vote.
            $voting_query = "INSERT INTO urna(voto) VALUES ('{$vote_data}')";
            if(!$conn->query($voting_query)) {
                fail_helper_vote("DB_QUERY".$conn->error,$rol,$access_code);
            }
        } else {
            fail_helper_vote("CODE_MISMATCH",$rol,$access_code);
        }
        $update_query = "UPDATE nomina SET stamp=now(), estado=3 WHERE rol = '$rol'";
        if (!$conn->query($update_query)) {
            fail_helper_vote("DB_QUERY",$rol,$access_code);
        }
    } else { // Fail otherwise.
        fail_helper_vote("ROL_SEARCH",$rol,$access_code);
    }

    /*
        --------------------------------------------------------------------
                                EXIT CLEANUP
        --------------------------------------------------------------------
    */

    // Close db connection.
    $conn -> close();

    // Redirect the user to last page.
    header("Location: ./gracias.html");
    exit();
?>
