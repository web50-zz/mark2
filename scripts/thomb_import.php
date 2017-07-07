#!/usr/bin/env php5
<?php
/*
*  market  to market2  22052015
*/
$STOP_URI_INTERPRETER = true;

include('../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
$source_path = BASE_PATH.'/filestorage/import/';
echo("starts.....\r\n");
	$images = get_images($source_path);
	$j = 0;
	foreach($images as $key1=>$val2)
	{
		$j++;
		$thumb = $value;
		$source_absolute = $source_path.$val2;
		if(file_exists($source_absolute))
		{
			$res = array();
			$parts = explode('.',$val2);
			$art = $parts[0];
			$di = data_interface::get_instance('m2_item');
			$sql = "select * from m2_item where article = '$art'";
			$res = $di->_get($sql)->get_results();
			if(count($res) != 1)
			{
			echo(count($res)."\r\n");
			echo($val2."\r\n");
				var_dump($res);
				continue;
			}
			echo($j."\r\n");
				$args = array(
					'item_id'=>$res[0]->id,
					'file_type'=>3,
					'title'=>$res[0]->title,
					'comment'=>'Импорт ',
					'source'=>$source_absolute,
					'silent'=>'true',
				);
				var_dump($args);
				echo("Loading thumb\r\n");
				if(!($EMULATE == true))
				{
					$di = data_interface::get_instance('m2_item_files');
					$di->set_args($args);
					$res = $di->sys_set(true);
				}
		}
	}

echo($j."\r\n");


echo("done\r\n");




function get_images($source_path)
{
	echo('ee');
	$fl = scandir($source_path);
	unset($fl[0]);
	unset($fl[1]);
	return $fl;
}


?>
