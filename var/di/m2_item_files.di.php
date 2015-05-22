<?php
/**
*
* @author	9* 9@u9.ru 	
* @package	SBIN Diesel
*/
class di_m2_item_files extends data_interface
{
	public $title = 'm2: Маркет 2 - Каталог файлы элемента';
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
	protected $name = 'm2_item_files';

	/**
	* @var	string	$path_to_storage	Путь к хранилищу файлов каталога
	*/
	public $path_to_storage = 'mark2/m2_item_files/';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'creator_uid' => array('type' => 'integer'),
		'changer_uid' => array('type' => 'integer'),
		'changed_datetime' => array('type' => 'datetime'),
		'created_datetime' => array('type' => 'datetime'),
		'order' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'real_name' => array('type' => 'string'),
		'comment' => array('type' => 'text'),
		'type' => array('type' => 'string'),
		'size' => array('type' => 'integer'),
		'width' => array('type' => 'integer'),
		'height' => array('type' => 'integer'),
		'file_type' => array('type' => 'integer'),
		'item_id' => array('type' => 'integer'),
		
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
		return FILE_STORAGE_PATH.$this->path_to_storage;
	}

	/**
	*	Получить путь к хранилищу файлов на файловой системе
	*/
	public function get_url()
	{
		return '/filestorage/'.$this->path_to_storage;
	}

	public function get_list()
	{
		$this->_flush();
		$this->push_args(array());
		$this->_get();
		$data = $this->get_results();
		$this->pop_args();
		return $data;
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
		$this->_flush(true);
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
		$this->extjs_form_json(array('*', '"'.$this->get_url().'"' => 'url'));
	}

	/**
	*	Реорганизация порядка вывода
	*/
	protected function sys_reorder()
	{
		list($npos, $opos) = array_values($this->get_args(array('npos', 'opos')));
		$values = $this->get_args(array('opos', 'npos', 'id', 'cid'));

		if ($opos < $npos)
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` - 1) WHERE `order` >= :opos AND `order` <= :npos AND `item_id` = :cid";
		else
			$query = "UPDATE `{$this->name}` SET `order` = IF(`id` = :id, :npos, `order` + 1) WHERE `order` >= :npos AND `order` <= :opos AND `item_id` = :cid";

		$this->_flush();
		$this->connector->exec($query, $values);
		$this->fire_event('onSet', array(null, array('item_id' => $values['cid'])));
		response::send(array('success' => true), 'json');
	}
	/**
	*	Добавить \ Сохранить файл
	*/

	public function sys_set($silent = false)
	{
		$fid = $this->get_args('_sid',0);
		$from_source = $this->get_args('source',false);
		$regen = $this->get_args('regen',false);
		if ($fid > 0)
		{
			$this->_flush();
			$this->_get();
			$file = $this->get_results(0);
			$old_file_name = $file->real_name;
			$file_ish = get_object_vars($file);
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
		if($regen == true)
		{
			$file = $file_ish;
		}
		if ($file !== false)
		{
			if (!($fid > 0))
			{
				$file['order'] = $this->get_new_order();
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
			return $result;
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
	private function get_new_order()
	{
		$this->_flush();
		$this->_get("SELECT MAX(`order`) + 1 AS `order` FROM `{$this->name}`");
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
		if($silent == false)
		{
			response::send($data, 'json');
		}
		return $data;
	}

	/**
	*	Remove all files
	* @access public
	* @param	array|integer	$ids	The ID for `catalog_item_id` field
	*/
	public function remove_files($ids)
	{
		$this->set_args(array('_spid' => $ids));
		$this->_flush();
		$files = $this->_get();
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

	public function unset_for_item($eObj, $ids, $args)
	{
		$this->push_args(array());
		if (!is_array($ids) && $ids > 0)
		{
			$this->set_args(array(
				'_sitem_id' => $ids,
			));
			$this->_flush();
			$this->_get();
			$res =  $this->get_results();
			if(count($res)>0)
			{
				foreach($res as $key=>$value)
				{
					$this->set_args(array(
						'_sid' => $value->id,
					));
					$this->sys_unset(true);
				}
			}
		}
		else if (is_array($ids))
		{
			foreach ($ids as $id)
			{
				$this->set_args(array(
					'_sitem_id' => $id,
				));
				$this->_flush();
				$this->_get();
				$res =  $this->get_results();
				if(count($res)>0)
				{
					foreach($res as $key=>$value)
					{
						$this->set_args(array(
							'_sid' => $value->id,
						));
						$this->sys_unset(true);
					}
				}
			}
		}
		else
		{
			// Some error, because unknown project ID
		}
		$this->pop_args();
	}


	public function _listeners()
	{
		return array(
			array('di' => 'm2_item', 'event' => 'onUnset', 'handler' => 'unset_for_item'),
		);
	}

}
?>
