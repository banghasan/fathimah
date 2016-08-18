<?php

if (!defined('HS')) {
    die('Tidak boleh diakses langsung.');
}

function setTigaChar($value)
{
    return str_pad($value, 3, '0', STR_PAD_LEFT);
}

function nomorkearab($value)
{
    $indo = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    return str_replace($indo, $arabic, $value);
}

function kurawalkeqn($value)
{
    return preg_replace("/{(\d+)}/", '(/qn$1)', $value);
}

function escmarkdown($value)
{
    $mark = ['_', '*', '`'];
    $subs = ['\\_', '\\*', '\\`'];

    return str_replace($mark, $subs, $value);
}

// JSON_PRETTY_PRINT 128
// JSON_UNESCAPED_UNICODE 256
// 384
