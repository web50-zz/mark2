#!/usr/bin/env php5
<?php
/*
* Importer market  to market2 UI i25042014
* in default mode by default
* to  use make $EMULATE= false;
*/
//last id  1944
//$EMULATE = true;
$STOP_URI_INTERPRETER = true;

include('../../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
echo("starts.....\r\n");
$posts = get_items();
foreach($posts as $key=>$value)
{
	$j++;
	echo("\r\n iteration: $j\r\n");
	$cont = explode(';',$value);
//	dbg::show($cont);
	$item_id = 0;
	if($cont[0] == '')
	{
		continue;
	}
	$item_id = get_m2_item($cont[0]);
	if($item_id == 0)
	{
		echo("next \r\n");
		continue;
	}
	echo("id: {$item_id} {$cont[0]} \r\n");
	echo(" Loading description ID: {$new_post_id}\r\n");
			$di = data_interface::get_instance('m2_item_price');
			$args =  array(
				'item_id'=> $item_id,
				'type'=> 5,
				'price_value'=> $cont[6],
				'currency'=>'3',
			);
			if(!($EMULATE == true))
			{
				$di->set_args($args);
				$res = $di->sys_set(true);
			}
			$di = data_interface::get_instance('m2_item_price');
			$args =  array(
				'item_id'=> $item_id,
				'type'=> 6,
				'price_value'=> $cont[7],
				'currency'=>'3',
			);
			if(!($EMULATE == true))
			{
				$di->set_args($args);
				$res = $di->sys_set(true);
			}
			$di = data_interface::get_instance('m2_item_price');
			$args =  array(
				'item_id'=> $item_id,
				'type'=> 7,
				'price_value'=> $cont[8],
				'currency'=>'3',
			);
			if(!($EMULATE == true))
			{
				$di->set_args($args);
				$res = $di->sys_set(true);
			}

}

echo("done\r\n");




function get_items()
{
	$results = file('price_c.csv'); 
	return $results;
}

function get_m2_item($article = 0)
{
//		echo($article."\r\n");
		$di = data_interface::get_instance('m2_item');
		$di->set_args(array('_sarticle'=>$article));
		$di->_flush();
		$res = $di->extjs_grid_json(false,false);
//		echo('total '.$res['total']."\r\n");
		if($res['total'] != 1)
		{
//			echo("error \r\n");
			return 0;
		}
		return $res['records'][0]['id'];

}

?>
