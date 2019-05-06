<?php
/**
*
* @author	9* <9@u9.ru> 08.11.2018
* @package	SBIN Diesel
*/
class ui_m2_item_in_groups extends user_interface
{
	public $title = 'mark2: Группы проиводителей';

	protected $deps = array(
		'main' => array(
			'm2_item_in_groups.subscribers',
			'm2_item_group.mailer_list',
		),
		'subscribers' => array(
			'm2_item_in_groups.grid',
		),
	);
	
	public function __construct()
	{
		parent::__construct(__CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
	
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*      Точка входа в приложение
	*/
	protected function sys_subscribers()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribers.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*      ExtJS.Grid - список документов "Рейс"
	*/
	protected function sys_grid()
	{
		$tmpl = new tmpl($this->pwd() . 'grid.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*      ExtJS.Grid - список рассылок на которые подпсан контакт
	*/
	protected function sys_subscribed_to()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribed_to.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
