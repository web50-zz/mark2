<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 01072013	
* @package	SBIN Diesel
*/
class di_m2_item_category extends data_interface
{
	public $title = 'm2: Маркет 2 - элементы в категориях';

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
	protected $name = 'm2_item_category';

	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'category_id' => array('type' => 'integer'),
		'item_id' => array('type' => 'integer'),
		'category_order' => array('type' => 'integer'),
	);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}
	
	protected function sys_list()
	{
		$this->_flush();
		$this->set_order('category_order', 'ASC');
		$ct = $this->join_with_di('m2_category', array('category_id' => 'id'), array('title' => 'category_title'));
		$this->extjs_grid_json(array('id',
					'category_order',
					'category_id', 
					'item_id',
					array('di' => $ct, 'name' => 'title'),
					));
	}
	
	protected function sys_get()
	{
		$this->_flush(true);
		$ct = $this->join_with_di('m2_category', array('category_id' => 'id'), array('title' => 'category_title'));
		$ctt = $ct->get_alias();
		$this->extjs_form_json(array(
			'*',
			"`{$ctt}`.`title`" => 'category_title',
		));
	}

	/**
	*	Добавить \ Сохранить файл
	*/
	public function sys_set($silent = false)
	{
		$id = $this->get_args('_sid',0);
		$args =  $this->get_args();
		if(!($id > 0))
		{
			$args['category_order'] = $this->get_new_order();
		}
		$this->set_args($args);
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
	*	Реорганизация порядка вывода категорий внтури товара
	*/

	protected function sys_reorder()
	{
		list($npos, $opos) = array_values($this->get_args(array('npos', 'opos')));
		$values = $this->get_args(array('opos', 'npos', 'id', 'cid'));

		if ($opos < $npos)
			$query = "UPDATE `{$this->name}` SET `category_order` = IF(`id` = :id, :npos, `category_order` - 1) WHERE `category_order` >= :opos AND `category_order` <= :npos AND `item_id` = :cid";
		else
			$query = "UPDATE `{$this->name}` SET `category_order` = IF(`id` = :id, :npos, `category_order` + 1) WHERE `category_order` >= :npos AND `category_order` <= :opos AND `item_id` = :cid";

		$this->_flush();
		$this->connector->exec($query, $values);
		$this->fire_event('onSet', array(null, array('item_id' => $values['cid'])));
		response::send(array('success' => true), 'json');
	}

	/**
	*
	*/
	private function get_new_order()
	{
		$this->_flush();
		$this->_get("SELECT MAX(`category_order`) + 1 AS `category_order` FROM `{$this->name}`");
		return $this->get_results(0, 'category_order');
	}

	/**
	*	Удалить файл[ы]
	* @access protected
	*/
	protected function sys_unset($silent =  false)
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

	public function unset_for_item($eObj, $ids, $args)
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
	public function unset_for_category($eObj, $ids, $args)
	{
		$this->push_args(array());
		if (!is_array($ids) && $ids > 0)
		{
			$this->set_args(array(
				'_scategory_id' => $ids,
			));
			$this->_flush();
			$this->_unset();
		}
		else if (is_array($ids))
		{
			foreach ($ids as $id)
			{
				$this->set_args(array(
					'_scategory_id' => $id,
				));
				$this->_flush();
				$this->insert_on_empty = true;
				$this->_unset();
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
			array('di' => 'm2_item', 'event' => 'onUnset', 'handler' => 'unset_for_item'),
			array('di' => 'm2_category', 'event' => 'onUnset', 'handler' => 'unset_for_category'),
		);
	}

}
?>
