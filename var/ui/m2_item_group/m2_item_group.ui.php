<?php
/**
*
* @author	9* 9@u9.ru 08.10.2018
* @package	SBIN Diesel
*/
class ui_m2_item_group extends user_interface
{
	public $title = 'mark2: Группы товаров';
	public $data_buffer = array(); //сюда складываем чтото из внешних уи ди для парсинга в шаблон

	protected $deps = array(
		'main' => array(
			'm2_item_group.mailer_list',
		),
		'mailer_list' => array(
			'm2_item_group.grid',
		),
		'mailer_list_chooser' => array(
			'm2_item_group.grid',
		),
		'choice' => array(
			'm2_item_group.grid2',
		),
	);
	
	public function __construct()
	{
		parent::__construct(__CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
	
	/**
	*      Точка входа в приложение
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*      Обвязка для списка рассылки
	*/
	protected function sys_mailer_list()
	{
		$tmpl = new tmpl($this->pwd() . 'mailer_list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*      Обвязка для списка рассылки
	*/
	protected function sys_mailer_list_chooser()
	{
		$tmpl = new tmpl($this->pwd() . 'mailer_list_chooser.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*      ExtJS.Grid - рассылки
	*/
	protected function sys_grid()
	{
		$tmpl = new tmpl($this->pwd() . 'grid.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*      ExtJS.Form - Форма редактирования списка
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	/*
	* 9* 18092013 финальнй грид для выбора списка рассылки для виджета mailer_include 
	*  наследует  grid2  данного UI
	*/
	protected function sys_choice()
	{
		$tmpl = new tmpl($this->pwd() . 'choice.js');
		response::send($tmpl->parse($this), 'js');
	}
	/*
	* 9* 18092013 грид для выбора списка рассылки для виджета mailer_include 
	*
	*/
	protected function sys_grid2()
	{
		$tmpl = new tmpl($this->pwd() . 'grid2.js');
		response::send($tmpl->parse($this), 'js');
	}

	public function pub_content()
	{
		$template = $this->get_args('template', 'default.html');
		$data = array();
		// Шаблон
		$args = $this->get_args();
		$sort = $this->get_args('sort','id');
		$dir = $this->get_args('dir','asc');
		$limit = $this->get_args('limit','10');
		$group_id = $this->get_args('group_id','0');
		if($group_id == 0)
		{
			return '';
		}
		$di1 = data_interface::get_instance('m2_item_group');
		$di1->_flush();
		$di1->set_args(array('_sid'=>$group_id));
		$res = $di1->extjs_grid_json(false,false);
		$data['group'] = $res['records'][0];
		$di = data_interface::get_instance('m2_item_in_groups');
		$di->set_args(array('sort'=>$sort,'dir'=>$dir,'id'=>$group_id,'limit'=>$limit));
		$res =  $di->get_list();
		$data['records'] = $res;
		$data['args'] = $args;
		$data['basket'] = $_SESSION['mf2_cart'];
		$this->fire_event('before_parse', array($this));
		$data = array_merge($data,$this->data_buffer);
		return $this->parse_tmpl($template,$data);

	}
}
?>
