<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 26062013	
* @package	SBIN Diesel
*/
class ui_m2_category extends user_interface
{
	public $title = 'm2: Маркет 2  -  категории';

	protected $deps = array(
		'main' => array(
			'm2_category.tree',
		),
		'node_form' => array(
			//'m2_category_file.main',
			//'m2_category_tabs.main',
			//'m2_chars.main',
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
	public function sys_node_form()
	{
		$tmpl = new tmpl($this->pwd() . 'node_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	/**
	*       ExtJS - Форма проекта
	*/
	public function sys_category_selection()
	{
		$tmpl = new tmpl($this->pwd() . 'category_selection.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
