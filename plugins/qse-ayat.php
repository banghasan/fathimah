<?php

if (!defined('HS')) {
    die('Tidak boleh diakses langsung.');
}


$keterangan[] = [
    'nama'   => 'QSE Ayat',
    'oleh'   => '@hasanudinhs',
    'versi'  => '1.00',
    'fungsi' => 'Menampilkan Ayat pada Modul Quran',
    'format' => "!quran [nomorsurat] [nomorayat]\n!quran [nomorsurat] [nomorayat] [bahasa]\n\n!quran bisa disingkat menjadi !qs",
    'detail' => '`nomorsurat` angka dari `1` sampai dengan `114`

`nomorayat`
   - angka `1` sampai nomor akhir ayat surat 
   - bisa menggunakan `-` untuk menampilkan jarak ayat, contoh: `1-3`
   - gunakan koma (`,`) untuk memilih lebih dari 1 bahasa, contoh: `1,2,4`

`bahasa` pilihan id bahasa 
   gunakan koma (,) untuk memilih lebih dari 1 bahasa. Contoh: `ar,idt,id`
',
    'contoh' => "!quran 2 100\n!quran 2:100,200 id\n!quran 2 100-102 ar,en",

];


// dimulai di sini setiap plugins
if ($lanjut) {
    switch (true) {

        // pola lengkap
        case preg_match("/^[!\/](qs|quran) (\d+)( |:)((\w|,|-)+) ((\w|,|-)+)/i", $pesan, $cocok):
            $proses['surat'] = $cocok[2];
            $proses['ayat'] = $cocok[4];
            $proses['bahasa'] = $cocok[6];
            break;

        // pola  !qs surat:ayat
        // pola  !qs surat ayat
        case preg_match("/^[!\/](qs|quran) (\d+)( |:)((\w|,|-)+)/i", $pesan, $cocok):
            $proses['surat'] = $cocok[2];
            $proses['ayat'] = $cocok[4];
            $proses['bahasa'] = 'ar,id';
            break;

        // pola  !qs001002
        case preg_match("/^[!\/](qs|quran)(\d{3})(\d{3})/i", $pesan, $cocok):
            $proses['surat'] = $cocok[2];
            $proses['ayat'] = $cocok[3];
            $proses['bahasa'] = 'ar,id';
            break;

        default:
            // code...
            break;
     }

    if (isset($proses['surat'])) {
        echo ' --> proses di sini..';

        $permintaan = [
            'modul'  => 'quran',
            'format' => 'json',
            'surat'  => $proses['surat'],
            'ayat'   => $proses['ayat'],
            'bahasa' => $proses['bahasa'],
        ];

        $respon = fathimah($permintaan);
        if ($datas = json_decode($respon, true)) {
            if ($datas['status'] == 'error') {
                $text = "🚫 `$datas[pesan]`";
            } else {
                if (isset($datas['bahasa']['proses']) and isset($datas['ayat']['proses'])) {
                    $text = '';
                    $proses['ayat2'] = '';

                    // buat list ayat2 untuk ditampilkan
                    foreach ($datas['ayat']['proses'] as $value) {
                        $proses['ayat2'] .= "$value,";
                    }
                    $proses['ayat2'] = substr($proses['ayat2'], 0, -1);

                    // tampilkan ayat2 sesuai bahasa yang diminta
                    foreach ($datas['bahasa']['proses'] as $idbahasa) {
                        foreach ($datas['ayat']['data'][$idbahasa] as $ayatdata) {
                            if ($idbahasa == 'ar') {
                                $text .= nomorkearab($ayatdata['ayat']).") $ayatdata[teks]\n";
                            } elseif ($idbahasa = 'id') {
                                $text .= "$ayatdata[ayat]. ".kurawalkeqn($ayatdata['teks'])."\n";
                            } else {
                                $text .= "$ayatdata[ayat]. $ayatdata[teks]\n";
                            }
                        }
                        $text .= "\n";
                    }

                    $text .= 'QS. *'.$datas['surat']['nama'].'* '.$datas['surat']['nomor'].":$proses[ayat2]";
                } else {
                    $text = '🚫 Tidak dapat diproses!';

                    if (isset($datas['bahasa']['error'])) {
                        $text .= "\n\n▶️ *Bahasa*";
                        foreach ($datas['bahasa']['error'] as $value) {
                            $text .= "\n 📌 `$value`";
                        }
                        $text .= "\nTidak ada.";
                    }

                    if (isset($datas['ayat']['error'])) {
                        $text .= "\n\n▶️ *Ayat*";
                        foreach ($datas['ayat']['error'] as $value) {
                            $text .= "\n 📌 `$value`";
                        }
                        $text .= "\nTidak ada.";
                    }

                    $text .= "\n\nQS. *".$datas['surat']['nama'].'* '.$datas['surat']['nomor'];
                    $text .= "\nTerdiri dari `".$datas['surat']['ayat'].'` ayat';
                }
            }
        } else {
            $text = '🚫 '.BAHASA_ERROR_REMOTE_TIDAK_DIKETAHUI;
        }


        $hasil = sendApiMessage($fidchat, $text, $fidpesan, 'Markdown', true);

        if ($bot['debug']) {
            print_r($hasil);
        }

        // print_r($respon);

        $lanjut = false;
    }
}
