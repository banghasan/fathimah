<?php

if (!defined('HS')) {
    die('Tidak boleh diakses langsung.');
}


/*
if(file_exists('plugins/file.php'))
    include 'plugins/file.php';
*/

function processMessage($sumber)
{
    global $bot;
    if ($bot['debug']) {
        print_r($message);
    }

    $lanjut = true;

    if (isset($sumber['message'])) {
        $pesandata = $sumber['message'];
        $pesan = $pesandata['text'];

        $fidpesan = $pesandata['message_id'];
        $fidchat = $pesandata['chat']['id'];

        $fdarinama = $pesandata['from']['first_name'];

        $fdarinamalengkap = isset($pesandata['from']['last_name'])
            ? $pesandata['from']['first_name'].' '.$pesandata['from']['last_name']
            : $pesandata['from']['first_name'];

        $fdariuser = isset($pesandata['from']['username'])
            ? $pesandata['from']['username']
            : '';

        $pesanr = isset($pesandata['reply_to_message']['text'])
        ? $pesandata['reply_to_message']['text']
        : 'KOSONG';
    }

    echo PHP_EOL.'Load Plugins:';
    foreach ($bot['plugins']['aktif'] as $value) {
        if (file_exists("plugins/$value".'.php')) {
            if ($lanjut) {
                echo PHP_EOL."[v] $value";
                include "plugins/$value".'.php';
            } else {
                echo PHP_EOL."[-] $value";
            }
        } else {
            echo PHP_EOL."[x] $value";
        }
    }
    echo PHP_EOL;



    /*if (isset($message["message"])) {
    	$sumber   = $message['message'];
    	$idpesan  = $sumber['message_id'];
    	$idchat   = $sumber['chat']['id'];
    	$namamu   = $sumber['from']['first_name'];
    	if (isset($sumber['text'])) {
    	  $pesan  =  $sumber['text'];
    	  $pecah  = explode(' ', $pesan);
    	  $katapertama = strtolower($pecah[0]);
    	  switch ($katapertama) {
    	    case '/start':
    	      $text = "Hai $namamu.. Akhirnya kita bertemu!";
    	      break;
    	    case '/time':
    	      $text  = "Waktu Sekarang :\n";
    	      $text .= date("d-m-Y H:i:s");
    	      break;

    	    default:
    	      $text = "Pesan sudah diterima, terimakasih ya!";
    	      break;
    	  }
    } else {
      $text  = "Ada sesuatu di bola matamu..";
    }

    $hasil = sendApiMessage($idchat, $text, $idpesan);
    if ( $bot['debug']) {
      // hanya nampak saat metode poll dan debug = true;
      echo "Pesan yang dikirim: ".$text.PHP_EOL;
      print_r($hasil);
    }
  } */
}
