<?php
/**
*
* @author	Fedot B Pozdnyakov 9* <9@u9.ru> 18012014
* @package	SBIN Diesel
*/
class di_m2_utils extends data_interface
{
	public $title = 'm2: Маркет 2 - утилиты';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = '';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
	);

	/**
	* @var	array	$hash	Переменная для хранения ID при удалении
	*/
	protected $hash = array();
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	public function prepare_uri($config = array(),$name = '')
	{
		if($name == '')
		{
			throw new Exception('Невозможно сгенерировать');
		}
		$uri = text::str2url($name);
		$i = 0;

		while (!$this->check_uri($config,$uri))
		{
			$uri = text::str2url($name . strval($i++));
		}
		return $uri;
	}
	/**
	*	Проверить уникальность введённого имени 
	* @param	integer	$cid	ID компании
	* @param	string	$name	Проверяемое имя
	* @param	integer	$id	ID изменяемой записи
	* @return	boolean		True - если имя уникально, иначе False
	*/
	protected function check_uri($config,$name)
	{
		$ret = true;
		foreach($config as $key=>$value)
		{
			$di_name = $key;
			$id = $value['id'];
			$di = data_interface::get_instance($di_name);
			$di->_flush();
			$args = array('_s'.$value['field'] => $name);
			if ($id > 0)
			{
				$args['_nid'] = $id;
			}
			$di->push_args($args);
			$di->what = array('COUNT(*)' => 'check');
			$di->_get();
			$res = $di->get_results(0, 'check');
			$di->pop_args();
			$ret = (bool)((int)$res == 0);
			if($ret == false)
			{
				break;
			}

		}
		return $ret;
	}	

}
?>
