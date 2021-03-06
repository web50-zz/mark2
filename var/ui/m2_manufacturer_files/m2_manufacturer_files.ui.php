<?php
/**
*
* @author       9*  9@u9.ru  	10.01.2017
* @package	SBIN Diesel
*/
class ui_m2_manufacturer_files extends user_interface
{
	public $title = 'm2: Маркет 2 - Каталог файлы производителя';

	protected $deps = array(
		'main' => array(
			'm2_manufacturer_files.item_form',
			'm2_manufacturer_files.grid',
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
