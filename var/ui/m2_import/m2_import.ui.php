<?php
/**
*
* @author       9*  9@u9.ru  	
* @package	SBIN Diesel
*/
class ui_m2_import extends user_interface
{
	public $title = 'm2: Маркет 2 - Форма загрузки  импорта';

	protected $deps = array(
		'main' => array(
			'm2_import.item_form',
		),
		'grid' => array(
		),
		'item_form' => array(
		),
	);

	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}

	/**
	*       Main interface
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	protected function sys_grid()
	{
		$tmpl = new tmpl($this->pwd() . 'grid.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	protected function sys_types()
	{
		$tmpl = new tmpl($this->pwd() . 'types.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
