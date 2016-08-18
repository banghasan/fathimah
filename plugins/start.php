<?php

if (! defined('HS')) 
  die('Tidak boleh diakses langsung.');

$keterangan[] = [
	'nama' => 'Start',
	'oleh' => '@hasanudinhs',
	'versi' => '1.00',
	'fungsi' => 'Respon /start',
	'format' => "!start\n/start",
	'detail' => "-",	
	'contoh' => "/start"
];

# dimulai di sini setiap plugins
if ($lanjut) {

	# detek kecocokan pola
	$pola = "/^[!\/]start/i";
	if (preg_match($pola, $pesan, $cocok)) {
		echo " -> proses: $pola";

		$text  = "Halo, *".escmarkdown($fdarinama)."* ...";
		$text .= "\n\nJika butuh bantuan ketik /help ya.";
		$hasil = sendApiMessage($fidchat, $text, $fidpesan,"Markdown");

		if ( $bot['debug']) print_r($hasil);

	#berakhir di sini setiap plugins
		$lanjut = false;
	}

}