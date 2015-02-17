<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 26062013	
* @package	SBIN Diesel
*/
class di_m2_chars_types extends data_interface
{
	public $title = 'm2:  Маркет 2 - характеристики справочник';
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
	protected $name = 'm2_chars_types';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => 1, 'readonly' => 1),
			'title' => array('type' => 'string'),
			'name' => array('type' => 'string'),
			'uri' => array('type' => 'string'),
			'type' => array('type' => 'integer'),		// Тип ноды (0 - Группа, 1 - Произвольное значение, 2 - фиксированное значение для парента)  9*  0 - deprecated  издержки клонирования
			'output_type' => array('type' => 'integer'),		// Тип вывода 
			'char_type' => array('type' => 'integer'),		// Тип Значений харатктеристики 
			'link_id' => array('type' => 'integer'),	// ID проекта, если нода является ссылкой
			'visible' => array('type' => 'integer'),	// Видимый (0 - Нет, 1 - Да)
			'brief' => array('type' => 'text'),
			'description' => array('type' => 'text'),
			'short_description' => array('type' => 'text'),
			'left' => array('type' => 'integer', 'protected' => 1),
			'right' => array('type' => 'integer', 'protected' => 1),
			'level' => array('type' => 'integer', 'readonly' => 1)
		);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}
	
	public function get_trunc_menu($id)
	{
		$ns = new nested_sets($this);
		return $ns->get_parents($id, true);
	}

	public function sys_type_list()
	{
		$this->_flush();
		$this->set_order('left','ASC');
		$this->where = $this->get_alias().'.id != 1';
		$this->extjs_grid_json(array('id','title'));
	}

	/**
	*	Добавить 
	* @access protected
	*/
	protected function sys_set()
	{
		$id = $this->get_args('_sid');
		$name = $this->get_args('name');
		if(trim($name) == '')
		{
			if (($uri = $this->make_uri()) === false)
			{
				response::send(array('success' => false, 'errors' => 'Не удаётся создать уникальный URI, возможно не указано наименование'), 'json');
			}
			else
			{
				$this->set_args(array('name' => $uri), true);
			}
		}
		else
		{
			if(!$this->check_uri($name,$id))
			{
				response::send(array('success' => false, 'errors' => 'Не удаётся создать уникальный URI, возможно не указано наименование'), 'json');
			}
		}

		if ($this->args['_sid'] > 0)
		{
			$uri = $this->get_args('uri');
			$this->calc_uri();

			$this->_flush();
			$this->insert_on_empty = false;
			$result = $this->extjs_set_json(false);
			$result['data']['uri'] = $this->get_args('uri');
			$result['data']['type'] = $this->args['type'];
			$result['data']['char_type'] = $this->args['char_type'];
			$result['data']['name'] = $this->args['name'];

			if ($result['data']['uri'] != $uri)
				$this->recalc_uri($this->args['_sid']);
			
			// Обновляем title у всех узлов, которые являются ссылками на текущий.
			$this->_flush()
				->push_args(array('_stype' => 2, '_slink_id' => $this->get_args('_sid'), 'title' => $this->get_args('title')))
				->_set()
				->pop_args();
		}
		else if($this->args['pid'] > 0)
		{
			$ns = new nested_sets($this);
			unset($this->args['_sid']); // Иначе будет пытаться обновить нулевую ноду
			
			if ($ns->add_node($this->args['pid']))
			{
				$this->args['_sid'] = $this->get_lastChangedId(0);
				$this->calc_uri();
				
				$this->_flush();
				$this->insert_on_empty = false;
				$result = $this->extjs_set_json(false);
				$result['data']['uri'] = $this->args['uri'];
				$result['data']['type'] = $this->args['type'];
				$result['data']['char_type'] = $this->args['char_type'];
				$result['data']['name'] = $this->args['name'];
			}
			else
			{
				$result = array(
					'success' => false,
					'errors' =>  $e->getMessage()
				);
			}
		}
		
		response::send($result, 'json');
	}

	/**
	*	Залинковать узел
	*/
	protected function sys_link()
	{
		list($pid, $lid) = array_map('intval', $this->get_args(array('pid', 'lid'), NULL, TRUE));
		if ($pid > 0 && $lid > 0)
		{
			// Получаем инфолрмацию о линкуемой ноде
			$node = $this->_flush()
				->push_args(array('_sid' => $lid))
				->_get()
				->pop_args()
				->get_results(0);

			$ns = new nested_sets($this);
			if ($ns->add_node($pid))
			{
				$this->_flush()
				->push_args(array(
					'_sid' => $this->get_lastChangedId(0),	// ID свежесозданого узла
					'title' => $node->title,		// Title узла источника
					'type' => 2,				// Тип текущего узла (2 - ссылка)
					'link_id' => $node->id,			// ID узла источника
				))->insert_on_empty = false;
				$result = $this->extjs_set_json(false);
				$this->pop_args();
			}
			else
			{
				$result = array(
					'success' => false,
					'errors' =>  $e->getMessage()
				);
			}
		}
		else
		{
			dbg::write("Не указан один из параметров (pid: {$pid}, lid: {$lid})", LOG_PATH . 'di_errors.log');
			$result = array(
				'success' => false,
				'errors' =>  'Неверный набор параметров',
			);
		}
		
		response::send($result, 'json');
	}
	
	/**
	*	Переместить узел
	* @access protected
	*/
	protected function sys_move()
	{
		list($id, $pid, $ind) = array_map('intval', $this->get_args(array('_sid', 'pid', 'ind'), NULL, TRUE));

		if ($id > 0)
		{
			$ns = new nested_sets($this);
			if ($ns->move_node($id, $pid, $ind))
			{
				$this->push_args($ns->get_node($id));		// Запоминаем параметры ноды
				$this->set_args(array('_sid' => $id), true);	// Задаём поисковый ключ
				$this->calc_uri();				// Расчитываем URI

				$this->_flush();				// Сбрасываем DI
				$this->insert_on_empty = false;			// Запрещаем записывать новые записи
				$data = $this->extjs_set_json(false);		// Сохраняем новый URI

				$this->pop_args();				// Возвращаем исходные параметры
				$this->recalc_uri();				// Расчитываем URI всех потомков
			}
			else
			{
				$data = array(
					'success' => false,
					'error' => 'Не удалось переместить папку'
					);
			}
		}
		else
		{
			$data = array(
				'success' => true
				);
		}
		response::send($data, 'json');
	}
	
	/**
	*	Расчитать URI страницы
	*/
	private function calc_uri()
	{
		$ns = new nested_sets($this);
		
		if ($this->args['_sid'] > 0)
			$parents = $ns->get_parents($this->args['_sid']);
		else
			return FALSE;
		
		$uri = array();
		foreach ($parents as $parent)
		{
			if ($parent['id'] > 1)
			{
				$uri[] = $parent['name'];
			}
		}
		$uri[] = $this->args['name'];
		
		$this->set_args(array('uri' => join('/', $uri)), true);
		return TRUE;
	}
	
	/**
	*	Пересчитать URI всех потомков
	*/
	private function recalc_uri()
	{
		$ns = new nested_sets($this);
		
		if ($this->args['_sid'] > 0)
			$childs = $ns->get_childs($this->args['_sid']);
		else
			return FALSE;
		
		$this->insert_on_empty = false;
		
		foreach ($childs AS $child)
		{
			$this->push_args(array(
				'_sid' => $child['id'],
				'name' => $child['name']));
			
			if ($this->calc_uri() !== FALSE)
				$this->extjs_set_json(false);

			$this->pop_args();
		}
		
		return TRUE;
	}
	
	/**
	*	Получить XML-пакет данных для ExtJS-формы
	* @access protected
	*/
	protected function sys_get()
	{
		$this->extjs_form_json();
	}
	
	/**
	*	Получить JSON-пакет данных для ExtJS-дерева
	* @access protected
	*/
	protected function sys_slice()
	{
                $pid = intval($this->args['node']);
		$table = $this->get_name();
                $fields = array('id', 'title' => 'text', 'type','char_type','link_id', "IF (`{$table}`.`type` = 2, 'page_white_database', '')" => 'iconCls');
		$this->mode = 'NESTED_SETS_SLICE';
                if ($pid > 0)
                {
                        $this->set_args(array('_sid' => $pid));
                        $this->extjs_slice_json($fields, 1);
                }
                else
                {
                        $this->set_args(array('_slevel' => 1));
                        $this->extjs_slice_json($fields);
                }
	}
