<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 28052018	
* @package	SBIN Diesel
*/
class di_m2_category_price extends data_interface
{
	public $title = 'm2: Маркет 2 - Категории и цены индекс';

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
	protected $name = 'm2_category_price';

	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'category_id' => array('type' => 'integer'),
		'max_price' => array('type' => 'integer'),
		'min_price' => array('type' => 'integer'),
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
		$sql = "select prices_list, category_list from m2_item_indexer where prices_list != '[]' && category_list != '[]' and not_available = 0";
		$res = $di->_get($sql)->get_results();
		$index = array();
		$price_type = registry::get('MAIN_PRICE_TYPE');
		foreach($res as $key=>$value)
		{
			$cats = json_decode($value->category_list);
			$price = json_decode($value->prices_list);
			foreach($cats as $key=>$value)
			{
				if(!array_key_exists($value->category_id,$index))
				{
					$index[$value->category_id] = array('min'=>'20000000','max'=>0);	
				}
				foreach($price as $key2=>$value2)
				{
					if($value2->type == $price_type)
					{
						if($value2->price_value < $index[$value->category_id]['min'])
						{
							$index[$value->category_id]['min'] = floor($value2->price_value);
						}
						if($value2->price_value > $index[$value->category_id]['max'])
						{
							$index[$value->category_id]['max'] = ceil($value2->price_value);
						}
				
					}
				}
			}
		}
		foreach($index as $key=>$value)
		{
				$vals[] = "('',$key,".$value['min'].",".$value['max'].")";
		}
		$sql = "truncate $this->name";
		$this->connector->exec($sql);
		$sql = "insert into $this->name values ".implode(',',$vals);
		$this->connector->exec($sql);
	}

	public function get_price_for_category($scope = array())
	{
		$this->_flush();
		if(!(count($scope) > 0))
		{
			$ui = user_interface::get_instance('mf2_catalogue_nav');
			$scope = $ui->get_scope();
		}
		if(count($scope)>0)
		{
			$ids = implode(',',array_keys($scope));
			$sql = "select * from $this->name a where a.category_id in($ids)";
			$data = $this->_get($sql)->get_results();
			$out['max'] = 0;
			$out['min'] = 2000000;
			foreach($data as $key=>$value)
			{
				if($value->min_price < $out['min'])
				{
					$out['min'] = $value->min_price;
				}
				if($value->max_price > $out['max'])
				{
					$out['max'] = $value->max_price;
				}

			}
		}
		return $out;
	}

	public function get_price_for_category_list($ids = array())
	{
		$this->_flush();
		$ids = implode(',',$ids);
		$sql = "select * from $this->name a left join m2_manufacturers m on a.manufacturer_id = m.id where a.category_id in($ids) order by m.title ASC ";
		return $this->_get($sql)->get_results();
	}

}
?>
