<?php
    /*
        Sanitize relative paths for curl funtion.
    */
    function sanitize_curl_path($relative) {
        $domain = $_SERVER['SERVER_NAME'];
        $prefix = $_SERVER['HTTPS'] ? 'https://' : 'http://';
        return $prefix.$domain.$relative;
    }

    /*
        Send back form content and reason in case of error.
    */
    function fail_helper_access($error_reason,$rol,$user,$domain){
        // Set target url
        $url = sanitize_curl_path('/votaciones/identificacion.php');
        // Set the post values
        $fields = array(
            'error_reason' => urlencode($error_reason),
            'user' => urlencode($user),
            'domain' => urlencode($domain),
            'rol' => urlencode($rol)
        );

        // Url-ify the data for the POST
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        die();
    }

    /*
        Send back form content and reason in case of error.
    */
    function fail_helper_vote($error_reason,$rol,$access_code){
        // Set target url
        $url = sanitize_curl_path('/votaciones/urna.php');
        // Set the post values
        $fields = array(
            'error_reason' => urlencode($error_reason),
            'access_code' => urlencode($access_code),
            'rol' => urlencode($rol)
        );

        // Url-ify the data for the POST
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        die();
    }

    /*
        Go to upload page with custom success message.
    */
    function goto_upload($email,$rol){
        // Set target url
        $url = sanitize_curl_path('/votaciones/urna.php');
        // Set the post values
        $fields = array(
            'email' => urlencode($email),
            'rol' => urlencode($rol)
        );

        // Url-ify the data for the POST
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        die();
    }
?>
