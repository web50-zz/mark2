<?php
/**
*
* @author	FedotB Pozdnyakov 9@u9.ru 10072013
* @package	SBIN Diesel
*/
class ui_m2_item_price extends user_interface
{
	public $title = 'm2: Маркте 2 - айтем цена ';

	protected $deps = array(
		'main' => array(
			'm2_item_price.grid',
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
}
?>
