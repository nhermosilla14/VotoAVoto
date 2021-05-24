<?php
  // import db handling and cookie validation functions
  include 'lib/php/comm_handling.php';
  include 'lib/php/token_validation.php';
  include 'lib/php/fail_handling.php';

  // check that a cookie hasn't been set, if so, redirect to ballot box
  if (is_session_cookie_set()) {
    redirect_to_page("urna.php");
  }

  // get connection object and check proper connection
  $conn = db_conn();
  if ($conn->connect_error) {
    redirect_to_error_page("DB_CONN");
  }

  // following specs from: https://docs.microsoft.com/en-us/azure/active-directory/develop/v2-protocols-oidc
  // set client_id, tenant and redirect uri
  $ini_vars = parse_ini_file('php.ini');
  $tenant = $ini_vars['VotingAPP.azure.TENANT'];
  $client_id = $ini_vars['VotingAPP.azure.CLIENT_ID'];
  $redirect_uri = $ini_vars['VotingAPP.azure.REDIRECT_URI'];

  // create random nonce and client state (prevents CSRF)
  // (state should be saved temporarilly into the DB for later verifaction)
  $nonce = random_int(0, 2**32);
  $state = bin2hex("NOT_LOGGED_IN-{$nonce}");
  $save_state = "INSERT INTO client_state(state) VALUES ('{$state}')";

  // check errors in state saving
  if (!$conn->query($save_state)) {
    $conn -> close();
    redirect_to_error_page("DB_QUERY");
  }

  // create login url and redirect user
  $microsoft_login_url = "https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/authorize"
                        . "?state={$state}"
                        . "&nonce={$nonce}"
                        . "&response_mode=form_post"
                        . "&scope=openid profile user.read"
                        . "&response_type=id_token"
                        . "&approval_prompt=auto"
                        . "&client_id={$client_id}"
                        . "&redirect_uri={$redirect_uri}";

  $conn->close();
  redirect_to_page($microsoft_login_url);
?>
