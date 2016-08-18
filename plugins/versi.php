<?php

if (!defined('HS')) {
    die('Tidak boleh diakses langsung.');
}

$keterangan[] = [
    'nama'   => 'Versi',
    'oleh'   => '@hasanudinhs',
    'versi'  => '1.00',
    'fungsi' => 'Menampilkan Versi Program dan API Fathimah',
    'format' => "!versi\n/versi",
    'detail' => '-',
    'contoh' => '!versi',
];

// dimulai di sini setiap plugins
if ($lanjut) {

    // detek kecocokan pola
    $pola = "/^[!\/](versi|version)/i";
    if (preg_match($pola, $pesan, $cocok)) {
        echo " -> proses: $pola";


        $text = '🔬 *'.PROGRAMNAME."*\n⛓ Ver.".PROGRAMVER."\n⏳ ".PROGRAMUP;

        $permintaan = [
            'modul'  => 'versi',
            'format' => 'json',
        ];

        $respon = fathimah($permintaan);
        if ($datas = json_decode($respon, true)) {
            if ($datas['status'] == 'error') {
                $text .= "\n\n🚫 `$datas[pesan]`";
            } else {
                $text .= "\n\n🕋 *$datas[nama]*";
                $text .= "\n⚙ Ver.$datas[versi]";
                $text .= "\n⏳ $datas[update]";
            }
        } else {
            $text .= "\n\n🚫 ".BAHASA_ERROR_REMOTE_TIDAK_DIKETAHUI;
        }

        $text .= "\n\n🌐 www.fathimah.ga";

        $hasil = sendApiMessage($fidchat, $text, $fidpesan, 'Markdown', true);

        if ($bot['debug']) {
            print_r($hasil);
        }

    //berakhir di sini setiap plugins
        $lanjut = false;
    }
}
