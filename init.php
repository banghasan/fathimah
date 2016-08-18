<?php

if (! defined('HS')) 
	die('Tidak boleh diakses langsung.');

if (!file_exists('data/setting.php')) 
	die("
===========================
ERROR: Fathimah Engine belum diinstall..!
---------------------------
Jalankan terlebih dahulu: php install.php
===========================
");

include 'data/setting.php';
include 'konstanta.php';

if (!isset($token)) die(BAHASA_ERROR_TOKEN_TAK_KETEMU);

include 'src/fungsi.php';
include 'src/api.php';
include 'src/fathimah.php';
include 'src/proses.php';