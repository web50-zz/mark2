<?php
/**
*
* @author	9@u9.ru 16012014	
* @package	SBIN Diesel
*/
class di_m2_item_indexer extends data_interface
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
		'item_id' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'article' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'not_available' => array('type' => 'integer'),
		'files_list'=>array('type'=>'string'),
		'text_list'=>array('type'=>'string'),
		'prices_list'=>array('type'=>'string'),
		'manufacturers_list'=>array('type'=>'string'),
		'chars_list'=>array('type'=>'string'),
		'order' => array('type'=>'integer'),

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
					'not_available'=>'',
				)

			),
			'composite_fields'=>array(
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'files_list',
					'di_name'=>'m2_item_files',
					'di_key'=>'item_id',
					'fields'=>array(
						'real_name'=>'',
						'file_type'=>'',
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
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'manufacturers_list',
					'di_name'=>'m2_item_manufacturer',
					'di_key'=>'item_id',
					'fields'=>array(
						'manufacturer_id'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'chars_list',
					'di_name'=>'m2_chars',
					'di_key'=>'m2_id',
					'fields'=>array(
						'type_id'=>'',
						'str_title'=>'',
						'variable_value'=>'',
						'target_type'=>'',
						)
					)

			)	
	);



	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	public function update_record($id)
	{
		// Собираем основные данные
		$di = data_interface::get_instance($this->settings['index_target']['di_name']);
		$di->_flush(true);
		$what = array();

		foreach($this->settings['index_target']['fields_to_index'] as $key=>$value)
		{
				$what[] = $key;
		}

		foreach($this->settings['composite_fields'] as $key=>$value)
		{
			if($value['type'] == 'records_by_key')
			{
				$composites[$value['index_field_name']] = $this->get_by_key($id,$value);
			}

		}
		$di->what = $what;
		$di->push_args(array('_sid' => $id));
		$di->_get();
		$data = (array)$di->get_results(0);
		$di->pop_args();
		foreach($this->settings['index_target']['fields_to_index'] as $key=>$value)
		{
				if($value == '')
				{
					$args[$key] = $data[$key];
				}
				if(is_array($value))
				{
					if($value['alias'] != '')
					{
						$args[$value['alias']] = $data[$key];
					}
				}					
		}
		foreach($composites as $key=>$value)
		{
			$args[$key] = $value;
		}

		$this->_flush();
		$this->push_args($args);
		$this->set_args(array('_sitem_id' => $id), true);
		$this->insert_on_empty = true;
		$this->_set();
		$this->pop_args();

	}

	public function get_by_key($id,$input)
	{
		$what = array();
		foreach($input['fields'] as $key2=>$value2)
		{
			$what[] = $key2;
		}
		$di = data_interface::get_instance($input['di_name']);
		$di->_flush(true);
		$di->what = $what;
		$di->push_args(array('_s'.$input['di_key'] => $id));
		$di->connector->fetchMethod = PDO::FETCH_ASSOC;
		$di->_get();
		$data = (array)$di->get_results();
		$di->pop_args();
	//	$ret = json_encode($data);
		$ret = $this->json_enc($data);
		return $ret;
	}

	public function index_target_set($eObj, $ids, $args)
	{
		if (!is_array($ids) && $ids > 0)
		{
			$this->update_record($ids);
		}
		else if (is_array($ids))
		{
			foreach ($ids as $id)
			{
				$this->update_record($id);
			}
		}
		else
		{
			// Some error, because unknown const_mat ID
		}
	}

	public function index_target_unset($eObj, $ids, $args)
	{
		if (!empty($args['_sid']))
		{
			$this->_flush();
			$this->push_args(array(
				'_sitem_id' => $args['_sid'],
			));
			$this->_unset();
			$this->pop_args();
		}
	}


	public function update_field($id,$value)
	{
		if(!($id>0))
		{
			return;
		}
		$args[$value['index_field_name']] = $this->get_by_key($id,$value);
		$args['_sitem_id'] = $id;
		$this->_flush();
		$this->push_args($args);
		$this->insert_on_empty = false;
		$this->_set();
		$this->pop_args();
	}

	public function index_field_set($eObj, $ids, $args)
	{
		$di_name =  $eObj->get_name();
		foreach($this->settings['composite_fields'] as $key=>$value)
		{
			if($value['di_name'] == $di_name)
			{
				$id = (int)$args[$value['di_key']];
				$this->update_field($id,$value);
			}
		}
	}

	public function index_field_unset($eObj, $ids, $args)
	{
		$this->update_field($this->removeable_id,$this->field_to_update);
	}

	public function index_field_prepare_unset($eObj, $ids, $args)
	{
		$di_name =  $eObj->get_name();
		foreach($this->settings['composite_fields'] as $key=>$value)
		{
			if($value['di_name'] == $di_name)
			{
				$id = (int)$args[$value['di_key']];
				$di = clone $eObj;
				$di->_flush();
				$di->set_args(array('_sid' => $ids));
				$di->what = array($value['di_key']);
				$di->_get();
				$this->removeable_id = (int)$di->get_results(0, $value['di_key']);
				$this->field_to_update = $value;
			}
		}
	}

	//9*  custom cyrillic fix. for json_encode
	public function json_enc($arr)
	{
		$result = preg_replace_callback(
			'/\\\u([0-9a-fA-F]{4})/', 
			create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),
			json_encode($arr)
		);
		return $result;
	}

	public function _listeners()
	{
		$listeners =  array(
			array('di' => $this->settings['index_target']['di_name'], 'event' => 'onSet', 'handler' => 'index_target_set'),
			array('di' => $this->settings['index_target']['di_name'], 'event' => 'onUnset', 'handler' => 'index_target_unset'),
		);
		foreach($this->settings['composite_fields'] as $key=>$value)
		{
			if($value['type'] == 'records_by_key')
			{
				$listeners[] = array('di' => $value['di_name'], 'event' => 'onSet', 'handler' => 'index_field_set');
				$listeners[] = array('di' => $value['di_name'], 'event' => 'onUnset', 'handler' => 'index_field_unset');
				$listeners[] = array('di' => $value['di_name'], 'event' => 'onBeforeUnset', 'handler' => 'index_field_prepare_unset');
			}

		}
		return $listeners;
	}
}
?>
