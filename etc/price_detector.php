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
//	echo("\r\n iteration: $j\r\n");
	$di = data_interface::get_instance('m2_item_price');
	$di->_flush();
	$di->push_args(array('_sitem_id'=>$value->id));
	$di->where = '`type` in(5,6,8)';
	$res = $di->extjs_grid_json(false,false);
//	echo($res['total']."\r\n");
	if($res['total'] == 0)
	{
		echo($value->id.';'.$value->article."\r\n");
	}

}

echo("done\r\n");




function get_items()
{
	$di = data_interface::get_instance('m2_item');
 	$results = $di->_get($sql)->get_results();
	return $results;
}


?>
