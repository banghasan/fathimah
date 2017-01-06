<?php

if (!defined('HS')) {
    die('Tidak boleh diakses langsung.');
}

/*

Contoh penggunaan:

$permintaan = [
    'modul'=> 'quran',
    'format' => 'json',
    'surat' => '5'
];
$hasil = fathimah($permintaan);


*/

function fathimah($data, $pre = false)
{
    $header = [
        'X-Requested-With: XMLHttpRequest',
        'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36',
    ];

    $url = 'http://api.fathimah.ga';
    foreach ($data as $key => $value) {
        $key == 'modul'
            ? $url = "$url/$value"
            : $url = "$url/$key/$value";
    }

    if ($pre) {
        $url = "$url/pre";
    }

    // echo "URL: $url\n\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
// code...
}
