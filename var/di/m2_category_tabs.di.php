<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 26062013	
* @package	SBIN Diesel
*/
class di_m2_category_tabs extends data_interface
{
	public $title = 'm2: Маркет 2 - категории табы';

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
	protected $name = 'm2_category_tabs';

	/**
	* @var	string	$path_to_storage	Путь к хранилищу файлов каталога
	*/
	public $path_to_storage = 'filestorage/';

	/**
	* @var	array	$_preview	Массив с описанием preview-файлов
	*/
	protected $_preview = array(
		'thumb' => array(
			'width' => 332,
			'height' => 294,
			'adaptive' => true,	//Адаптивное масштабирование true - Да, false - Нет
			'bckgr' => null,	//Указывается цвет подложки (white, black, red и т.п.), если null, то подложка не используется
		)
	);
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'm2_category_id' => array('type' => 'integer', 'alias' => 'pid'),
		'order' => array('type' => 'integer'),
		'type' => array('type' => 'integer'),
		'name' => array('type' => 'string'),
		'title' => array('type' => 'string'),
		'content' => array('type' => 'string'),
	);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	/**
	*	Получить путь к хранилищу файлов на файловой системе
	*/
	public function get_path_to_storage()
	{
		return BASE_PATH . $this->path_to_storage;
	}

	public function get_url()
	{
		return '/'.$this->path_to_storage;
	}

	/**
	*	Применить параметры для превью
	* @param	string	$reg_key	Имя ключа в реестре с параметрами preview
	*/
	protected function prepare_preview_params($reg_key = 'm2_category_tabs')
	{
		if ($reg_key && ($new_params = registry::get($reg_key)) != '')
		{
			// {"thumb": {"width": 332, "height": 294, "adaptive": true, "bckgr": null}}
			$this->_preview = $new_params;
		}
	}
	
	/**
	*	Получить размеры указанного изображения
	*/
	protected function sys_get_size()
	{
		$this->_flush();
		$this->what = array('width', 'height');
		$this->_get();
		response::send(array(
			'success' => true,
			'data' => $this->get_results(0)
		), 'json');
	}
	
	protected function sys_list()
	{
		$this->_flush();
		$this->set_order('order', 'ASC');
		$di =  $this->join_with_di('m2_text_types',array('type'=>'id'),array('title'=>'type_str'));
		$this->extjs_grid_json(array(
					'id', 
					'order',
					'name',
					'type',
					'title',
					array('di'=>$di,'name'=>'title'),
		));

		$this->extjs_grid_json(array('id', 'order', 'name', 'title'));
	}
	
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}

	/**
	*	Реорганизация порядка вывода
	*/
	protected function sys_reorder()
	{
		list($npos, $opos) = array_values($this->get_args(array('npos', 'opos')));
		$values = $this->get_args(array('opos', 'npos', 'id', 'pid'));

		if ($opos < $npos)
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` - 1) WHERE `order` >= :opos AND `order` <= :npos AND `m2_category_id` = :pid";
		else
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` + 1) WHERE `order` >= :npos AND `order` <= :opos AND `m2_category_id` = :pid";

		$this->_flush();
		$this->connector->exec($query, $values);
		response::send(array('success' => true), 'json');
	}
	
	/**
	*	Добавить \ Сохранить файл
	*/
	protected function sys_set()
	{
		$fid = $this->get_args('_sid');

		if ($fid > 0)
		{
			$this->_flush();
			$this->_get();
			$file = $this->get_results(0);
			$old_file_name = $file->real_name;
		}
		$file = array();
		$args =  $this->get_args();
		if (!($fid > 0))
		{
			$args['order'] = $this->get_new_order((int)$this->get_args('m2_category_id'));
		}
		$this->set_args($args);
		$this->_flush();
		$this->insert_on_empty = true;
		$result = $this->extjs_set_json(false);
		response::send($result, 'json');
	}
	

	/**
	*
	*/
	private function get_new_order($pid)
	{
		$this->_flush();
		$this->_get("SELECT MAX(`order`) + 1 AS `order` FROM `{$this->name}` WHERE `m2_category_id` = {$pid}");
		return $this->get_results(0, 'order');
	}
	
	/**
	*	Удалить файл[ы]
	* @access protected
	*/
	protected function sys_unset()
	{
		if ($this->args['records'] && !$this->args['_sid'])
		{
			$this->args['_sid'] = request::json2int($this->args['records']);
		}
		$this->_flush();
		$files = $this->_get();
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		
		/* NOTE: Не совсем понятна целесообразность дальнешего кода,
		*	т.к. в форме редактирования никаких файлов не загружается, но поля в таблице предусмотрены.
		*	Возможно, подлежит удалению, т.к. явлвяется наследием copy-past
		*/
		if (!empty($files))
		{
			$this->prepare_preview_params();
			foreach ($files as $file)
			{
				if ($file->real_name)
				{
					file_system::remove_file($file->real_name, $this->get_path_to_storage());
					foreach ($this->_preview as $pref => $params)
					{
						file_system::remove_file("{$pref}-{$file->real_name}", $this->get_path_to_storage());
					}
				}
			}
		}

		response::send($data, 'json');
	}

	/**
	*	Remove all files
	* @access public
	* @param	array|integer	$ids	The ID for `catalog_item_id` field
	*/
	public function remove_files($ids)
	{
		/* NOTE: Судя по всему наследие copy-past, т.к. метод в DI нигде не дёргается,
		*	а параметр _spid, должен быть _sid, т.к. удаляем файлы соответствующие удаляемой записи
		*	Конструкция вида "$files = $this->_get()" является Deprecated.
		*	Результат запроса можно получить через $this->get_results(), после вызова $this->_get()
		*/
		$this->set_args(array('_spid' => $ids));
		$this->_flush();
		$files = $this->_get();
		$this->_flush();
		$this->extjs_unset_json(false);
		if (!empty($files))
		{
			$this->prepare_preview_params();
			foreach ($files as $file)
			{
				file_system::remove_file($file->real_name, $this->get_path_to_storage());
				foreach ($this->_preview as $pref => $params)
				{
					file_system::remove_file("{$pref}-{$file->real_name}", $this->get_path_to_storage());
				}
			}
		}
	}

	/**
	*	Обработчик удаления категори(и|й)
	*/
	public function unset_for_category($eObj, $ids, $args)
	{
		// Получаем файлы, привязанные к удаляем(ой|ым) категориям
		$fids = $this->_flush()
			->push_args(array('_sm2_category_id' => $ids))
			->set_what(array('id'))
			->_get()
			->pop_args()
			->get_results(false, 'id');

		if (!empty($fids))
		{
			// Удаляем файлы штатными средкствами
			$this->push_args(array('_sid' => $fids));
			$this->sys_unset(true);
			$this->pop_args();
		}
	}

	public function _listeners()
	{
		return array(
			array('di' => 'm2_category', 'event' => 'onUnset', 'handler' => 'unset_for_category'),
		);
	}
}
?>
