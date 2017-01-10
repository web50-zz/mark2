<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 17072013	
* @package	SBIN Diesel
*/
class di_m2_manufacturers extends data_interface
{
	public $title = 'm2: Маркет 2 - Производители типы';

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
	protected $name = 'm2_manufacturers';

	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'order' => array('type' => 'integer'),
		'not_available' => array('type' => 'integer'),
		'type' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'name' => array('type' => 'string'),
	);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}
	protected function sys_available_list()
	{
		$this->_flush();
		$this->set_order('order', 'ASC');
		$this->set_args(array('_snot_available'=>'0'));
		$this->extjs_grid_json(array('id', 'order', 'title'));
	}

	protected function sys_list()
	{
		$this->_flush();
	//	$this->set_order('order', 'ASC');
		$di = $this->join_with_di('m2_manufacturer_types',array('type'=>'id'),array('title'=>'manufacturer_title'));
		$this->extjs_grid_json(array('id', 'order', 'title',
			array('di'=>$di,'name'=>'title'),
		));
	}
	
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}

	/**
	*	Реорганизация порядка вывода
	*/
	protected function sys_reorder()
	{
		list($npos, $opos) = array_values($this->get_args(array('npos', 'opos')));
		$values = $this->get_args(array('opos', 'npos', 'id', 'pid'));

		if ($opos < $npos)
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` - 1) WHERE `order` >= :opos AND `order` <= :npos";
		else
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` + 1) WHERE `order` >= :npos AND `order` <= :opos";

		$this->_flush();
		$this->connector->exec($query, $values);
		response::send(array('success' => true), 'json');
	}
	
	/**
	*	Добавить \ Сохранить файл
	*/
	public function sys_set($silent = false)
	{
		$id = $this->get_args('_sid');
		$args =  $this->get_args();
		$args['name'] = $this->prepare_uri();
		if (!($id > 0))
		{
			$args['order'] = $this->get_new_order();
		}
		$this->set_args($args);
		$this->_flush();
		$this->insert_on_empty = true;
		$result = $this->extjs_set_json(false);
		if($silent == true)
		{
			return $result;
		}
	}
	

	/**
	*
	*/
	private function get_new_order()
	{
		$this->_flush();
		$this->_get("SELECT MAX(`order`) + 1 AS `order` FROM `{$this->name}`");
		return $this->get_results(0, 'order');
	}
	
	/**
	*	Удалить файл[ы]
	* @access protected
	*/
	protected function sys_unset()
	{
		if ($this->args['records'] && !$this->args['_sid'])
		{
			$this->args['_sid'] = request::json2int($this->args['records']);
		}
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		response::send($data, 'json');
	}
	protected function prepare_uri()
	{
		$config = array();
		$name = $this->get_args('name');
		$title = $this->get_args('title');
		$id = $this->get_args('_sid');
		$di = data_interface::get_instance('m2_utils');
		$config = array(
			'm2_manufacturers'=>array(
					'field'=>'name',
					'id'=>$id
				),
			);
		if($name == '')
		{
			$name = $title;
		}

		try{
			$uri = $di->prepare_uri($config,$name);
		}
		catch(exception $e)
		{
			dbg::write($e->getMessage());
		}
		return $uri;
	}
	public function search_by_name($name = '')
	{
		if($name == '')
		{
			return false;
		}
		$this->_flush();
		$this->set_args(array('_sname'=>$name));
		$res = $this->_get()->get_results(0);
		if($res->id >0)
		{
			return $res;
		}
		return false;
	}
	protected function sys_choice()
	{
		response::send($this->choice(), 'json');
	}
	
	public function choice()
	{
		$lid = $this->get_args('lid', 0);
		$this->_flush(true);
		$tbl = $this->get_name();
		$sbr = $this->join_with_di('m2_manufacturer_in_groups', array('id' => 'item_id', $lid => 'group_id'), array('id' => 'cid'));

		list($query, $field) = array_values($this->get_args(array('query', 'field'), false));
		if ($query && !$field)
		{
			$this->where = "(`{$tbl}`.`title` LIKE '%{$query}%'";
		}
		else
		{
			$this->set_args(array("_s{$field}" => "%{$query}%"), true);
		}

		return $this->extjs_grid_json(array(
			'id',
			'title' => 'title',
		), false);
	}

}
?>
