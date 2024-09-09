<?php
//echo getTimestampByDate("2024-03-21 08:12:11.784829"). "\n";
//echo "UUID modifie : " . modifyDateInUUIDv1($uuid, getTimestampByDate("2024-03-21 10:45:26.246226")) . "\n";
//echo "Date : " . hexToDateTime("1eee767beb85136");
function hexToDateTime(string $hex_timestamp) {
    //cette fonction permet de comprendre mieux le fonctionnement de l'api
    $decimal_timestamp = hexdec($hex_timestamp);
    $timestamp_seconds = ($decimal_timestamp - 122192928000000000) / 1e7;
    $date_string = date("Y-m-d H:i:s.u", $timestamp_seconds);
    return $date_string;
}

function getTimestampByDate(DateTime $date) {
    $timestamp = $date->format("U.u") * 1e7 + 122192928000000000;
    return $timestamp;
}

function modifyDateInUUIDv1(string $uuid, $timestamp) {
    $hex = dechex($timestamp);
    $parts = explode("-", $uuid);
    $parts[0] = substr($hex, -8);
    $parts[1] = substr($hex, 3, 4);
    $parts[2] = $parts[2][0] . substr($hex, 0, 3);
    return implode("-", $parts);
}


?>