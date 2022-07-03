<?php

$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "https://www.geonames.org/countries/");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

$dom = new DOMDocument();

@$dom->loadHTML($output);
$dom->preserveWhiteSpace = false;

$countries = $dom->getElementById('countries');

$data = [];

foreach ($countries->childNodes as $nodes){

    if($nodes->childNodes->item(0)->nodeValue) {
        if($nodes->childNodes->item(5)->nodeValue != "Capital"){
            $code = trim($nodes->childNodes->item(0)->nodeValue);
            $capital = trim($nodes->childNodes->item(5)->nodeValue);

            array_push($data, ["code" => $code, "capital" => $capital]);
        }
    }
}

echo json_encode($data);