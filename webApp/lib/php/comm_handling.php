<?php
    /*
        Establish database connection.
    */
    function db_conn(){
        // Parse php.ini file.
        $ini_vars = parse_ini_file('php.ini');
        $servername = $ini_vars['VotingAPP.cfg.DB_HOST'];
        $username = $ini_vars['VotingAPP.cfg.DB_USER'];
        $password = $ini_vars['VotingAPP.cfg.DB_PASS'];
        $db = $ini_vars['VotingAPP.cfg.DB_NAME'];
        $conn = new mysqli($servername, $username, $password, $db);
        return $conn;
    }
?>
