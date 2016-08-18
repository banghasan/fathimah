<?php

if (! defined('HS')) 
	die('Tidak boleh diakses langsung.');

# dimulai di sini setiap plugins
if ($lanjut) {

	# detek kecocokan pola
	$pola = "/^[!\/]help$/i";
	if (preg_match($pola, $pesan, $cocok)) {
		echo " -> proses: $pola";

		$text = "*Pilihan Bantuan*:\n~~~~~~~";
		$i=0;
		foreach ($keterangan as $key => $value) {
			$i++;
			$text .= "\n`✅ $value[nama]` (/help$key)";
		}
		$text .= "\n\n~~~~~~~\nTerdapat `$i` bantuan tersedia.";
		$hasil = sendApiMessage($fidchat, $text, $fidpesan, 'Markdown');

		if ( $bot['debug']) print_r($hasil);

	#berakhir di sini setiap plugins
		$lanjut = false;
	}

	$pola = "/^[!\/]help(\d+)$/i";
	if (preg_match($pola, $pesan, $cocok)) {
		echo " -> proses: $pola";

		$nomor = $cocok[1];
		$max = count($keterangan)-1;
		if ($nomor>$max) {
			$text = "Nomor Indeks bantuan terlalu besar, maksimal `$max`.";
		} elseif ($nomor<0) {
			$text = "Nomor Indeks bantuan dimulai dari `0`.";
		} else {
			$data=$keterangan[$nomor];

			$text  = "";
			$text .= "\n📚 Plugins *$data[nama]*";
			$text .= "\n🚦 Ver. $data[versi]";
			$text .= "\n👤 $data[oleh]";
			$text .= "\nℹ️_ $data[fungsi] _";

			$text .= "\n\n🔂 *Format*";
			$text .= "\n`$data[format]`";

			$text .= "\n\n📖 *Penjelasan*";
			$text .= "\n$data[detail]";

			$text .= "\n\n💼 *Contoh*";
			$text .= "\n`$data[contoh]`";
		}
		
		
		
		$hasil = sendApiMessage($fidchat, $text, $fidpesan, 'Markdown');

		if ( $bot['debug']) print_r($hasil);

	#berakhir di sini setiap plugins
		$lanjut = false;
	}

}