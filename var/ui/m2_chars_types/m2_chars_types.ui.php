<?php
/**
*
* @author      9@u9.ru	
* @package	SBIN Diesel
*/
class ui_m2_chars_types extends user_interface
{
	public $title = 'm2: Маркет 2 - Типы характеристик';

	protected $deps = array(
		'main' => array(
			'm2_chars_types.tree',
		),
		'project_form' => array(
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
	public function sys_tree()
	{
		$tmpl = new tmpl($this->pwd() . 'tree.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS - Форма типа проекта
	*/
	public function sys_group_form()
	{
		$tmpl = new tmpl($this->pwd() . 'group_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS - Форма проекта
	*/
	public function sys_project_form()
	{
		$tmpl = new tmpl($this->pwd() . 'project_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
