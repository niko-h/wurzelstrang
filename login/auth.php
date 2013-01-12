<?php
session_start();

if(isset($_POST['assertion'])) {
    $url = 'https://verifier.login.persona.org/verify';
    $c = curl_init($url);
    $data = 'assertion='.$_POST['assertion'].'&audience=http://192.168.178.30:8888';

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
?>