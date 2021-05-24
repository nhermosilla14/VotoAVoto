<?php
    // Definition and implementation of additional functions.
    include 'lib/php/input_handling.php';
    include 'lib/php/fail_handling.php';
    include 'lib/php/comm_handling.php';
    include 'lib/php/token_validation.php';
    $target_dir = "../../private_files/";

    // get connection object and check proper connection
    $conn = db_conn();
    if ($conn->connect_error) {
      redirect_to_error_page("DB_CONN");
    }


    // check that session cookie is set and that it's valid
    if (!is_session_cookie_set()) {
      redirect_to_error_page("SIGN_TAMP");
    }

    // check that user hasn't voted, otherwise redirect to last phase
    if (has_voted($_COOKIE['voting_signature'], $conn)) {
      redirect_to_page("gracias.html");
    }


    // get signature from cookie and user email from token
    $id_token = $_COOKIE["voting_signature"];
    //echo json_decode(base64_decode(explode(".", $id_token)[1]), True);
    $jwt_payload = json_decode(base64_decode(explode(".", $id_token)[1]), True);
    $jwt_email = $jwt_payload['preferred_username'];

    // check correct request method and num of files given
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (count($_FILES) == 1) {
          $f_type = strtolower(pathinfo(basename($_FILES["vote"]["name"]),PATHINFO_EXTENSION));
          $f_size = $_FILES["vote"]["size"];
      } else {
          redirect_to_error_page("FILE_COUNT");
      }
    } else {
      // redirect to home page
      redirect_to_page("/");
    }


    // test file restrictions
    if($_FILES["vote"]["error"] == UPLOAD_ERR_OK) {
        // requires the bvf format suffix
        if($f_type == "bvf"){
            // has to be smaller than 1 KiB
            if($f_size <= 1024){
                $vote_data = addslashes(file_get_contents($_FILES['vote']['tmp_name']));
            } else {
                redirect_to_error_page("FILE_SIZE");
            }
        } else {
            redirect_to_error_page("FILE_EXTENSION");
        }
    } else {
        // failed while uploading
        redirect_to_error_page("UPLOAD_ERROR");
    }


    // token is verified before inserting vote
    // valid, insert vote.
    $voting_query = "INSERT INTO ballot(vote) VALUES ('{$vote_data}')";
    if(!$conn->query($voting_query)) {
        redirect_to_error_page("DB_QUERY");
    }

    // store the vote file locally on a private folder using sha256 hash as the filename.
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $hash = hash_file("sha256", $_FILES['vote']['tmp_name']);
    $target_file = $target_dir.$hash.'.bvf';

    if (!move_uploaded_file($_FILES['vote']['tmp_name'], $target_file)) {
        redirect_to_error_page("UPLOAD_ERROR");
    }

    // then save signature into db
    $save_token_query = "INSERT INTO signature(email, token) VALUES ('{$jwt_email}', '{$id_token}')";

    if (!$conn->query($save_token_query)) {
      redirect_to_error_page("DB_QUERY");
    }

    // close db connection
    $conn -> close();

    // redirect to last phase
    redirect_to_page("gracias.html");
?>
