<?php
/**
*
* @author	9*	<9@u9.ru> 10.01.2017
* @package	SBIN Diesel
*/
class di_m2_manufacturer_group extends data_interface
{
	public $title = 'm2: Группы производителей';

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
	protected $name = 'm2_manufacturer_group';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),

		'created_datetime' => array('type' => 'datetime'),
		'creator_uid' => array('type' => 'integer'),
		'changed_datetime' => array('type' => 'datetime'),
		'changer_uid' => array('type' => 'integer'),
		'title' => array('type' => 'string'),	// Наименование списка
		'comment' => array('type' => 'string'),	// Комментарий к списку
	);
	
	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush();
		$this->extjs_grid_json(array(
			'id',
			'title',
		));
	}

	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_get($silent = false)
	{
		$this->_flush();
		if($silent == true)
		{
			return 	$this->extjs_form_json(false,false);
		}
		$this->extjs_form_json();
	}
	
	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$this->insert_on_empty = true;

		// Если не указан идентификатор записи
		if (!($this->args['_sid'] > 0))
		{
			// NOTE: Фиксируем время создания и UID создателя
			$this->set_args(array(
				'created_datetime' => date('Y-m-d H:i:s'),
				'creator_uid' => UID
			), true);
		}

		// NOTE: Фиксируем время изменения и UID редактора
		$this->set_args(array(
			'changed_datetime' => date('Y-m-d H:i:s'),
			'changer_uid' => UID
		), true);

		$this->extjs_set_json();
	}

	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_mset()
	{
		$records = (array)json_decode($this->get_args('records'), true);

		foreach ($records as $record)
		{
			$record['_sid'] = $record['id'];
			unset($record['id']);
			$record['changed_datetime'] = date('Y-m-d H:i:s');
			$record['changer_uid'] = UID;

			$this->_flush();
			$this->push_args($record);
			$this->insert_on_empty = true;
			$data = $this->extjs_set_json(false);
			$this->pop_args();
		}

		response::send(array('success' => true), 'json');
	}
	
	/**
	*	Удалить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$this->extjs_unset_json();
	}

	public function get_list_by_ids($ids)
	{
		$this->_flush();
		$this->push_args(array('_sid'=>$ids));
		$res = $this->extjs_grid_json(false,false);
		return $res['records'];
	}

	protected function sys_choice()
	{
		response::send($this->choice(), 'json');
	}

	/* 9* 18092013 Исользуется для выборки рассылок, входящих  в список mailer_include_group для виджета внешней кнопки	
	*  Появляется в окне для выбора не подключенных к выбранному виджету рассылок
	*
	*/
	public function choice()
	{
		$lid = $this->get_args('lid', 0);
		$this->_flush(true);
		$tbl = $this->get_name();

		list($query, $field) = array_values($this->get_args(array('query', 'field'), false));
		if ($query && !$field)
		{
			$this->where = "(`{$tbl}`.`comment` LIKE '%{$query}%'";
		}
		else
		{
			$this->set_args(array("_s{$field}" => "%{$query}%"), true);
		}

		return $this->extjs_grid_json(array(
			'id',
			'title',
		), false);
	}
	// 9* данные рассылки по ид если на входе массив то вернет список рассылок 
	public function get_by_id($id)
	{
		if(!($id>0))
		{
			return array();
		}
		$this->set_args(array('_sid'=>$id));
		$this->_flush();
		$data = $this->extjs_grid_json(false,false);
		return $data['records'];
	}
}
?>
