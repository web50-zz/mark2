<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 21042017	
* @package	SBIN Diesel
*/
class di_m2_chars_in_category extends data_interface
{
	public $title = 'm2: Маркет 2 - Категории и характеристики связка';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = 'm2_chars_in_category';

	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'category_id' => array('type' => 'integer'),
		'manufacturer_id' => array('type' => 'integer'),
	);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	protected function sys_list()
	{
		$this->_flush();
		$this->extjs_grid_json();
	}
	
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}

	
	/**
	*	Добавить \ Сохранить 
	*/
	public function sys_set($silent = false)
	{
		$id = $this->get_args('_sid');
		$this->_flush();
		$this->insert_on_empty = true;
		$result = $this->extjs_set_json(false);
		if($silent == true)
		{
			return $result;
		}
		response::send($result, 'json');
	}
	

	/**
	*	Удалить файл[ы]
	* @access protected
	*/
	public function sys_unset($silent = false)
	{
		if ($this->args['records'] && !$this->args['_sid'])
		{
			$this->args['_sid'] = request::json2int($this->args['records']);
		}
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		if($silent == true)
		{
			return $data;
		}
		response::send($data, 'json');
	}

	public function recache()
	{
		$di = data_interface::get_instance('m2_item_indexer');
		$di->_flush();
		$di->where = "chars_list != '[]' && category_list != '[]'";
		$res = $di->_get()->get_results();
		$index = array();
		foreach($res as $key1=>$value1)
		{
			$cats = json_decode($value1->category_list);
			$chars = json_decode($value1->chars_list);
			foreach($cats as $key=>$value)
			{
				if(!array_key_exists($value->category_id,$index))
				{
					$index[$value->category_id] = array();	
				}
				foreach($chars as $key2=>$value2)
				{
					if($value2->type_value_str != '')
					{
						$val = $value2->type_value_str;
					}
					if($value2->variable_value != '')
					{
						$val = $value2->variable_value;
					}
					if(!array_key_exists($value2->type_id,$index[$value->category_id]))
					{
						$index[$value->category_id][$value2->type_id] = array();
					}
					if(!array_key_exists($val,$index[$value->category_id][$value2->type_id]))
					{
						$index[$value->category_id][$value2->type_id][$val] = array();
					}
					$index[$value->category_id][$value2->type_id][$val][$value1->item_id] = 1;
				}
			}
		}
		foreach($index as $key=>$value)
		{
				$str = $this->json_enc($value);
				$vals[] = "('',$key,'$str')";
		}
		$sql = "truncate $this->name";
		$this->connector->exec($sql);
		$sql = "insert into $this->name values ".implode(',',$vals);
		$this->connector->exec($sql);
	}

	//9*  custom cyrillic fix. for json_encode
	public function json_enc($arr)
	{
		$result = preg_replace_callback(
			'/\\\u([0-9a-fA-F]{4})/', 
			create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),
                        str_replace('\n','',str_replace('\t','',str_replace('\r','',str_replace('"','\"',json_encode($arr)))))
		);
		return $result;
	}

}
?>
