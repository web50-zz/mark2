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
	$item_id = 0;
	var_dump($cont);
	if($cont[0] == '')
	{
		continue;
	}
	if($cont[2] == '')
	{
		continue;
	}
	$item_id = get_m2_item($cont[2]);
	if($item_id == 0)
	{
		echo("next \r\n");
		continue;
	}
	echo("id: {$item_id} {$cont[0]} \r\n");
	echo(" Loading description ID: {$new_post_id}\r\n");
			$di = data_interface::get_instance('m2_item_price');
			$args = array('_sitem_id'=>$item_id,'_stype'=>array('7'));
			$di->_flush();
			$di->set_args($args);
			$rr = $di->extjs_grid_json(false,false);
			$ids = array();
			foreach($rr['records'] as $key=>$value)
			{
				$ids[] = $value['id'];
			}
			var_dump($ids);			
			$di = data_interface::get_instance('m2_item_price');
			$args = array('_sid'=>$ids);
			if(!($EMULATE == true))
			{
				$di->sys_unset(true);
			}
			if($cont[9] != '')
			{
				$di = data_interface::get_instance('m2_item_price');
				$args =  array(
					'item_id'=> $item_id,
					'type'=> 7,
					'price_value'=> $cont[9],
					'currency'=>'3',
				);
				var_dump($args);			
				if(!($EMULATE == true))
				{
					$di->set_args($args);
					$res = $di->sys_set(true);
				}
			}
}

echo("done\r\n");




function get_items()
{
	$results = file('reg_redy_p.csv'); 
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
