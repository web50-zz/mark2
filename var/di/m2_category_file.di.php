<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 26062013	
* @package	SBIN Diesel
*/
class di_m2_category_file extends data_interface
{
	public $title = 'm2: Маркет 2 - категории файлы';

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
	protected $name = 'm2_category_file';

	/**
	* @var	string	$path_to_storage	Путь к хранилищу файлов каталога
	*/
	public $path_to_storage = 'mark2/m2_category_files/';

	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'm2_category_id' => array('type' => 'integer', 'alias' => 'pid'),
		'order' => array('type' => 'integer'),
		'name' => array('type' => 'string'),
		'title' => array('type' => 'string'),
		'real_name' => array('type' => 'string'),
		'type' => array('type' => 'integer'),
		'file_type' => array('type' => 'integer'),
		'size' => array('type' => 'integer'),
		'width' => array('type' => 'integer'),
		'height' => array('type' => 'integer'),
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
		return FILE_STORAGE_PATH . $this->path_to_storage;
	}

	public function get_url()
	{
		return '/filestorage/'.$this->path_to_storage;
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
		$di =  $this->join_with_di('m2_file_types',array('file_type'=>'id'),array('title'=>'file_type_str','prefix'=>'prefix','is_image'=>'is_image'));
		$this->extjs_grid_json(array(
					'id', 
					'order', 
					'name', 
					'real_name', 
					'size', 
					'width', 
					'height',
					'file_type',
					'title',
					'type',
					'"'.$this->get_url().'"' => 'url',
					array('di'=>$di,'name'=>'title'),
					array('di'=>$di,'name'=>'prefix'),
					array('di'=>$di,'name'=>'is_image'),
		));
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
		$fid = $this->get_args('_sid',0);
		$silent = $this->get_args('silent',false);
		$from_source = $this->get_args('source',false);
		if ($fid > 0)
		{
			$this->_flush();
			$this->_get();
			$file = $this->get_results(0);
			$old_file_name = $file->real_name;
		}
		else
		{
			$this->args['created_datetime'] = date('Y-m-d H:i:s');
			$this->args['creator_uid'] = UID;
		}
		$this->args['changed_datetime'] = date('Y-m-d H:i:s');
		$this->args['changer_uid'] = UID;

		if(!is_dir($this->get_path_to_storage()))
		{
				mkdir($this->get_path_to_storage(),0777,true);
		}
		if(!empty($old_file_name))
		{
			if($from_source != false)
			{
				$file = file_system::copy_file($from_source,$this->get_path_to_storage(), $old_file_name);
			}
			else
			{
				$file = file_system::replace_file('file', $old_file_name, $this->get_path_to_storage());
			}
		}else{
			if($from_source != false)
			{
				$file = file_system::copy_file($from_source, $this->get_path_to_storage());
			}
			else
			{
				$file = file_system::upload_file('file', $this->get_path_to_storage());
			}
		};
		
		if ($file !== false)
		{
			if (!($fid > 0))
			{
				$file['order'] = $this->get_new_order((int)$this->get_args('m2_category_id'));
			}
			// Если мы имеем имя файла и тип файла определен  
			if ($file['real_name'] && $this->get_args('file_type') > 0)
			{
				$di = data_interface::get_instance('m2_file_types');
				if($type_data = $di->get_type_data($this->get_args('file_type')))
				{
					// Тип файла изображение - будем делать превью
					if($type_data->is_image == 1)
					{
						// Формируем параметры превью
						$width = $type_data->width;
						$height = $type_data->height;
						$prefix = $type_data->prefix.'-';
						// если по типу параметров нет, делаем дефолт
						if(!($width>0)){
							$width = 200;
						}
						if(!($height)>0){
							$height = 130;
						}
						if(!($prefix)){
							$prefix = 'thumb-';
						}

						list($file['width'], $file['height']) = getimagesize($this->get_path_to_storage() . $file['real_name']);

						// Удаляем старые превьюшки, если это замена существующего файла
						if (!empty($old_file_name))
						{
							file_system::remove_file("$prefix{$old_file_name}", $this->get_path_to_storage());
						}

						$this->create_preview($file, $prefix, $width, $height, true, null);
						// $mask = BASE_PATH.'themes/lndp/img/news_small_mask.png';
						// not works this time	$this->mask_preview($thumb_name,$mask);
					}
				}
			}

			$this->set_args($file, true);
			$this->_flush();
			$this->insert_on_empty = true;
			$result = $this->extjs_set_json(false);
		}
		else
		{
			$result = array('success' => false);
		}
		if($silent == true)
		{
			$this->args['result'] = $result;
			return;
		}
		response::send(response::to_json($result), 'html');
	}
	
	/**
	*	Создать preview-изображение
	*
	* @access	private
	* @param	array	$file	Массив с данными по текущему файлу
	* @param	string	$pref	Префикс превьюшки, по умолчанию 'thumb-'
	* @param	integer	$width	Ширина превьюшки, по умолчанию 300px
	* @param	integer	$height	Высота превьюшки, по умолчанию 300px
	* @param	boolean	$adaptive	Адаптивное масштабирование true - Да, false - Нет
	* @param	string	$bckgr	Указывается цвет подложки (white, black, red и т.п.), если null, то подложка не используется
	*/
	private function create_preview($file, $pref = "thumb-", $width = 300, $height = 300, $adaptive = true, $bckgr = null)
	{
		require_once INSTANCES_PATH .'wwwcore/lib/thumb/ThumbLib.inc.php';
		$file_name = $this->get_path_to_storage() . $file['real_name'];
		$thumb_name = $this->get_path_to_storage() . $pref . $file['real_name'];
		$thumb = PhpThumbFactory::create($file_name);
		// Если указан адаптивное изменение размеров, то применяем adaptiveResize()
		if ($adaptive)
			$thumb->adaptiveResize($width, $height);
		else
			$thumb->resize($width, $height);
		// Сохраняем превью в указанный файл
		$thumb->save($thumb_name);

		// Если указана полдложка, то накладываем её
		if ($bckgr !== null)
		{
			$crImg = new Imagick($thumb_name);
			$bgImg = new Imagick();
			$bgImg->newImage($width, $height, new ImagickPixel('white'));
			$bgImg->setImageFormat($crImg->getImageFormat());
			$bgImg->setImageColorspace($crImg->getImageColorspace());
			$bgImg->compositeImage($crImg, $crImg->getImageCompose(), (int)($width - $crImg->getImageWidth()) / 2, (int)($height - $crImg->getImageHeight()) / 2);
			$bgImg->writeImage($thumb_name);
		}
	}
	/**
	*	Создать watermark
	*
	* @access	private
	* @param	string	$position	Позиция водного знака
	*						cc = center center
	*						lt = left top
	*						rt = right top
	*						lb = left bottom
	*						rb = right bottom
	*						cb = center bottom
	* @param	integer	$padding	number like 10 = padding 10px 
	*/
	private function create_watermark($file, $position = 'cc', $padding = 0, $resize_factor = 1)
	{
		require_once INSTANCES_PATH .'wwwcore/lib/thumb/ThumbLib.inc.php';
		$mark = BASE_PATH . CURRENT_THEME_PATH . "img/mark.png";
		$src = $this->get_path_to_storage() . $file['real_name'];
		$thumb = PhpThumbFactory::create($src);
		$thumb->createWatermark($mark, $position, $padding, $resize_factor);
		$thumb->save($src);
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
	protected function sys_unset($silent = false)
	{
		if ($this->args['records'] && !$this->args['_sid'])
		{
			$this->args['_sid'] = request::json2int($this->args['records']);
		}
		$this->_flush();
		$this->_get();
		$files = $this->get_results();
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		if (!empty($files))
		{
			foreach ($files as $file)
			{
				if ($file->real_name)
				{
					file_system::remove_file($file->real_name, $this->get_path_to_storage());
					if ($file->file_type > 0)
					{
						$di = data_interface::get_instance('m2_file_types');
						if($type_data = $di->get_type_data($file->file_type))
						{
							$prefix = $type_data->prefix.'-';
							if(!($prefix)){
								$prefix = 'thumb-';
							}
							file_system::remove_file("$prefix{$file->real_name}", $this->get_path_to_storage());
						}
					}
				}
			}
		}
		if($silent == false)
		{
			response::send($data, 'json');
		}
		return $data;
	}

	/**
	*Remove all files
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
		$this->_get();
		$files = $this->get_results();
		$this->_flush();
		$this->extjs_unset_json(false);
		if (!empty($files))
		{
			foreach ($files as $file)
			{
				file_system::remove_file($file->real_name, $this->get_path_to_storage());
				if ($file->file_type == 1)
				{
					file_system::remove_file("thumb-{$file->real_name}", $this->get_path_to_storage());
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
