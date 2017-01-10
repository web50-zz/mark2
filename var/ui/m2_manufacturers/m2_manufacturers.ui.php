<?php
/**
*
* @author	FedotB Pozdnyakov 9@u9.ru 17072013
* @package	SBIN Diesel
*/
class ui_m2_manufacturers extends user_interface
{
	public $title = 'm2: Маркет 2 - Производители справочник';

	protected $deps = array(
		'main' => array(
			'm2_manufacturers.grid',
		),
		
		'choice' => array(
			'm2_manufacturers.grid2',
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

	protected function sys_choice()
	{
		$tmpl = new tmpl($this->pwd() . 'choice.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	protected function sys_grid2()
	{
		$tmpl = new tmpl($this->pwd() . 'grid2.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
