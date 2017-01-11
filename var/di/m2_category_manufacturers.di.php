<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 28122016	
* @package	SBIN Diesel
*/
class di_m2_category_manufacturers extends data_interface
{
	public $title = 'm2: Маркет 2 - Категории и бренды связка';

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
	protected $name = 'm2_category_manufacturers';

	
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
		$di->where = "manufacturers_list != '[]' && category_list != '[]'";
		$res = $di->_get()->get_results();
		$index = array();
		foreach($res as $key=>$value)
		{
			$cats = json_decode($value->category_list);
			$mans = json_decode($value->manufacturers_list);
			foreach($cats as $key=>$value)
			{
				if(!array_key_exists($value->category_id,$index))
				{
					$index[$value->category_id] = array();	
				}
				foreach($mans as $key2=>$value2)
				{
					$index[$value->category_id][$value2->manufacturer_id] = 1;
				}
			}
		}
		foreach($index as $key=>$value)
		{
			foreach($value as $key2=>$value)
			{
				$vals[] = "('',$key,$key2)";
			}
		}
		$sql = "truncate $this->name";
		$this->connector->exec($sql);
		$sql = "insert into $this->name values ".implode(',',$vals);
		$this->connector->exec($sql);
	}

	public function get_manufacturers_for_category($category)
	{
		$this->_flush();
		$ui = user_interface::get_instance('mf2_catalogue_nav');
		$scope = $ui->catalogue_scope;
		$ids = implode(',',array_keys($scope));
		$sql = "select * from $this->name a left join m2_manufacturers m on a.manufacturer_id = m.id where a.category_id in($ids) group by m.id order by m.title ASC ";
		return $this->_get($sql)->get_results();
	}
}
?>
