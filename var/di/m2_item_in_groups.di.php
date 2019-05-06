<?php
/**
*		08.10.2018
* @author	9* <9@u9.ru> 9@u9.ru
* @package	SBIN Diesel
*/
class di_m2_item_in_groups extends data_interface
{
	public $title = 'Рассылка: Подписчики';

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
	protected $name = 'm2_item_in_groups';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),

		'created_datetime' => array('type' => 'datetime'),
		'creator_uid' => array('type' => 'integer'),
		'changed_datetime' => array('type' => 'datetime'),
		'changer_uid' => array('type' => 'integer'),
		'group_id' => array('type' => 'integer', 'alias' => 'lid'),
		'item_id' => array('type' => 'integer'),
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
		$this->_flush(true);
		$cnt = $this->join_with_di('m2_item', array('item_id' => 'id'), array('title' => 'title'));
		$cntt = $cnt->get_alias();

		list($query, $field) = array_values($this->get_args(array('query', 'field'), false));
		if ($query && !$field)
		{
			$this->where.= " OR `{$cntt}`.`comment` LIKE '%{$query}%')";
		}
		else
		{
			$this->set_args(array("_s{$field}" => "%{$query}%"), true);
		}

		$this->extjs_grid_json(array(
			'id',
			array('di' => $cnt, 'name' => 'title'),
		));
	}

	public function get_list()
	{
		$sort = $this->get_args('sort','id');
		$dir = $this->get_args('dir','ASC');
		$limit = $this->get_args('limit','10');
		$page = $this->get_args('page',1);
		$start = ($page - 1) * $limit;
		$id = $this->get_args('id',0);
		$sql = "select * from m2_item_indexer i left join ".$this->name." l on i.item_id = l.item_id where group_id = ".$id." order by l.$sort $dir limit $start,$limit";
		$res = $this->_get($sql)->get_results();
		return $res;
	}



