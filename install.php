<?php

define('HS', true);

date_default_timezone_set('ASIA/Jakarta');
include 'konstanta.php';

echo
'
=========================
* Bot Installer
* '.PROGRAMNAME.'
-------------------------
* oleh: Hasanudin HS
* email: banghasan@gmail.com 
* telegram dan twitter: @hasanudinhs
* grup telegram: @botphp
-------------------------
* Release: Versi '.PROGRAMVER.'
* Tanggal: '.PROGRAMUP.'
* Website: '.PROGRAMURL.'
=========================

';


echo 'Ingin melanjutkan proses installasi (Ya/Tidak/Kosong BATAL)? ';

$handle = fopen('php://stdin', 'r');
$line = fgets($handle);
if (strtolower(trim($line)) != 'ya') {
    echo '- Proses installasi dibatalkan!'.PHP_EOL.PHP_EOL;
    echo 'Terimakasih.'.PHP_EOL;
    exit;
}



$myfile = fopen('data/setting.php', 'w') or die('Gagal membuat file!'.PHP_EOL.'Pastikan direktori /data dapat ditulisi.');

echo 'Masukkan token bot: '; $line = fgets($handle);
echo '
------------------------
+ Proses...
';

$TOKEN = trim($line);

$txt = "<?php\n";

$txt .= "
if (!defined('HS')) 
	die('Tidak boleh diakses langsung.');

require_once 'teks/bahasa.php';
";

$txt .= "\n\$token=\"$TOKEN\";\n\n";

$method = 'getMe';
$url = 'https://api.telegram.org/bot'.$TOKEN.'/'.$method;

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$datas = curl_exec($ch);
echo curl_error($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo '________________________'.PHP_EOL;
if ($status != 200) {
    echo 'Token ERROR!';
} else {
    $data = json_decode($datas);

    $txt  .= "\n\$bot['name']  		= '".$data->result->first_name."';";
    $txt  .= "\n\$bot['username']  	= '@".$data->result->username."';";
    $txt  .= "\n\$bot['logtime']  	= '".date('Y-m-d H:i:s')."';";

    echo 'Nama Bot: '.$data->result->first_name.PHP_EOL;
    echo 'Nama Bot: '.$data->result->username.PHP_EOL;
}
echo '________________________'.PHP_EOL;

echo '+ set plugins dan variabel...';

$txt .= <<<'EOT'
$bot['token']		= $token;
$bot['debug']		= false;

$bot['admin']['name']		= 'bangHasan';
$bot['admin']['username']	= '@hasanudinhs';
$bot['admin']['id']		=  '213567634';	

$bot['plugins']['aktif'] = [
	'qse-surat',
	'qse-ayat',
	'qse-note',
	'qse-bahasa',
	'qse-cari',
	'start',
	'simple',
	'versi',
	'help' // letakkan paling bawah selalu
];


# Jika ingin di hook ke non https ubah di sini
$bot['API_URL'] = "https://api.telegram.org/bot$bot[token]/";

# penyesuaian waktu server
date_default_timezone_set("ASIA/Jakarta");

EOT;

fwrite($myfile, $txt);

fclose($handle);
fclose($myfile);


echo '
~~~~~~~~~~~~~~~~~~~~~~~~~
file data/setting.php telah dibentuk.
~~~~~~~~~~~~~~~~~~~~~~~~~

untuk menjalankan: php poll.php
webhook: URL/hook.php

--- install selesai... ---
';
