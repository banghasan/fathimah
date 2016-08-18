<?php

if (! defined('HS')) 
	die('Tidak boleh diakses langsung.');

$keterangan[] = [
	'nama' => 'QSE Note',
	'oleh' => '@hasanudinhs',
	'versi' => '1.00',
	'fungsi' => 'Menampilkan Modul Quran untuk Catatan Kaki dari Depag',
	'format' => "!qn [nomor]",
	'detail' => "`nomor` dimulai dari `1` sampai dengan `1.610`",	
	'contoh' => "!qn 100\n/qn100"
];

# dimulai di sini setiap plugins
if ($lanjut) {
	
	# alias
	$pola = "/^[!\/]qn(\d+)/i";
	if (preg_match($pola, $pesan, $cocok)) $pesan = "!qn $cocok[1]";

	# detek kecocokan pola
	$pola = "/^[!\/]qn (\d+)/i";
	if (preg_match($pola, $pesan, $cocok)) {
		echo " -> proses: $pola";

		$permintaan = [
			'modul'=> 'quran',
			'format' => 'json',
			'catatan' => $cocok[1]
		];

		$respon = fathimah($permintaan);

		if ($datas = json_decode($respon, true)) {
			if ($datas['status']=='ok') {
				$text  = $datas['catatan']['teks'];
				$text .= "\nRef. QS ".$datas['quran']['surat'].':'.$datas['quran']['ayat'];
				$text .= " (/qs".setTigaChar($datas['quran']['surat']).setTigaChar($datas['quran']['ayat']).")";
			} else {
				$text = $datas['pesan'];
			}
			
		} else {
			$text = BAHASA_ERROR_REMOTE_TIDAK_DIKETAHUI;
		}
		

		$hasil = sendApiMessage($fidchat, $text, $fidpesan, 'Markdown', true);

		if ( $bot['debug']) print_r($hasil);

		//print_r($datas);


	#berakhir di sini setiap plugins
		$lanjut = false;
	}

}