//9* список  с именами  листов рассылок
	protected function sys_list_subs()
	{
		$this->_flush(true);
		$cnt = $this->join_with_di('mailer_list', array('group_id' => 'id'), array('title' => 'title'));
		$cntt = $cnt->get_alias();

		list($query, $field) = array_values($this->get_args(array('query', 'field'), false));
		if ($query && !$field)
		{
			$this->where.= " OR `{$cntt}`.`comment` LIKE '%{$query}%')";
		}
		else
		{
			$this->set_args(array("_s{$field}" => "%{$query}%"), true);
		}

		$this->extjs_grid_json(array(
			'id',
			array('di' => $cnt, 'name' => 'title'),
		));
	}

	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}

	/**
	*	Подписать на рассылку клиента
	* @access protected
	*/
	protected function sys_add()
	{
		list($list_id, $item_id) = array_values($this->get_args(array('lid', 'id', 'cid'), 0));
		if ($list_id > 0 && !empty($item_id))
		{
			if (is_array($item_id))
			{
				foreach ($item_id as $i => $id)
				{
					if($id>0)
					{
						$this->_flush();
						$this->insert_on_empty = true;
						$this->push_args(array(
							'created_datetime' => date('Y-m-d H:i:s'),
							'creator_uid' => UID,
							'changed_datetime' => date('Y-m-d H:i:s'),
							'changer_uid' => UID,
							'_sgroup_id' => $list_id,
							'group_id' => $list_id,
							'_sitem_id' => $id,
							'item_id' => $id,
							));
						$this->_set();
						$this->pop_args();
					}
				}
			}
			else
			{
				$this->_flush();
				$this->insert_on_empty = true;
				$this->push_args(array(
					'created_datetime' => date('Y-m-d H:i:s'),
					'creator_uid' => UID,
					'changed_datetime' => date('Y-m-d H:i:s'),
					'changer_uid' => UID,
					'_sgroup_id' => $list_id,
					'group_id' => $list_id,
					'_sitem_id' => $item_id,
					'item_id' => $item_id,
					));
				$this->_set();
				$this->pop_args();
			}
	
			$results = array('success' => true);
		}
		else
		{
			$results = array('success' => false, 'error' => 'Параметры указаны не верно.');
		}
		if($this->args['silent'] == true)
		{
			return $results;
		}
		response::send($results, 'json');
	}

	public function add()
	{
		$this->args['silent'] = true;
		return $this->sys_add();
	}
	/**
	*	Подписать на рассылку клиента
	* @access protected
	*/
	protected function sys_add_all()
	{
		$results = array('success' => true);
		$list_id = (int)$this->get_args('lid');
		if ($list_id > 0)
		{
			$di = data_interface::get_instance('m2_items');
			$di->set_args($this->get_args());
			$data = $di->choice();
			if (!empty($data['records']))
			{
				foreach ($data['records'] as $record)
				{
					$this->_flush();
					$this->insert_on_empty = true;
					$this->push_args(array(
						'created_datetime' => date('Y-m-d H:i:s'),
						'creator_uid' => UID,
						'changed_datetime' => date('Y-m-d H:i:s'),
						'changer_uid' => UID,
						'uniq_hash'=>md5($list_id.$id.microtime()),
						'_sgroup_id' => $list_id,
						'group_id' => $list_id,
						'_sitem_id' => $record['id'],
						'item_id' => $record['id'],
						));
					$this->_set();
					$this->pop_args();
				}
			}
		}
		else
		{
			$results = array('success' => false, 'errors' => 'Не указана группа');
		}
		response::send($results, 'json');
	}
	/**
	*	Подписать на рассылку клиента
	* @access protected
	*/
	protected function sys_remove_all()
	{
		$results = array('success' => true);
		$list_id = (int)$this->get_args('_slid');
		if ($list_id > 0)
		{
			$this->_flush();
			$this->set_args(array('_sgroup_id'=>$list_id));
			$data = $this->extjs_grid_json(false,false);
			if ($data['total']>0)
			{
				foreach ($data['records'] as $record)
				{
					$to_del[] = $record['id'];
				}
				$this->_flush();
				$this->push_args(array(
					'_sid' => $to_del,
					'_sgroup_id'=>$list_id,
					));
				$this->_unset();
				$this->pop_args();

			}
		}
		else
		{
			$results = array('success' => false, 'errors' => 'Не указана группа ');
		}
		response::send($results, 'json');
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
	protected function sys_unset($silent = false)
	{
		$this->_flush();
		if($silent == true)
		{
			return $this->extjs_unset_json(false);
		}
		$this->extjs_unset_json();
	}

	public function unset_for_contact($eObj, $ids, $args)
	{
		$this->push_args(array());
		if (!is_array($ids) && $ids > 0)
		{
			$this->set_args(array(
				'_sitem_id' => $ids,
			));
			$this->_flush();
			$this->_get();
			$res =  $this->get_results();
			if(count($res)>0)
			{
				foreach($res as $key=>$value)
				{
					$this->set_args(array(
						'_sid' => $value->id,
					));
					$this->sys_unset(true);
				}
			}
		}
		else if (is_array($ids))
		{
			foreach ($ids as $id)
			{
				$this->set_args(array(
					'_sitem_id' => $id,
				));
				$this->_flush();
				$this->_get();
				$res =  $this->get_results();
				if(count($res)>0)
				{
					foreach($res as $key=>$value)
					{
						$this->set_args(array(
							'_sid' => $value->id,
						));
						$this->sys_unset(true);
					}
				}
			}
		}
		else
		{
			// Some error, because unknown project ID
		}
		$this->pop_args();
	}


	public function unset_for_maillist($eObj, $ids, $args)
	{
		$this->push_args(array());
		if (!is_array($ids) && $ids > 0)
		{
			$this->set_args(array(
				'_sgroup_id' => $ids,
			));
			$this->_flush();
			$this->_get();
			$res =  $this->get_results();
			if(count($res)>0)
			{
				foreach($res as $key=>$value)
				{
					$this->set_args(array(
						'_sid' => $value->id,
					));
					$this->sys_unset(true);
				}
			}
		}
		else if (is_array($ids))
		{
			foreach ($ids as $id)
			{
				$this->set_args(array(
					'_sgroup_id' => $id,
				));
				$this->_flush();
				$this->_get();
				$res =  $this->get_results();
				if(count($res)>0)
				{
					foreach($res as $key=>$value)
					{
						$this->set_args(array(
							'_sid' => $value->id,
						));
						$this->sys_unset(true);
					}
				}
			}
		}
		else
		{
			// Some error, because unknown project ID
		}
		$this->pop_args();
	}


	public function _listeners()
	{
		return array(
			array('di' => 'm2_items', 'event' => 'onUnset', 'handler' => 'unset_for_contact'),
			array('di' => 'm2_item_group', 'event' => 'onUnset', 'handler' => 'unset_for_maillist'),
		);
	}
}
?>
