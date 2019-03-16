#!/usr/bin/env php5
<?php
/*
*  market  to market2  17032019  Миграция файлов по списку артикулов в CSV с одного сервера на другой
*/
$STOP_URI_INTERPRETER = true;
ini_set('session.save_path', '/var/www/u0500628/data/www/tovarisch24.ru/instances/mark2/scripts/peps_file_migration/tmp');
include('../../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
$source_path = BASE_PATH.'/filestorage/import/';
echo("starts.....\r\n");
$c = new importer();
$c->files_source_path = '/var/www/u0500628/data/www/tovarisch24.ru/filestorage/mark2/napitki_files_import/m2_item_files/';
$c->init();

var_dump(count($c->new_item_index));
var_dump(count($c->old_item_index));
echo("done\r\n");
// sys_ unset в m2_item_files.di  надо сделать public временно после импорта вернуть в protected

class importer
{
	public $old_item_index = array();
	public $new_item_index = array();
	public $articles = array();
	public $new_item_files = array();
	public $old_item_files = array();
	public $files_source_path = '';//место где лежат файлы которые надо импортировать
	
	public function init()
	{
		$this->get_old_item_index();
		$this->get_old_item_files();
		$this->get_new_item_index();
		$this->get_new_item_files();
		$this->get_articles_to_move();
		//$this->remove_old_files();
		$this->add_new_files();
	}
	public function remove_old_files()
	{
		$j = 0;
		$f = 0;
		echo("Starting deletion old files\r\n");
		foreach($this->articles as $k=>$v)
		{
			if(array_key_exists($v,$this->new_item_index))
			{
				if(array_key_exists($this->new_item_index[$v],$this->new_item_files))
				{
					foreach($this->new_item_files[$this->new_item_index[$v]] as $k2=>$v2)
					{
						      $f++;
						      echo('removing file id '. $v2."\r\n");
						      $di = data_interface::get_instance('m2_item_files');
						      $di->set_args(array('_sid'=>$v2));
						      $data = $di->sys_unset(true);
					}
				}
				else
				{
//					echo("not found for $v \r\n");
				}
			}
			else
			{
				$j++;
			}
		}
		echo("missed items $j \r\n");
		echo("removed old files $f \r\n");
	}

	public function add_new_files()
	{
		$j = 0;
		$d = 0;
		$i = 0;
		$skip = 1;
		foreach($this->articles as $k=>$v)
		{
			if(array_key_exists($v,$this->old_item_index))
			{
				$i++;
				if(array_key_exists($v,$this->new_item_index))
				{
					$j++;
					if(array_key_exists($this->old_item_index[$v],$this->old_item_files))
					{
						foreach($this->old_item_files[$this->old_item_index[$v]] as $k1=>$v1)
						{
							if($v1->real_name != '')
							{
								if(file_exists($this->files_source_path.$v1->real_name))
								{
									$d++;
									if($d == '3919')
									{
										$skip = 2;
									}
									if($skip == 2)
									{
										$args = array();
										$args = array(
											'item_id'=>$this->new_item_index[$v],
											'name'=>$v1->name,
											'file_type'=>3,
											'source'=>$this->files_source_path.$v1->real_name,
										);
										echo("iteration $d adding for article $v\r\n");
										$di = data_interface::get_instance('m2_item_files');
										$di->set_args($args);
										$data = $di->sys_set(true);
										//var_dump($data);
									}
								}
							}
						}
					}
				}		
			}
		}
		echo("Get $i items from old db.\r\nFound $j items in new db \r\n");
		echo("Get $d files from old db.\r\n");
	}

	public function get_articles_to_move()
	{
		$data = file('list.csv');
		foreach($data as $k=>$v)
		{
			$this->articles[] = trim($v);
		}
		unset($data);
	}
	public function get_old_item_index()
	{
		$old = data_interface::set_query('SELECT
		`id` AS `id`,
		`article` AS `article`
		from m2_item
		ORDER BY `ID` ASC',
		array(
		'id' => array('type' => 'string'),
		'article' => array('type' => 'string'),
		),
		'old',
		'db1'
		);

		$old->_get();
		$res = $old->get_results();
		foreach($res as $k=>$v)
		{
			$this->old_item_index[$v->article] = $v->id;
		}

	}
	public function get_old_item_files()
	{
		$old = data_interface::set_query('SELECT
		`id` AS `id`,
		`item_id` AS `item_id`,
		`real_name` AS `real_name`,
		`name` AS `name`
		from m2_item_files
		ORDER BY `ID` ASC',
		array(
		'id' => array('type' => 'string'),
		'item_id' => array('type' => 'string'),
		'real_name' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		),
		'old',
		'db1'
		);

		$old->_get();
		$res = $old->get_results();
		foreach($res as $k=>$v)
		{
			$this->old_item_files[$v->item_id][] = $v;
		}
		var_dump(count($this->old_item_files));
	}


	public function get_new_item_index()
	{
		glob::set('dbh',false);
		$new = data_interface::set_query('SELECT
		`id` AS `id`,
		`article` AS `article`
		from m2_item
		ORDER BY `ID` ASC',
		array(
		'id' => array('type' => 'string'),
		'article' => array('type' => 'string'),
		),
		'localhost',
		'db1'
		);

		$new->_get();
		$res = $new->get_results();
		foreach($res as $k=>$v)
		{
			$this->new_item_index[$v->article] = $v->id;
		}

	}
	public function get_new_item_files()
	{
		$di = data_interface::get_instance('m2_item_files');
		$di->_get();
		$res = $di->get_results();
		foreach($res as $k=>$v)
		{
			$this->new_item_files[$v->item_id][] = $v->id;
		}
	}

}

?>
