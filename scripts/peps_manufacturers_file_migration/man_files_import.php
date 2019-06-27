#!/usr/bin/env php5
<?php
/*
*  market  to market2  17032019  Миграция файлов для производителей из другой базы
*/

$old_files_path = '/server/web/documents/nptk.u9.ru/filestorage/mark2/m2_manufacturer_files/';//путь к каталогу сос тарыми файлами
$current_base_path = '/server/web/documents/nptk.u9.ru/';
//берем из с тарой датабазы файлы для производителей в новой датабазе. Ищем по названию.
// То есть для каждого производителя в новой датабазе ищем такогоже в старой и файлы которые там на него повешены вешаем на его аналог в новой базе
// старая db должна быть ппописана как то так в  etc/db.cfg.php
/*
		'old' => array(
			'type' => 'mysql',
			'host' => 'localhost',
			'charset' => CHARSET,
			'user' => 'nptk_u9_ru',
			'pass' => 'nptk_u9_ru',
			'dbs' => array(
				'db1' => 'nptk_u9_ru',
				)
			),
*/
$STOP_URI_INTERPRETER = true;
ini_set('session.save_path', $current_base_path.'/filestorage/tmp/');//путь для файлов сессии временных
include('../../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
echo("starts.....\r\n");


$c = new importer();
$c->files_source_path = $old_files_path;
$c->init();

//var_dump(count($c->new_item_index));
//var_dump(count($c->old_item_index));
echo("done\r\n");
// sys_ unset в m2_item_files.di  надо сделать public временно после импорта вернуть в protected

class importer
{
	public $old_item_index = array();
	public $new_item_index = array();
	public $found_in_old = array();
	public $new_item_files = array();
	public $old_item_files = array();
	public $files_source_path = '';//место где лежат файлы которые надо импортировать
	
	public function init()
	{
		$this->get_old_item_index();
		$this->get_old_item_files();
		$this->get_new_item_index();
		$this->get_new_item_files();
		$this->get_files_to_move();
		$this->remove_prev_files();
		$this->add_new_files();
	}
	public function remove_prev_files()
	{
		$j = 0;
		$f = 0;
		echo("Starting deletion prev files\r\n");
		foreach($this->found_in_old as $k=>$v)
		{
			//удаляем файлы если они есть в новой базе и в старой базе. Если они есть в новой, но нет в старой, то ничего не удаляем.
				if(array_key_exists($v['new_id'],$this->new_item_files))
				{
					if(array_key_exists($v['old_id'],$this->old_item_files))
					{
						echo('prev files exist for '.$v['new_id'] .' '.$v['old_id']."\r\n");
						foreach($this->new_item_files[$v['new_id']] as $k2=>$v2)
						{
							      $f++;
							      echo('removing file id '. $v2."\r\n");
							      $di = data_interface::get_instance('m2_manufacturer_files');
							      $di->set_args(array('_sid'=>$v2));
							      $data = $di->sys_unset(true);
						}
					}
				}
		}
		echo("removed old files $f \r\n");
	}

	public function add_new_files()
	{
		$j = 0;
		$d = 0;
		$i = 0;
		$skip = 1;
		foreach($this->found_in_old as $k=>$v)
		{
			if(array_key_exists($v['old_id'],$this->old_item_files))
			{
				foreach($this->old_item_files[$v['old_id']] as $k1=>$v1)
				{
					if($v1->real_name != '')
					{
						if(file_exists($this->files_source_path.$v1->real_name))
						{
							$d++;
								$args = array();
								$args = array(
									'item_id'=>$v['new_id'],
									'name'=>$v1->name,
									'file_type'=>7,
									'source'=>$this->files_source_path.$v1->real_name,
								);
								echo("iteration $d adding file for item ".$v['new_id']." id in old db: ".$v['old_id']."\r\n");
								$di = data_interface::get_instance('m2_manufacturer_files');
								$di->set_args($args);
								$data = $di->sys_set(true);
						}
					}
				}
			}
		}
		echo("Copied $d files from old db.\r\n");
	}

	public function get_files_to_move()
	{
		foreach($this->new_item_index as $k=>$v)
		{
			if(array_key_exists($k,$this->old_item_index))
			{
				$tmp = array();
				$tmp['old_id'] = $this->old_item_index[$k];
				$tmp['new_id'] = $v;
				$this->found_in_old[]= $tmp;
			}
		}
		echo(count($this->found_in_old)." manufacturers from current db was detected in old db.\r\n");
	}
	public function get_old_item_index()
	{
		$old = data_interface::set_query('SELECT
		`id` AS `id`,
		`title` AS `title`
		from m2_manufacturers
		ORDER BY `ID` ASC',
		array(
		'id' => array('type' => 'string'),
		'title' => array('type' => 'string'),
		),
		'old',
		'db1'
		);

		$old->_get();
		$res = $old->get_results();
		foreach($res as $k=>$v)
		{
			$this->old_item_index[$v->title] = $v->id;
		}

	}
	public function get_old_item_files()
	{
		$old = data_interface::set_query('SELECT
		`id` AS `id`,
		`item_id` AS `item_id`,
		`real_name` AS `real_name`,
		`name` AS `name`
		from m2_manufacturer_files
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
	}


	public function get_new_item_index()
	{
		glob::set('dbh',false);
		$new = data_interface::set_query('SELECT
		`id` AS `id`,
		`title` AS `title`
		from m2_manufacturers
		ORDER BY `ID` ASC',
		array(
		'id' => array('type' => 'string'),
		'title' => array('type' => 'string'),
		),
		'localhost',
		'db1'
		);

		$new->_get();
		$res = $new->get_results();
		foreach($res as $k=>$v)
		{
			$this->new_item_index[$v->title] = $v->id;
		}

	}
	public function get_new_item_files()
	{
		$di = data_interface::get_instance('m2_manufacturer_files');
		$di->_get();
		$res = $di->get_results();
		foreach($res as $k=>$v)
		{
			$this->new_item_files[$v->item_id][] = $v->id;
		}
	}

}

?>
