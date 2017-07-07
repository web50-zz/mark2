#!/usr/bin/env php5
<?php
/*
* Importer market  to market2 UI i25042014
* in default mode by default
* to  use make $EMULATE= false;
*/
// Замена  артикулов  с новых на старые
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
	if($value[0] != '' && $value[1] != '')
	{
		$di = data_interface::get_instance('m2_item');
		$di->_flush();
		$di->push_args(array('_sarticle'=>$value[0]));
		$res = $di->extjs_grid_json(false,false);
		$di->pop_args();
		if($res['total']  != 1)
		{
			echo("Not found for {$value[0]}\r\n");		
		}
		else
		{
			echo($res['records'][0]['id']." \r\n");
			$di = data_interface::get_instance('m2_item');
			$di->_flush();
			$args = array(
				'_sid'=>$res['records'][0]['id'],
				'article'=>$value[1]
				);
			$di->push_args($args);
			var_dump($args);
//			$it = $di->sys_set(true);
			$di->pop_args();
		}
	}
	else
	{
		echo("not needed\r\n");	
	}
}

echo("done\r\n");




function get_items()
{
	$cont = file('to_parse1.csv');
	foreach($cont as $key=>$value)
	{
		$tmp = explode(';',$value);
		$out[] = $tmp;
	}
	return $out;
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
