<?php
/***************************
 *
 * PHP File für die Website
 *
 **************************/

if(!isset($_COOKIE['DEFAULT_LANGUAGE']) || !isset($_COOKIE['LANGUAGE'])) {
    // setcookie('DEFAULT_LANGUAGE', DEFAULT_LANGUAGE, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie('DEFAULT_LANGUAGE', DEFAULT_LANGUAGE, time() + (86400 * 30), "/");
    setcookie('LANGUAGE', DEFAULT_LANGUAGE, time() + (86400 * 30), "/"); // 86400 = 1 day
    $_COOKIE['LANGUAGE'] = DEFAULT_LANGUAGE;
}

function CallAPI($method, $url, $data = false) {
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

function getTheme() {
    $result = json_decode(CallAPI('GET', AUDIENCE.'/api/index.php/siteinfo'));
    return $result->siteinfo->site_theme;
}

?>