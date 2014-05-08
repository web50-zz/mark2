#!/usr/bin/env php5
<?php
/*
* Importer market  to market2 UI i25042014
* in default mode by default
* to  use make $EMULATE= false;
*/

//$EMULATE = true;
$source_wp_path = '/server/web/documents/avik.u9.ru/images/data/catalog/ru';
$STOP_URI_INTERPRETER = true;

include('../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
echo("starts.....\r\n");
$posts = get_items();

foreach($posts as $key=>$value)
{
	$j++;
	echo("\r\n iteration: $j\r\n");
	echo("id: {$value->id} {$value->name}  {$value->articul} \r\n");
	$post_args = array(
		'_sid'=>$value->id,
		'order'=>$value->order,
	);
	if(!($EMULATE == true))
	{
		$di = data_interface::get_instance('m2_item');
		$di->set_args($post_args);
		$res = $di->sys_set(true);
	}
	else{
	}
	var_dump($res);
	echo("Post reindexed\r\n");
}

echo("done\r\n");




function get_items()
{
	$di = data_interface::get_instance('m2_item');
	$di->what = array('id','order');
	$results = $di->_get()->get_results();
	return $results;
}

?>
