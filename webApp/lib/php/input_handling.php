<?php
/*
    Input needs to be sanitized to prevent XSS attacks or SQL injection.
    Also make the use of lower case letters mandatory for consistency with DB.
*/
function sanitize_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/*
    Just a convenience wrapper for the ROL format checker. Eases re-utilization.
*/
function rol_format($rol){
    return preg_match('/^[0-9]{7,9}\-[0-9K]$/',$rol);
}

/*
    Just a convenience wrapper for the ROL format checker. Eases re-utilization.
*/
function access_code_format($access_code){
    return preg_match('/^[0-9a-zA-Z]{8}$/',$access_code);
}

/*
    Just a convenience wrapper for the email format checker. Eases re-utilization.
*/
function email_format($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>
