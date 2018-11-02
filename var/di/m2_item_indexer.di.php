<?php
/**
*
* @author	9@u9.ru 16012014	
* @package	SBIN Diesel
*/
class di_m2_item_indexer extends di_index_processor
{
	public $title = 'm2: Item Indexer';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	public $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	public $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	public $name = 'm2_item_indexer';

	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'last_changed' => array('type'=>'datetime'),
		'item_id' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'article' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'not_available' => array('type' => 'integer'),
		'category_list'=> array('type'=>'string'),
		'linked_items_list'=> array('type'=>'string'),
		'files_list'=>array('type'=>'string'),
		'text_list'=>array('type'=>'string'),
		'prices_list'=>array('type'=>'string'),
		'manufacturers_list'=>array('type'=>'string'),
		'chars_list'=>array('type'=>'string'),
		'order' => array('type'=>'integer'),
		'meta_title'=>array('type'=>'string'),

	);

	public $settings = array(
		'index_target'=>array(
			'di_name'=>'m2_item',//9* di которыйиндексируем
			'fields_to_index'=>array(
					'id'=>array(
						'alias'=>'item_id'
					),
					'article'=>'',
					'title'=>'',
					'name'=>'',
					'order'=>'',
					'not_available'=>'',
					'meta_title'=>'',
				)

			),
			'composite_fields'=>array(
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'files_list',
					'di_name'=>'m2_item_files',
					'di_key'=>'item_id',
					'order_field'=>'order',
					'order_type'=>'asc',
					'fields'=>array(
						'real_name'=>'',
						'id'=>'',
						'file_type'=>'',
						'order'=>'',
						'type'=>''
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'text_list',
					'di_name'=>'m2_item_text',
					'di_key'=>'item_id',
					'fields'=>array(
						'title'=>'',
						'content'=>'',
						'type'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'prices_list',
					'di_name'=>'m2_item_price',
					'di_key'=>'item_id',
					'fields'=>array(
						'type'=>'',
						'price_value'=>'',
						'content'=>'',
						'currency'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'linked_items_list',
					'di_name'=>'m2_item_links',
					'di_key'=>'item_id',
					'order_field'=>'order',
					'order_type'=>'asc',
					'fields'=>array(
						'type'=>'',
						'linked_item_id'=>'',
						'order'=>'',
						'title'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'manufacturers_list',
					'di_name'=>'m2_item_manufacturer',
					'di_key'=>'item_id',
					'fields'=>array(
						'manufacturer_id'=>'',
						),
					'joins'=>array(
						'm2_manufacturers'=>array(
								'source_key'=>'manufacturer_id',
								'remote_key'=>'id',
								'fields'=>array(
									'title'=>'title',
									'name'=>'name',
								)
							)
						)

					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'chars_list',
					'di_name'=>'m2_chars',
					'di_key'=>'m2_id',
					'order_field'=>'order',
					'order_type'=>'asc',
					'fields'=>array(
						'type_id'=>'',
						'type_value'=>'',
						'str_title'=>'',
						'variable_value'=>'',
						'order'=>'',
						'brief'=>'',
						'type_value_str'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'category_list',
					'di_name'=>'m2_item_category',
					'di_key'=>'item_id',
					'order_field'=>'category_order',
					'order_type'=>'asc',
					'fields'=>array(
						'category_id'=>'',
						'category_order'=>'',
						),
					'joins'=>array(
						'm2_category'=>array(
								'source_key'=>'category_id',
								'remote_key'=>'id',
								'fields'=>array(
									'title'=>'title',
									'uri'=>'uri'
								)
							)
						)
					),
			)	
	);



	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	protected function sys_batch_reindex(){
		try{
			$this->batch_reindex();
			response::send(array('success' => true,'msg'=>'Переиндексировано'),'json');
		}catch(Exception $e)
		{
			response::send(array('success' => false,'msg'=>$e->getMessage()),'json');
		}
	}
	public function batch_reindex(){
		$time_start = microtime(true); 
		$items = $this->prepare_data();
		$flds = array_keys($this->settings['index_target']['fields_to_index']);
		$out_vals = '';
		$j = 0;
		$this->connector->exec('truncate table m2_item_indexer');
		foreach($this->keys_index['m2_item'] as $k=>$v)
		{
			$j++;
			$tmp = array();
			$tmp['id'] = '';
			foreach($flds as $k1=>$v1)
			{
				$t = (array)$v;
				if($v1 == 'id')
				{
					$tmp['item_id'] = $t[$v1];
				}
				else
				{
					$tmp[$v1] = str_replace('"','\"',str_replace("'","\'",$t[$v1]));
				}
			}
			foreach($this->settings['composite_fields'] as $k2=>$v2)
			{
				$t2 = $this->keys_index[$v2['di_name']][$tmp['item_id']];
				if(count($t2) > 0)
				{
					$tmp[$v2['index_field_name']] = $this->json_enc($t2);		
				}
				else
				{
					$tmp[$v2['index_field_name']] = '[]';		
				}
			}
				$out_vals[] = '("'.implode('","',array_values($tmp)).'",now())';
				if($k == 1)
				{
					$out_flds = $tmp;
					$out_flds['last_changed'] = 1;
				}
			if($j  == 3000) 
			{
				$j = 0;
				$flds_a = '(`'.implode('`,`',array_keys($out_flds)).'`)';
				$sql = 'insert into m2_item_indexer '.$flds_a.' values '.implode(",\r\n",$out_vals);
				$this->connector->exec($sql);
				$out_vals = array();
			}

		}
		$flds_a = '(`'.implode('`,`',array_keys($out_flds)).'`)';
		$sql = 'insert into m2_item_indexer '.$flds_a.' values '.implode(",\r\n",$out_vals);
		$this->connector->exec($sql);
		$time_end = microtime(true);
		$di = data_interface::get_instance('m2_url_indexer');
		$di->reindex();
		$execution_time = ($time_end - $time_start)/60;
	}

	public function prepare_data()
	{
		$flds = array_keys($this->settings['index_target']['fields_to_index']);
		$main_table = $this->settings['index_target']['di_name'];
		$sql = 'select `'.implode('`,`',$flds).'` from '.$main_table.';';
		$res = $this->_get($sql)->get_results();
		$this->keys_index = array();
		$this->keys_index[$main_table] = $res;
		foreach($this->settings['composite_fields'] as $k=>$v)
		{
			$joins = array();
			$fields = array();
			$fields1 = array();
			$JOINS = '';
			$ORDER  = '';
			$fields1 = array_keys($v['fields']);
			$fields1[] = $v['di_key'];
			$this->keys_index[$v['di_name']] = array();
			foreach($fields1 as $k4=>$v4)
			{
				$fields[] = 't.`'.$v4.'`';
			}

			$table = $v['di_name'];
			if(array_key_exists('joins',$v))
			{
				foreach($v['joins'] as $k1=>$v1)
				{
					$joins[] = $k1 .' a on t.'.$v1['source_key'].' = a.`'.$v1['remote_key'].'`';
					foreach($v1['fields'] as $k2=>$v2)
					{
						$fields[] = 'a.'.$v2;
					}
				}
			}
			if(count($joins) >0)
			{
				$JOINS = 'LEFT JOIN '.implode(',',$joins);
			}
			if(array_key_exists('order_field',$v))
			{
				$ORDER = ' order by `'.$v['order_field'] .'` '.$v['order_type'];
			}
			$sql = 'SELECT '.implode(",",$fields).' FROM '.$table.' t '.$JOINS.' '.$ORDER.';';
			$tmp = $this->_get($sql)->get_results();
			foreach($tmp as $k5=>$k6)
			{
				$t = (array)$k6;
				$item_id = $t[$v['di_key']];
				if($item_id > 0)
				{
					if(!array_key_exists($item_id,$this->keys_index[$v['di_name']]))
					{
						$this->keys_index[$v['di_name']][$item_id] = array();
					}
					unset($k6->item_id);
					unset($k6->m2_id);
					$this->keys_index[$v['di_name']][$item_id][] = $k6;
				}
			}
		}
//		var_dump('data_mined');
	}

}
?>
