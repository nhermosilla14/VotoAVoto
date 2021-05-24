<?php
    /*
      Redirect to arbitrary page using Location header or js
    */
    function redirect_to_page($page) {
        // headers haven't been sent, use location header
        if (!headers_sent()) {
            header("Location: {$page}");
        } else {
            // otherwise use a more "dirty" approach with js
            echo "<script type='text/javascript'>
                  window.location = '{$page}';
                  </script>";
        }
        die();
    }

    /*
        Redirect to error page
    */
    function redirect_to_error_page($error_reason) {
        redirect_to_page("error.php?error_reason={$error_reason}");
    }
?>