//9*  Списрк  узлов по входящему  паренту для нужд комбобксов
	protected function sys_chld_list()
	{
                $pid = intval($this->args['node']);
		$table = $this->get_name();
                $fields = array('id', 'title', 'type','char_type','link_id', "IF (`{$table}`.`type` = 2, 'page_white_database', '')" => 'iconCls');
		$this->mode = 'NESTED_SETS_SLICE';
                if ($pid > 0)
                {
                        $this->set_args(array('_sid' => $pid));
                        $res = $this->extjs_slice_json($fields,1, false);
                }
                else
                {
                        $this->set_args(array('_slevel' => 1));
                        $res = $this->extjs_slice_json($fields,1,false);
		}
		$out = array('success'=>true,'records'=>$res);
		response::send($out,'json');
	}

	/**
	*	Удалить узел
	* @access protected
	*/
	protected function sys_unset()
	{
		$id = intval($this->get_args('_sid'));
		$this->unset_recursively($id);

		$ns = new nested_sets($this);

		if ($id > 0 && $ns->delete_node($id))
		{
			$result = array('success' => true);
		}
		else
		{
			$result = array('success' => false);
		}

		response::send($result, 'json');
	}

	/**
	*	Удалить рекурсивно все связи
	* @access protected
	*/
	protected function unset_recursively($id)
	{
		$ids = array();

		$this->_flush();
		$this->set_args(array('_sid' => $id));
		$node = (array)array_pop($this->_get());

		$ns = new nested_sets($this);
		$childs = $ns->get_childs($id);
		array_unshift($childs, $node);
		foreach ($childs as $child)
		{
			$ids[] = $child['id'];
		}
	}

	public function get_child_proj_of($id)
	{
		$r = $this->get_all();
		$out['records'] = array();
		foreach($this->data['records'] as $key=>$value)
		{
			if($value['type'] == 1 || $value['type'] == 2)
			{	
				if($id != 1 && $id == $value['parent'])
				{
					array_push($out['records'],$value);
				}
				if($id == 1)
				{
					array_push($out['records'],$value);
				}
			}
		}
		return $out;
	}

	public function get_all()
	{
		$parent = 1;
		$this->_flush();
		if($this->args['parent']>0){
			$parent = $this->args['parent'];
		}
		$this->set_args(array(
				'sort'=>'left',
				'dir'=>'ASC',
				));
		$dp = $this->join_with_di('m2_chars_types_file',array('id'=>'m2_chars_types_id'),array('real_name'=>'real_name'));
		$d2 = $this->join_with_di('m2_chars_types',array('link_id'=>'id'),array('uri'=>'l_uri','name'=>'l_name','brief'=>'l_brief'));
		$dp2 = $this->join_with_di('m2_chars_types_file',array('link_id'=>'m2_chars_types_id'),array('real_name'=>'l_real_name'));
		$flds = array(
			'id',
			'brief',
			'name',
			'left',
			'right',
			'level',
			'uri',
			'link_id',
			'type',
			array('di'=>$d2,'name'=>'uri'),
			array('di'=>$d2,'name'=>'name'),
			array('di'=>$d2,'name'=>'brief'),
			"'".$dp->get_url()."' as path",
			array('di'=>$dp,'name'=>'real_name'),
			array('di'=>$dp2,'name'=>'real_name')
		);
		$this->data =  $this->extjs_grid_json($flds,false);
		$this->get_childs(0);
		$this->correct_links();
		$this->get_childs(0);
		if($parent != 1){
			$this->search_parent($this->data['records'],$parent);
			return $this->result;
		}
		return $this->data['records'][0];
	}

	public function get_childs($index)
	{
		$this->cnt++;
		$this->data['records'][$index]['childs']= array();
		foreach($this->data['records'] as $key=>$value){
			if($value['level'] == $this->data['records'][$index]['level']+1)
			{
				if($value['left']>$this->data['records'][$index]['left'] && $value['right']<$this->data['records'][$index]['right'])
				{
						$this->data['records'][$key]['parent'] = $this->data['records'][$index]['id'];
						$this->get_childs($key);
						array_push($this->data['records'][$index]['childs'],$this->data['records'][$key]);
				}
			}
		}
	}

	public function search_parent($array_in,$parent)
	{
		foreach($array_in as $key=>$value)
		{
			if($value['id'] == $parent)
			{
				$this->result = $value;
				return;
			}
			else{
				$this->search_parent($value['childs'],$parent);
			}
		}
	}
	public function correct_links()
	{
		foreach($this->data['records'] as $key=>$value){
			if($value['type'] == 2){
				foreach($this->data['records'] as $key2=>$value2)
				{
					if($value2['id'] == $value['link_id'])
					{
						$this->data['records'][$key]['l_parent'] = $value2['parent'];
					}
				}
			}
		}
	}

	public function get_level_down($node)
	{
		$data = array();
		$ns = new nested_sets($this);
		$data['childs'] = $ns->get_childs($node, NULL);
		return $data;;
	}

	/**
	*	Проверить уникальность введённого имени 
	* @param	integer	$cid	ID компании
	* @param	string	$name	Проверяемое имя
	* @param	integer	$id	ID изменяемой записи
	* @return	boolean		True - если имя уникально, иначе False
	*/
	protected function check_uri($name, $id)
	{
		$this->_flush();
		$this->push_args(array('_sname' => $name));
		if ($id > 0) $this->set_args(array('_nid' => $id), true);
		$this->what = array('COUNT(*)' => 'check');
		$this->_get();
		$this->pop_args();
		return (bool)((int)$this->get_results(0, 'check') == 0);
	}

	/**
	*	Формируем URI для записи на основе его title
	* @param	string	$title	Title записи, если не указан, то берётся из ARGS
	* @param	boolean	$check	Проверить URI на уникальность, в случае не уникальности будет произведена попытка создать оный
	* @return	string,boolean	Новый URI, либо FALSE, в случае ошибки.
	*/
	protected function make_uri($title = null, $check = true)
	{
		$uri = false;

		if (empty($title))
			$title = $this->get_args('title');

		if ($check && !empty($title))
		{
			$b = $this->get_args(array('_sid'), null, true);
			$uri = text::str2url($title);
			$i = 0;

			while (!$this->check_uri($uri, $b))
			{
				$uri = text::str2url($title . strval($i++));
			}
		}

		return $uri;
	}

}
?>
