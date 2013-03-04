<?php
session_start();

/**
  * persona auth
  */
if(isset($_POST['assertion'])) {
    $url = 'https://verifier.login.persona.org/verify';
    $c = curl_init($url);
    $data = 'assertion='.$_POST['assertion'].'&audience=https://localhost:4443';

    curl_setopt_array($c, array(
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_POST            => true,
        CURLOPT_POSTFIELDS      => $data,
        CURLOPT_SSL_VERIFYPEER  => true,
        CURLOPT_SSL_VERIFYHOST  => 2
    ));

    $result = curl_exec($c);
    curl_close($c);

    $response = json_decode($result);
    if ($response->status == 'okay') {
        $_SESSION['user'] = $response;
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
}


/**
  * answer to persona.js
  */
include('internalauth.php');
if( isadmin($_SESSION['user']->email) ) {
    echo 'yes';
} else if(!isset($_SESSION['user'])) {
    session_destroy();
    echo 'no';
} else {
    echo 'no';
    session_destroy();
}


?>