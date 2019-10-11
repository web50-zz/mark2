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
		'index1' => array('type' => 'string'),
		'index2' => array('type' => 'string'),
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
		$di->where = "chars_list != '[]' && category_list != '[]' and not_available = 0 ";
		$res = $di->_get()->get_results();
		$index = array();
		$type_values = array();
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
					$tmp = array();
					if($value2->type_value_str != '')
					{
						$val = $value2->type_value_str;
					}
					if($value2->variable_value != '')
					{
						$val = $value2->variable_value;
					}
					$type_values[$value2->type_id][$val] = $value2->type_value;
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
		$index2 = array();
		foreach($index as $k1=>$v1)
		{	
			$index2[$k1]= array();
			foreach($v1 as $k2=>$v2)
			{
				foreach($v2 as $k3=>$v3)
				{
					$index2[$k1][] = array('category_id'=>$k1,'type_id'=>$k2,'val'=>$k3,'type_value'=>$type_values[$k2][$k3]);
				}
			}
		}
		foreach($index as $key=>$value)
		{
				$str = $this->json_enc($value);
				$str2 = $this->json_enc($index2[$key]);
				$vals[] = "('',$key,'$str','$str2')";
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

	public function get_chars_for($scope = array(),$type_id = 0)
	{
		if(!count($scope)>0)
		{
			return array();
		}
		$scope_key = implode(',',array_keys($scope));
		if(!$this->data_for_scope[$scope_key])
		{
			$sql = 'select * from '.$this->get_alias().' where category_id in('.implode(',',array_keys($scope)).")";
			$this->_flush();
			$res = $this->_get($sql)->get_results();
			$this->data_for_scope[$scope_key] = $res;
		}
		$data = array();
		foreach($this->data_for_scope[$scope_key] as $key=>$value)
		{
			$ar = json_decode($value->index2);
			if(count($ar)>0)
			{
				foreach($ar as $key2=>$value2)
				{
					if($value2->type_id  == $type_id)
					{
						$data[$value2->val] = $value2->type_value;
					}
				}
			}
		}
		ksort($data);
		return $data;
	}

	// Выдает список категорий для заданного id свойства товара которые входят в искомую категорию. То есть тупо найти категории в которых свойсто 123 имеется.
	public function get_cats_for_char($type_id = 0)
	{
		if($type_id == 0)
		{
			return array();
		}
		$sql = 'select * from '.$this->get_alias().' i left join m2_category c on i.category_id = c.id where index2 like \'%"type_id":'.$type_id.',%\'';
		$res = $this->_get($sql)->get_results();
		return $res;
	}
}
?>
