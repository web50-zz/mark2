<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 26062013	
* @package	SBIN Diesel
*/
class di_m2_category_chars extends data_interface
{
	public $title = 'm2: Маркет 2 -  характеристики для категорий. Внимание - не товаров, а категорий';

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
	protected $name = 'm2_category_chars';

	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'm2_id' => array('type' => 'integer', 'alias' => 'pid'),
		'type_value' => array('type' => 'integer'),// id значение из справочника  если оно выбирается из фиксироанных
		'type_id' => array('type' => 'integer'),// id парамера из справочника
		'type_value_str'=>array('type'=>'string'),// строковое наименование ввыбранного фиксированного значени из справочника если  выбор шел из фиксированных
		'variable_value' => array('type' => 'string'),// Произвольное значение
		'str_title' => array('type' => 'string'),// название характеристики  в строковом виде 
		'char_type' => array('type' => 'string'),// 1 если характеристика из справочника и подразумевает выбор из фиксировванных значений. в остельных случаях 0
		'is_custom' => array('type' => 'integer'),//  1  если это не из справочника
		'order' => array('type' => 'integer'),
	);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}


	protected function sys_list()
	{
		$this->_flush();
		$this->set_order('order', 'DESC');
		$di = $this->join_with_di('m2_chars_types',array('type_id'=>'id'),array('title'=>'title'));
		$flds = array('id');
		$flds[] = array('di'=>$di,'name'=>'title');
		$flds[] = 'str_title';
		$flds[] = 'order';
		$flds[] = 'is_custom';
		$flds[] = 'if('.$this->get_alias().'.char_type = 1,
				type_value_str,
				variable_value) as type_str';
		$this->extjs_grid_json($flds);
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
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` - 1) WHERE `order` >= :opos AND `order` <= :npos AND `m2_id` = :pid";
		else
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` + 1) WHERE `order` >= :npos AND `order` <= :opos AND `m2_id` = :pid";

		$this->_flush();
		$this->connector->exec($query, $values);
		response::send(array('success' => true), 'json');
	}
	
	/**
	*	Добавить \ Сохранить 
	*/
	protected function sys_set()
	{
		$fid = $this->get_args('_sid');
		if($this->args['type_id'] >0)
		{
			$di = data_interface::get_instance('m2_chars_types');
			$di->_flush();
			$di->set_args(array('_sid'=>$this->args['type_id']));
			$res = $di->extjs_grid_json(false,false);
			if($res['total'] == 1)
			{
				$this->args['str_title'] = $res['records'][0]['title'];
			}
		}
		if ($fid > 0)
		{
			$this->_flush();
			$this->_get();
			$file = $this->get_results(0);
			$old_file_name = $file->real_name;
		}
		$file = array();
		$args =  $this->get_args();
		if (!($fid > 0))
		{
			$args['order'] = $this->get_new_order((int)$this->get_args('m2_id'));
		}
		$this->set_args($args);
		$this->_flush();
		$this->insert_on_empty = true;
		$result = $this->extjs_set_json(false);
		response::send($result, 'json');
	}
	

	/**
	*
	*/
	private function get_new_order($pid)
	{
		$this->_flush();
		$this->_get("SELECT MAX(`order`) + 1 AS `order` FROM `{$this->name}` WHERE `m2_id` = {$pid}");
		return $this->get_results(0, 'order');
	}
	
	/**
	*	Удалить 
	* @access protected
	*/
	protected function sys_unset($silent = false)
	{
		if ($this->args['records'] && !$this->args['_sid'])
		{
			$this->args['_sid'] = request::json2int($this->args['records']);
		}
		$this->_flush();
		$files = $this->_get();// это надо почмуто чтобы работал коррекно onBeforeUnset
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		if($silent == true)
		{
			return $data;
		}
		response::send($data, 'json');
	}

	public function get_value_for_property($item_id = 0, $property_id = 0)
	{
		$this->push_args(array(
			'_sm2_id'=>$item_id,
			'_stype_id'=>$property_id,
		));
		$this->_flush();
		$res = $this->extjs_grid_json(false,false);
		$this->pop_args();
		return $res['records'][0]['variable_value'];
	}

	public function unset_for_item($eObj, $ids, $args)
	{
		$this->push_args(array());
		if (!is_array($ids) && $ids > 0)
		{
			$this->set_args(array(
				'_sm2_id' => $ids,
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
					'_sm2_id' => $id,
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

	/**
	*	Обработчик удаления категори(и|й)
	*/
	public function unset_for_category($eObj, $ids, $args)
	{
		// Получаем файлы, привязанные к удаляем(ой|ым) категориям
		$fids = $this->_flush()
			->push_args(array('_sm2_id' => $ids))
			->set_what(array('id'))
			->_get()
			->pop_args()
			->get_results(false, 'id');

		if (!empty($fids))
		{
			// Удаляем файлы штатными средкствами
			$this->push_args(array('_sid' => $fids));
			$this->sys_unset(true);
			$this->pop_args();
		}
	}

	
	public function _listeners()
	{
		return array(
			array('di' => 'm2_category', 'event' => 'onUnset', 'handler' => 'unset_for_item'),
		//	array('di' => 'm2_category', 'event' => 'onUnset', 'handler' => 'unset_for_category'),
		);
	}
}
?>
