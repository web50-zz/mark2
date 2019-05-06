<?php
/**
*
* @author	FedotB Pozdnyakov 9@u9.ru 30062013
* @package	SBIN Diesel
*/
class ui_m2_item extends user_interface
{
	public $title = 'm2: Маркте 2 - Каталог';

	protected $deps = array(
		'main' => array(
			'm2_item.grid',
			'm2_item_price.main',
			'm2_item_manufacturer.main',
		),
		'item_selection' => array(
			'm2_item.grid',
		),
		'choice' => array(
			'm2_item.grid2',
		),

	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
	
	/**
	*       Управляющий JS админки
	*/
	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Управляющий JS админки
	*/
	public function sys_grid()
	{
		$tmpl = new tmpl($this->pwd() . 'grid.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS - Форма редактирования
	*/
	public function sys_form()
	{
		$tmpl = new tmpl($this->pwd() . 'form.js');
		response::send($tmpl->parse($this), 'js');
	}

	/* 
	*	Селектор предмета
	*/

	public function sys_item_selection()
	{
		$tmpl = new tmpl($this->pwd() . 'item_selection.js');
		response::send($tmpl->parse($this), 'js');
	}
	/* 
	*	Селектор предмета версия с мультивыбором
	*/

	public function sys_choice()
	{
		$tmpl = new tmpl($this->pwd() . 'choice.js');
		response::send($tmpl->parse($this), 'js');
	}

	public function sys_grid2()
	{
		$tmpl = new tmpl($this->pwd() . 'grid2.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
