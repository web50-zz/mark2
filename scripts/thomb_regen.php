#!/usr/bin/env php5
<?php
/*
*  market  to market2  22052015
*/

$STOP_URI_INTERPRETER = true;

include('../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
echo("starts.....\r\n");
$posts = get_items();
foreach($posts as $key=>$value)
{
	$di = data_interface::get_instance('m2_item_files');
	$post_args = array('_sid'=>$value->id,
			'size'=>$value->size,
			'regen'=>true,
			);
	$di->_flush();
	$di->set_args($post_args);
	$res = $di->sys_set(true);
	var_dump($res);
}

echo("done\r\n");




function get_items()
{
	$di = data_interface::get_instance('m2_item_files');
	$sql = 'select * from m2_item_files where file_type = 3';
	$di->_flush();
	$res = $di->_get()->get_results();
	return $res;
}


?>
