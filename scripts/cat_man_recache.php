#!/usr/bin/env php5
<?php
/*
* Рекэширование связки категории и производителей 28122016 
*/

$EMULATE = true;
$STOP_URI_INTERPRETER = true;

include('../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
echo("starts.....\r\n");
$di = data_interface::get_instance('m2_category_manufacturers');
$di->recache();
//$res = $di->get_manufacturers_for_category(380);
//var_dump($res);
?>
