#!/usr/bin/env php5
<?php
/*
* Рекэширование связки категории и характеристик 21042017 
*/

$EMULATE = true;
$STOP_URI_INTERPRETER = true;

include('../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
echo("starts.....\r\n");
$di = data_interface::get_instance('m2_chars_in_category');
$di->recache();
//$res = $di->get_manufacturers_for_category(380);
//var_dump($res);
?>
