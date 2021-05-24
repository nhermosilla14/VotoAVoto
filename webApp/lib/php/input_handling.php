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
?>
