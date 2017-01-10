<?php
/**
*
* @author	9* 9@u9.ru 10.01.2017
* @package	SBIN Diesel
*/
class ui_m2_manufacturer_group extends user_interface
{
	public $title = 'Рассылка: Списки';

	protected $deps = array(
		'main' => array(
			'm2_manufacturer_group.mailer_list',
		),
		'mailer_list' => array(
			'm2_manufacturer_group.grid',
		),
		'mailer_list_chooser' => array(
			'm2_manufacturer_group.grid',
		),
		'choice' => array(
			'm2_manufacturer_group.grid2',
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

}
?>
