<?php

if (! defined('HS')) 
  die('Tidak boleh diakses langsung.');

$keterangan[] = [
	'nama' => 'Simple',
	'oleh' => '@hasanudinhs',
	'versi' => '1.00',
	'fungsi' => 'Gaungkan Pesan Masuk',
	'format' => "!echo [pesan]\n/echo [pesan]",
	'detail' => "`pesan` boleh diisi bebas.",	
	'contoh' => "!echo hai kawan-kawan"
];

# dimulai di sini setiap plugins
if ($lanjut) {

	# detek kecocokan pola
	$pola = "/^[!\/]echo (.*)/i";
	if (preg_match($pola, $pesan, $cocok)) {
		echo " -> proses: $pola";

		$text  = "Pesan kamu: ".$cocok[1];
		$text .= "\n\nTerimakasih, sudah diterima!";
		$hasil = sendApiMessage($fidchat, $text, $fidpesan);

		if ( $bot['debug']) print_r($hasil);

	#berakhir di sini setiap plugins
		$lanjut = false;
	}

}