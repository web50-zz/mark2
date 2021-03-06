<?php
/**
*
* @author	Fedot B Pozdnyakov 9* <9@u9.ru> 14012014
* @package	SBIN Diesel
*/
class di_m2_url_indexer extends data_interface
{
	public $title = 'm2: Маркет 2 - URL индексатор';

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
	protected $name = 'm2_url_indexer';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'url' => array('type' => 'string'),
		'item_id' => array('type' => 'integer'),
		'category_id' => array('type' => 'integer'),
	);

	/**
	* @var	array	$hash	Переменная для хранения ID при удалении
	*/
	protected $hash = array();
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	public function search_by_uri($uri, $columns = null)
	{
		$this->_flush();
		$this->push_args(array('_surl' => $uri));
		$this->what = $columns;
		$this->set_order('id','desc');
		$this->_get();
		$this->pop_args();
		return (array)$this->get_results(0);
	}

	public function search_id_by_uri($uri)
	{
		$this->_flush();
		$this->push_args(array('_surl' => $uri));
		$this->what = array('item_id');
		$this->_get();
		$this->pop_args();
		return (int)$this->get_results(0, 'item_id');
	}



	/**
	*	Обновить все записи для определенной публикации ID
	*/
	protected function update_record($id)
	{
		if(!($id>0))
		{
			return false;
		}
		$this->update_one($id); // 9* апдейт  прямого урл  публикации
		// посмотрим в какие разделы входит публикация 
		$di = data_interface::get_instance('m2_item_category');
		$di ->_flush();
		$di ->push_args(array('_sitem_id'=>$id));
		$di->_get();
		$res = $di->get_results();
		$di->pop_args();
		// 9* если входит в какой то раздел то апдейтим  урлы для нахождения в разделах
		foreach($res as $key=>$value)
		{
			$this->update_one($id,$value->category_id); 
		}
	}
	// Обновить запись в таблице индекса для определенной публикации в  определенном разделе
	protected function update_one($item_id,$category_id = 0)
	{
		$di = data_interface::get_instance('m2_item');
		$di->_flush(true);
		$di->what = array(
			'id' => 'item_id',
			'name'=>'url',
		);
		$di->push_args(array('_sid' => $item_id));
		$di->_get();
		$data = (array)$di->get_results(0);
		$di->pop_args();
		if($category_id>0)
		{
			$di = data_interface::get_instance('m2_category');
			$di->_flush(true);
			$di->what = array(
				'id',
				'uri'=>'url',
			);
			$di->push_args(array('_sid' => $category_id));
			$di->_get();
			$data2 = (array)$di->get_results(0);
			$di->pop_args();
			$data['url'] = $data2['url'].$data['url'].'/';
			$data['category_id'] = $category_id;
		}
		else
		{
			$data['url'] = '/'.$data['url'].'/';
		}
		// Обновляем данные
		$this->_flush();
		$this->push_args($data);
		$this->set_args(array(
				'_sitem_id' => $item_id,
				'_scategory_id'=>$category_id
		
		), true);
		$this->insert_on_empty = true;
//		$this->connector->debug = true;
		$this->_set();
		$this->pop_args();

	}

	protected function update_category($id)
	{
		if(!($id>0))
		{
			return false;
		}
		// 9* берем данные  по категории из базы
		$di = data_interface::get_instance('m2_category');
		$di->_flush(true);
		$di->what = array(
			'id'=>'category_id',
			'uri'=>'url',
		);
		$di->push_args(array('_sid' => $id));
		$di->_get();
		$data = (array)$di->get_results(0);
		$category_url = $data['url'];
		$di->pop_args();
		// Обновляем данные в индексе урл по полученным из базы данных категории
		$this->_flush();
		$this->push_args($data);
		$this->set_args(array(
				'_scategory_id'=>$id,
				'_sitem_id'=>'0',
		
		), true);
		$this->insert_on_empty = true;
		$this->_set();
		$this->pop_args();

		// посотрим нет ли  статей в этой категории
		$di = data_interface::get_instance('m2_item_category');
		$di->_flush(true);
		$di->what = array(
			'item_id',
		);
		$di->push_args(array('_scategory_id' => $id));
		$di->_get();
		$data2 = $di->get_results();
		$di->pop_args();
		//для найденных статей апдейтим url 
		if(count($data2) >0)
		{
			foreach($data2 as $key=>$value)
			{
				$di = data_interface::get_instance('m2_item');
				$di->_flush(true);
				$di->what = array(
					'id' => 'item_id',
					'name'=>'url',
				);
				$di->push_args(array('_sid' => $value->item_id));
				$di->_get();
				$data3 = (array)$di->get_results(0);
				$di->pop_args();
				if($data3['item_id']>0)//9*  бывает что запись удалили  а связка осталась в силу говна как гото потому вот проверим чтобы лишнег не апдейтить todo www_article
				{	
					$this->_flush();
					$input['url'] = $category_url.$data3['url'].'/';
					$input['item_id'] = $data3['item_id'];//9* to www_article todo
					$input['category_id'] = $id;// 9* to www artucle todo
					$this->push_args($input);
					$this->set_args(array(
							'_scategory_id'=>$id,
							'_sitem_id'=>$data3['item_id'],
					
						), true);
	//9* оно и так инсертится почемуто		$this->insert_on_empty = true;   
					$this->_set();
					$this->pop_args();
				}
			}
		}
	}

	protected function update_category_for_item($id,$category_id)
	{
		$this->update_one($id,$category_id);
	}

	/**
	*	Обработчик события "Изменения  компании"
	*
	* @access	public
	* @param	object		$eObj	DI y_comp_settlement
	* @param	array|integer	$ids	ID изменённых компаний
	* @param	array		$args	Массив ARGS который был актуален события
	*/
	public function m2_item_set($eObj, $ids, $args)
	{
		if (!is_array($ids) && $ids > 0)
		{
				$this->update_record($ids);
		}
		else if (is_array($ids))
		{
			foreach ($ids as $id)
			{
				$this->update_record($id);
			}
		}
		else
		{
			// Some error, because unknown settlement ID
		}
	}


	/**
	*	Обработчик события "Удаление компании"
	*
	* @access	public
	* @param	object		$eObj	DI y_comp_settlement
	* @param	array|integer	$ids	ID удалённых компаний
	* @param	array		$args	Массив ARGS который был актуален события
	*/
	public function m2_item_unset($eObj, $ids, $args)
	{
		if (!empty($args['_sid']))
		{
			$this->_flush();
			$this->push_args(array(
				'_sitem_id' => $args['_sid'],
			));
			$this->_unset();
			$this->pop_args();
		}
	}


	/**
	*	Обработчик события "Изменения вхождения  категорию"
	*
	* @access	public
	* @param	object		$eObj	DI www_article_in_category
	* @param	array|integer	$ids	ID изменённых публикаций 
	* @param	array		$args	Массив ARGS который был актуален события
	*/
	public function m2_item_category_set($eObj, $ids, $args)
	{
		if (($id = (int)$args['item_id']) == 0 && !empty($args['_sitem_id']) && !empty($args['_scategory_id']))
		{
			$id = (int)$args['_sitem_id'];
		}
		$category_id = (int)$args['category_id'];
		$this->update_category_for_item($id,$category_id);
	}

	/**
	*	Обработчик события "Изменения  вхождения в категорию"
	*
	* @access	public
	* @param	object		$eObj	DI www_article_in_category 
	* @param	array		$args	Массив ARGS который был актуален события
	*/
	public function m2_item_category_prepare_unset($eObj, $args)
	{
		if (($id = (int)$args['item_id']) == 0 && !empty($args['_sitem_id']))
		{
			$id = (int)$args['_sitem_id'];
		}
		$records =  json_decode($args['records']);
		if($records[0]>0)
		{
			$di = data_interface::get_instance('m2_item_category');
			$di->_flush();
			$di->push_args(array('_sid'=>$records[0]));
			$di->_get();
			$res = $di->get_results();
			$di->pop_args();
			$this->category_id = $res[0]->category_id;
			$this->item_id = $id;
		}
	}

	/**
	*	Обработчик события "Удаление публикации из категории"
	*
	* @access	public
	* @param	object		$eObj	DI www_article_in_category 
	* @param	array|integer	$ids	ID удалённых записей
	* @param	array		$args	Массив ARGS который был актуален события
	*/
	public function m2_item_category_unset($eObj, $ids, $args)
	{
		if (!empty($this->item_id)&&!empty($this->category_id))
		{
			$this->_flush();
			$this->push_args(array(
				'_sitem_id' => $this->item_id,
				'_scategory_id' => $this->category_id,
			));
			$this->_unset();
			$this->pop_args();
		}
	}


	/**
	*	Обработчик события "Изменения категории"
	*
	* @access	public
	* @param	object		$eObj	DI www_article_type
	* @param	array|integer	$ids	ID изменённых публикаций 
	* @param	array		$args	Массив ARGS который был актуален события
	*/
	public function m2_category_set($eObj, $ids, $args)
	{
		if($ids>0)
		{
			$category_id = (int)$ids;
			$this->update_category($category_id);
		}
	}

	/**
	*	Обработчик события "Удаление категории"
	*
	* @access	public
	* @param	object		$eObj	DI www_article_type 
	* @param	array|integer	$ids	ID удалённых записей
	* @param	array		$args	Массив ARGS который был актуален события
	*/
	public function m2_category_unset($eObj, $ids, $args)
	{
		if(is_array($ids) && !(count($ids) >0))
		{
			return;
		}
		$this->_flush();
		$this->push_args(array(
			'_scategory_id' => $ids,
		));
		$this->_unset();
		$this->pop_args();
	}

	public function sys_reindex(){
		$this->reindex();
	}
	public function reindex()
	{
		$our = array();
		$sql = "select id,uri from m2_category where id > 1";
		$cats = $this->_get($sql)->get_results();
		foreach($cats as $k=>$v)
		{
			$out .= '("",0,'.$v->id.',"'.$v->uri.'"),';
		}
		$sql = 'truncate table m2_url_indexer';
		$this->get_connector()->exec($sql);
		$sql = 'insert into m2_url_indexer (`id`,`item_id`,`category_id`,`url`) values '.rtrim($out,',');
		$this->get_connector()->exec($sql);
		$out = '';
		$sql = "select item_id,name,category_list from m2_item_indexer where not_available = 0";
		$res = $this->_get($sql)->get_results();
		$j = 0;
		foreach($res as $k=>$v)
		{
			$j++;
			if($v->item_id>0)
			{
				$out .= '("",'.$v->item_id.',0,"/'.$v->name.'/"),';
				if($v->category_list != '[]' && $v->category_list != '')
				{
					$lst = json_decode($v->category_list);
					if(is_array($lst))
					{
						if(count($lst) > 0)
						{
							foreach($lst as $k1=>$v1)
							{
								$out .= '("",'.$v->item_id.','.$v1->category_id.',"'.$v1->uri.$v->name.'/"),';
							}
						}
					}
				}
				if($j == 500)
				{
					$sql = 'insert into m2_url_indexer (`id`,`item_id`,`category_id`,`url`) values '.rtrim($out,',');
					$this->get_connector()->exec($sql);
					$out = '';
					$sql = '';
					$j = 0;
				}
			}
		}
		if($out != '')
		{
			$sql = 'insert into m2_url_indexer (`id`,`item_id`,`category_id`,`url`) values '.rtrim($out,',');
			$this->get_connector()->exec($sql);
		}
	}


	public function _listeners()
	{
		return array(
			array('di' => 'm2_item', 'event' => 'onSet', 'handler' => 'm2_item_set'),
			array('di' => 'm2_item', 'event' => 'onUnset', 'handler' => 'm2_item_unset'),
			array('di' => 'm2_item_category', 'event' => 'onSet', 'handler' => 'm2_item_category_set'),
			array('di' => 'm2_item_category', 'event' => 'onBeforeUnset', 'handler' => 'm2_item_category_prepare_unset'),
			array('di' => 'm2_item_category', 'event' => 'onUnset', 'handler' => 'm2_item_category_unset'),
			array('di' => 'm2_category', 'event' => 'onSet', 'handler' => 'm2_category_set'),
			array('di' => 'm2_category', 'event' => 'onUnset', 'handler' => 'm2_category_unset'),
		);
	}
}
?>
