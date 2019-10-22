<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 30062013	
* @package	SBIN Diesel
*/
class di_m2_item extends data_interface
{
	public $title = 'm2: Маркет 2 - Каталог';

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
	protected $name = 'm2_item';

	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'order' => array('type' => 'integer'),
		'name' => array('type' => 'string'),
		'not_available' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'price' => array('type' => 'string'),
		'price2' => array('type' => 'string'),
		'article' => array('type' => 'string'),
		'meta_title' => array('type' => 'string'),
	);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	protected function sys_list()
	{
		$this->_flush();
		//$this->set_order('order', 'ASC');
		list($field, $query) = array_values($this->get_args(array('field', 'query')));
		$sort_price_type = registry::get('MAIN_PRICE_TYPE');
		if(!$sort_price_type)
		{
			$sort_price_type = 5;
		}
		if (!empty($field) && !empty($query))
		{
			if($this->args['field'] != 'id')
				$this->set_args(array("_s{$field}" => "%{$query}%"), true);
			else
				$this->set_args(array("_s{$field}" => $query), true);
		}

		if($this->args['by_category'] == true)
		{
			$this->sys_search_by_category();
		}
		$di2 = $this->join_with_di('m2_chars',array('id'=>'m2_id','128'=>'type_id'),array('variable_value'=>'discount'));
		$di3 = $this->join_with_di('m2_item_price',array('id'=>'item_id',$sort_price_type=>'type'),array('price_value'=>'cprice'));
		$this->extjs_grid_json(array(
			'id', 
			'order', 
			'name', 
			'title',
			'article',
			'not_available',
			array('di'=>$di2,'name'=>'variable_value'),
			array('di'=>$di3,'name'=>'price_value'),
			)
		);

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
	*	Добавить \ Сохранить 
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
		else if(array_key_exists('order',$args) && $args['order'] == '0')
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
		response::send($result, 'json');
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

	protected function sys_search_by_category()
	{
		$di = $this->join_with_di('m2_item_category',array('id'=>'item_id'),array('category_id','category_id'));
		$this->where = $di->get_alias().'.category_id = '.$this->args['category_id'];
		$this->extjs_grid_json(array('id', 'order', 'name', 'title',
			array('di'=>$di,'name'=>'category_id')
		));
	}


	protected function prepare_uri()
	{
		$config = array();
		$name = $this->get_args('name');
		$title = $this->get_args('title');
		$id = $this->get_args('_sid');
		$di = data_interface::get_instance('m2_utils');
		$config = array(
			'm2_item'=>array(
					'field'=>'name',
					'id'=>$id
				),
			'm2_category'=>array(
					'field'=>'name'
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
			//dbg::write($e->getMessage());
		}
		return $uri;
	}
// это для поиска предметов в группе товаров  винтерфкейсе m2_item_in_groups

	protected function sys_choice()
	{
		response::send($this->choice(), 'json');
	}
	
	public function choice()
	{
		$lid = $this->get_args('lid', 0);
		$this->_flush(true);
		$tbl = $this->get_name();
		$sbr = $this->join_with_di('m2_item_in_groups', array('id' => 'item_id', $lid => 'group_id'), array('id' => 'cid'));

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

	protected function sys_make_copy()
	{
		try
		{
			$id = $this->get_args('_sid');
			if(!($id>0))
			{
				throw new Exception('Не задан ID');
			}
			$this->_flush();
			$res = $this->extjs_form_json(false,false);
			$title = $res['data']['title'];// это для реиндекса внизу
			if(!($res['success'] == true))
			{
				throw new Exception('Не найден');
			}
			$input = $res['data'];
			unset($input['id']);
			unset($input['order']);
			unset($input['name']);
			$input['article'] = $this->prepare_article();
			$this->set_args($input);
			$res = $this->sys_set(true);
			if(!($res['success'] == true))
			{
				throw new Exception('Ошибка копирования товара');
			}
			$new_id = $res['data']['id'];
			$tbls = array('m2_chars','m2_item_price','m2_item_manufacturer','m2_item_category','m2_item_text','m2_item_links','m2_item_files');
			foreach($tbls as $k1=>$v1)
			{
				$f = 'item_id';
				if($v1 == 'm2_chars')
				{
					$f = 'm2_id';
				}
				$di = data_interface::get_instance($v1);
				$di ->set_args(array("_s{$f}"=>$id));
				$res = $di->extjs_grid_json(false,false);
				$vals = '';
				if($res['success'] == true)
				{
					if(is_array($res['records']) && count($res['records'])>0)
					{
						foreach($res['records'] as $k=>$v)
						{
							unset($v['id']);
							$v[$f] = $new_id;
							if($v1 == 'm2_item_files')
							{
								$d = data_interface::get_instance('m2_item_files');
								$path = $d->get_path_to_storage();
								$fl = $path.$v['real_name'];
								if(is_file($fl))
								{
									$df = data_interface::get_instance('m2_item_files');
									$input = $v;
									unset($input['id']);
									unset($input['real_name']);
									$input['source'] =$fl;
									$df->set_args($input);
									$df->sys_set(true);
								}
							}
							$vals.= ",('','".implode("','",$v)."')";

						}
						if($v1 != 'm2_item_files')
						{
							$sql = "insert into $v1 "."(`".implode("`,`",array_keys($res['records'][0]))."`)"." values ".substr($vals,1);
							$con = $di->get_connector();
							$con->exec($sql);
						}
					}
				}
			}
			//reindex
			$input = array('_sid'=>$new_id,'title'=>$title);
			$this->set_args($input);
			$res = $this->sys_set(true);

		}
		catch (exception $e)
		{
			response::send(array('success' => false,'msg'=>$e->getMessage()),'json');
		}
		response::send(array('success' => true,'msg'=>'Копирование прошло успешно. Новый ID:'.$new_id),'json');
	}


	protected function prepare_article()
	{
		$n = substr(md5(time().rand()),0,7);
		if($this->check_article_uniq($n))
		{
			return $n;
		}
		$this->prepare_article();
	}

	// проверяем есть ли такой артикул уже. Если нет возвращаем true
	protected function check_article_uniq($a)
	{
		$sql = 'select count(*) as a from '.$this->name." where `article` = '$a'";
		$res = $this->_get($sql)->get_results($sql);
		if($res->a >0)
		{
			return false;	
		}
		return true;
	}


}
?>
