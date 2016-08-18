<?php

if (! defined('HS')) 
	die('Tidak boleh diakses langsung.');

foreach (glob("plugins/*.php") as $filename) {
	$pecah = explode("/", $filename);
	$bot['plugins']['daftar'][] = substr($pecah[1],0,-4);
}
    