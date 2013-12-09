<?php
/**
*
* @author      9*  9@u9.ru 07072012	
* @package	SBIN Diesel
*/
class ui_m2_search extends user_interface
{
	public $title = 'm2: Маркет 2  поиск  по каталогу';

	protected $deps = array(
		'main' => array(
			'm2_item.main',
			'm2_search.main_filter',
			'm2_category.main',
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
	*       Main interface
	*/
	protected function sys_main_filter()
	{
		$tmpl = new tmpl($this->pwd() . 'main_filter.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
