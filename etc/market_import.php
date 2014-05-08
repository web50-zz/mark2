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
$cat = get_cat_map();
$posts = get_items();

foreach($posts as $key=>$value)
{
	$j++;
	echo("\r\n iteration: $j\r\n");
	echo("id: {$value->id} {$value->name}  {$value->articul} \r\n");
	$post_args = array(
		'title'=>$value->name,
		'name'=>$value->id,
		'article'=>$value->articul,
	);
	if(!($EMULATE == true))
	{
		$di = data_interface::get_instance('m2_item');
		$di->set_args($post_args);
		$res = $di->sys_set(true);
		$new_post_id = $res['data']['id'];
	}
	else{
		$new_post_id = $j;
	}
	echo("Post imported new ID: {$new_post_id}\r\n");
	// prices
		$di = data_interface::get_instance('m2_item_price');
		$args =  array(
			'item_id'=>$new_post_id,
			'type'=> 7,
			'price_value'=> $value->price,
			'content'=>'Без учета скидки',
			'currency'=>3
		);
		if($value->item_id>0)
		{
			$args['content'] = '';
		}
		if(!($EMULATE == true))
		{
			$di->set_args($args);
			$res = $di->sys_set(true);
		}
	echo("Prices imported for ID: {$new_post_id}\r\n");
	// images
	$images = get_images($value->id);
	foreach($images as $key1=>$val2)
	{
		$thumb = $value;
		$source_absolute = $source_wp_path.'/'.$value->id.'/'.$val2;
		if(file_exists($source_absolute))
		{
			$args = array(
				'item_id'=>$new_post_id,
				'file_type'=>3,
				'title'=>'Картинка',
				'comment'=>'Импорт из вордпресса',
				'source'=>$source_absolute,
				'silent'=>'true',
			);
			echo("Loading thumb\r\n");
			if(!($EMULATE == true))
			{
				$di = data_interface::get_instance('m2_item_files');
				$di->set_args($args);
				$res = $di->sys_set(true);
			}
		}
	}
	echo(" Loading description ID: {$new_post_id}\r\n");
		if($value->description_full != '')
		{
			$di = data_interface::get_instance('m2_item_text');
			$args =  array(
				'item_id'=>$new_post_id,
				'type'=> 3,
				'content'=> $value->description_full,
			);
			if(!($EMULATE == true))
			{
				$di->set_args($args);
				$res = $di->sys_set(true);
			}
		}
		if($value->description != '')
		{
			$di = data_interface::get_instance('m2_item_text');
			$args =  array(
				'item_id'=>$new_post_id,
				'type'=> 5,
				'content'=> $value->description,
			);
			if(!($EMULATE == true))
			{
				$di->set_args($args);
				$res = $di->sys_set(true);
			}
		}
	echo(" Loading availability props ID: {$new_post_id}\r\n");
		$di = data_interface::get_instance('m2_chars');
		$args = array(
			'm2_id'=>$new_post_id,
			'type_id'=>94,
			'char_type'=>1,
			);
		if($value->item_id>0)
		{
			echo("В наличии\r\n");
			$args['type_value'] = 105;
			$args['type_value_str'] = 'В наличии';
		}else{
			$args['type_value'] = 104;
			$args['type_value_str'] = 'На заказ';
		}
		if(!($EMULATE == true))
		{
			$di->set_args($args);
			$res = $di->sys_set(true);
		}
	echo(" Loading categories  ID: {$new_post_id}\r\n");
		$cats = get_cats($value->id);
		foreach($cats as $key3=>$value3)
		{
			$di = data_interface::get_instance('m2_item_category');
			echo("Assigning post {$new_post_id} to category {$key3}\r\n");
			$args =  array(
				'item_id'=>$new_post_id,
				'category_id'=> $key3,
			);
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
	$sql = 'SELECT i.id,name,articul,price,description,description_full, p.item_id
		        FROM catalog_items i
			LEFT JOIN catalog_items_params_values_bunches p ON p.item_id = i.id
			GROUP BY i.id limit 0,100000
		';
	$old = data_interface::set_query($sql,
		array(
			'id'=>array('type'=>'integer'),
			'name'=>array('type'=>'string'),
			'articul'=>array('type'=>'string'),
			'price'=>array('type'=>'string'),
			'description'=>array('type'=>'string'),
			'description_full'=>array('type'=>'string'),
		),
		'old',
		'db1'
	);
	$results = $old->_get($sql)->get_results();
	return $results;
}

function get_images($id,$exist = 0)
{
	$sql = "SELECT value
		        FROM catalog_items_params_values where param_id in(6,11,12,13,15,16) and item_id = {$id}
		";
	if($exist>0)
	{
	$sql = "SELECT value
		        FROM catalog_items_params_values where param_id in(17,18,19,20) and item_id = {$id}
		";
	}
	$old = data_interface::set_query($sql,
		array(
			'value'=>array('type'=>'string'),
		),
		'old',
		'db1'
	);
	$results = $old->_get($sql)->get_results();
	foreach($results as $key=>$value)
	{
		$parts = explode('_',$value->value);
		$out[] = $parts[1];
	}
	return $out;
}

function get_cat_map()
{
		$sql = 'SELECT id,name
		        FROM catalog_groups where CHAR_LENGTH(hiercode) > 2
		';
	$old = data_interface::set_query($sql,
		array(
			'id'=>array('type'=>'integer'),
			'name'=>array('type'=>'string'),
		),
		'old',
		'db1'
	);
	$results = $old->_get($sql)->get_results();
	$out = array();
	foreach($results as $key=>$value)
	{
		$sql = "SELECT id,title from m2_category where title = '{$value->name}' and level = 2";
		echo("{$value->name}\r\n");
		$new = data_interface::set_query($sql,
			array(
				'id'=>array('type'=>'integer'),
				'name'=>array('type'=>'string'),
			),
			'localhost',
			'db1'
		);
		$results2 = $new->_get($sql)->get_results();
		if(count($results2)>0)
		{
			$results[$key]->map = $results2[0]->id ;

		}else{
	//		dbg::show($value);
		}
		echo("pushing {$value->id}  {$results[$key]->map}\r\n\r\n");
		$out[$value->id] = $results[$key];
	}
	return $out;
}

function get_cats($id)
{
	global $cat;
		$sql = "SELECT item_id,group_id
		        FROM catalog_groups_items where item_id = {$id}";
	$old = data_interface::set_query($sql,
		array(
			'item_id'=>array('type'=>'integer'),
			'group_id'=>array('type'=>'integer'),
		),
		'old',
		'db1'
	);
	$results = $old->_get($sql)->get_results();
	$res = array();
	foreach($results as $key=>$value)
	{

		if($cat[$value->group_id]->map>0)
		{
			$res[$cat[$value->group_id]->map] = 1;
		}
	}
	return $res;
}


	function rus2translit($string)
	{
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);

		return strtr($string, $converter);
	}
	 function str2url($str)
	{
		// переводим в транслит
		$str = rus2translit($str);

		// в нижний регистр
		$str = strtolower($str);

		// заменям все пробелы на "_", а ненужное удаляем
		$str = preg_replace(array('/\s+/', '/[^-a-z0-9_]+/u'), array('-', ''), $str);

		// удаляем начальные и конечные '-'
		$str = trim($str, "-");

		return $str;
	}

?>
