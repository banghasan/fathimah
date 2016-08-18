<?php

define('HS', true);

include_once 'init.php';

function printApiUpdates($result)
{
    foreach ($result as $obj) {
        if ($GLOBALS['bot']['debug']) {
            echo PHP_EOL.'DEBUG: '.$obj['message']['text'].PHP_EOL;
        }
        processMessage($obj);
        $last_id = $obj['update_id'];
    }

    return $last_id;
}


echo PROGRAMNAME.' '.PROGRAMVER.PHP_EOL.BAHASA_BARIS.BAHASA_SUKSES_POLL.date('Y-m-d H:i:s').PHP_EOL;


$last_id = null;
while (true) {
    $result = getApiUpdates($last_id);
    if (!empty($result)) {
        echo '+';
        $last_id = printApiUpdates($result);
    } else {
        echo '-';
    }

 // sleep(1);
}
