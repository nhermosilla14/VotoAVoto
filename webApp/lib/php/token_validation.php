<?php
    /*
      Validate a JWT using a python module
    */
    function validate_jwt($id_token) {
      $jwt_username = array();
      $jwt_retval = -1;
      if (preg_match("/^[a-zA-Z0-9\.\-_]+$/", $id_token)) {
        exec("python3 lib/python/signature_validation.py " . escapeshellarg($id_token), $jwt_username, $jwt_retval);
      }

      return array($jwt_username[0], $jwt_retval);
    }

    /*
      Check that a JWT set as a cookie is valid, if it is then redirect to urna.php
    */
    function is_session_cookie_set() {
      // if cookie is not set
      if (!isset($_COOKIE["voting_signature"])) {
          return False;
      }

      // validate and extract results
      $token = $_COOKIE["voting_signature"];
      [$jwt_email, $jwt_retval] = validate_jwt($token);

      // validation succeeded
      if ($jwt_retval == 0) {
        return True;
      }

      return False;
    }

    /*
      Check that a JWT is already on our DB
    */
    function has_voted($id_token, $conn) {
      // first validate the given token (remove possibility of injections)
      [$jwt_email, $jwt_retval] = validate_jwt($id_token);

      // if it failed validation
      if ($jwt_retval != 0) {
        return True;
      }

      // search for email and signature in db
      $jwt_name = explode('@', $jwt_email)[0];
      $search_user = "SELECT email FROM signature WHERE email LIKE '{$jwt_name}@%'";
      $res = $conn->query($search_user);

      // has voted either using the same signature as before
      // or using new signature (but being the same person)
      if ($res->num_rows > 0) {
        return True;
      }

      return False;
    }
?>
