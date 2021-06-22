<?php
  // import db handling and token validation functions
  include 'lib/php/comm_handling.php';
  include 'lib/php/token_validation.php';
  include 'lib/php/fail_handling.php';

  // get connection object and check proper connection
  $conn = db_conn();
  if ($conn->connect_error) {
    redirect_to_error_page("DB_CONN");
  }

  // check that this is a post request and that id_token and state is being passed
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_token"]) && isset($_POST["state"])) {
    $id_token = $_POST["id_token"];
    $state = $_POST["state"];

    // verify state (it's valid hex)
    if (preg_match("/^[a-f0-9]{2,}$/", $state)) {
      // verify state (it's on our db)
      $search_query = "SELECT state FROM client_state WHERE state = '{$state}'";
      $res = $conn->query($search_query);

      if ($res->num_rows == 0) {
        // state was tampered with, end immediately
        $conn -> close();
        redirect_to_error_page("STATE_TAMP");
      }

      // delete client state from db
      $delete_query = "DELETE FROM client_state WHERE state = '{$state}'";
      if (!$conn->query($delete_query)) {
        $conn -> close();
        redirect_to_error_page("DB_QUERY");
      }

    } else {
      // state was tampered with, end immediately
      $conn -> close();
      redirect_to_error_page("STATE_TAMP");
    }

    // call python module that makes correct JWT validation after JWT format checking
    [$jwt_username, $jwt_retval] = validate_jwt($id_token);

    // check if validation was successful
    if ($jwt_retval == 0) {
      // then check against valid users (all domains are taked into account for this logged user)
      $jwt_name = explode("@", $jwt_username)[0];
      $check_user_query = "SELECT email FROM student WHERE email LIKE '{$jwt_name}@%'";

      // user wasn't found in db, redirect to error page
      $res = $conn->query($check_user_query);
      if ($res->num_rows == 0) {
        $conn -> close();
        redirect_to_error_page("INVALID_USER");
      }

      $conn -> close();

      // user can vote, set cookie and redirect to next step
      $max_age = 300;
      echo "<script type='text/javascript'>
              document.cookie = 'voting_signature={$id_token}; max-age={$max_age}; Secure';
            </script>";
      redirect_to_page("urna.php");
    } else {
      redirect_to_error_page("SIGN_TAMP");
    }

  } else {
    redirect_to_page("index.html");
  }
?>
