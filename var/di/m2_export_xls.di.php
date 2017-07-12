<?php
/**
*
* @author	Fedot B Pozdnyakov 9@u9.ru 13052015	
* @package	SBIN Diesel
*/
class di_m2_export_xls extends data_interface
{
	public $title = 'm2: Маркет 2 - export import xls';

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

	protected $cfg_path = 'mark2/etc/xls_exportconfig.php';
	public	$import_di = false;
	public $path_to_storage = 'mark2/m2_import/';
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
	);
	
	public function __construct () {
		// Call Base Constructor
		$import_di = registry::get('m2_import_di');
		if($import_di)
		{
			$this->import_di = $import_di;
		}
		if(file_exists(INSTANCES_PATH.$this->cfg_path))
		{
			require_once(INSTANCES_PATH.$this->cfg_path);
			$this->export_conf = $mark2_export_conf;
		}
		parent::__construct(__CLASS__);
	}
	/**
	*	Получить путь к хранилищу файлов на файловой системе
	*/
	public function get_path_to_storage()
	{
		return FILE_STORAGE_PATH.$this->path_to_storage;
	}

	public function sys_list()
	{
		if(array_key_exists('di',$this->export_conf))
		{
			$di = data_interface::get_instance($this->export_conf['di']);
			$di->sys_list();
			die();
		}
		$di1 = data_interface::get_instance('phpexcel');
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$di = data_interface::get_instance('m2_item_indexer');
		$di->_flush();
		$di->set_order('article','ASC');
		$res = $di->_get()->get_results();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("PHPExcel Test Document")
		->setSubject("PHPExcel Test Document")
		->setDescription("Test document for PHPExcel, generated using PHP classes.")
		->setKeywords("office PHPExcel php")
		->setCategory("Test result file");
		$j = 1;
		foreach($res as $key=>$value)
		{
			$cat = json_decode($value->category_list);
			$category = $cat[0]->title;
			$man = json_decode($value->manufacturers_list);
			$manufacturer = $man[0]->title;
			$ch = json_decode($value->chars_list);
			foreach($ch  as $k=>$v)
			{
				if($v->type_id == 112)
				{
					$litr = $v->variable_value;
				}
				if($v->type_id == 113)
				{
					$sht = $v->variable_value;
				}

			}
			$p = json_decode($value->prices_list);
			foreach($p as $k=>$v)
			{
				if($v->type == 5)
				{
					$one =  $v->price_value;
				}
				if($v->type == 6)
				{
					$two =  $v->price_value;
				}
				if($v->type == 8)
				{
					$tree =  $v->price_value;
				}
				if($v->type == 9)
				{
					$four =  $v->price_value;
				}
			}
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j, $value->article)
				->setCellValue('B'.$j, $category)
				->setCellValue('C'.$j, $value->title)
				->setCellValue('D'.$j, $manufacturer)
				->setCellValue('E'.$j, $litr)
				->setCellValue('F'.$j, $sht)
				->setCellValue('G'.$j, $one)
				->setCellValue('H'.$j, $two)
				->setCellValue('I'.$j, $tree)
				->setCellValue('J'.$j, $four);
			$j++;
		}
		$objPHPExcel->setActiveSheetIndex(0);
		$callStartTime = microtime(true);
		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment;filename="price.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function sys_import_xls()
	{
		if(array_key_exists('di',$this->export_conf))
		{
			$di = data_interface::get_instance($this->export_conf['di']);
			$di->sys_import_xls();
			die();
		}

		if(!is_dir($this->get_path_to_storage()))
		{
				mkdir($this->get_path_to_storage(),0777,true);
		}
		$file = file_system::upload_file('file', $this->get_path_to_storage());
		if ($file !== false)
		{
			if($this->import_di != false)
			{
				$di =  data_interface::get_instance($this->import_di);
				$di->import_xls($this->get_path_to_storage().$file['real_name']);
			}
			else
			{
				$this->import_xls($this->get_path_to_storage().$file['real_name']);
			}
			$result = array('success' => true);
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

	public 	function import_xls($file = '')
	{
//		$file = INSTANCES_PATH.'mark2/etc/test.xlsx';
		$cat = $this->prepare_categories();
		$man = $this->prepare_manufacturers();
//		$res = $this->read_into_array($file);
		$res = $this->get_items2($file);
		if(!(count($res)>0))
		{
			return;
		}
		foreach($res as $key=>$value)
		{
			$di = data_interface::get_instance('m2_item');
			$di->_flush();
			$di->set_args(array('_sarticle'=>$value[0]));
			$res =  $di->_get()->get_results();
			if(count($res)==1)
			{
				if($res[0]->id >0)
				{
					if(array_key_exists($value[1],$cat))
					{
						$category = $cat[$value[1]];
					}
					else
					{
					}
					if(array_key_exists($value[3],$man))
					{
						$manuf = $man[$value[3]];
					}
					else
					{
					}
				}
				if($manuf>0&&$category>0 )
				{
					$di = data_interface::get_instance('m2_item');
					$di->_flush();
					$post_args = array(
						'_sid'=>$res[0]->id,
						'title'=>$value[2],
					);
					$di->set_args($post_args);
					$resi = $di->sys_set(true);
					$new_id = $resi['data']['id'];
					if($resi['success'] == true)
					{
						$di = data_interface::get_instance('m2_item_manufacturer');
						$con = $di->get_connector();
						$sql = 'delete from m2_item_manufacturer where item_id = '.$new_id;
						$con->exec($sql);
						$sql = "insert into m2_item_manufacturer (`id`,`item_id`,`manufacturer_id`) values('','".$new_id."','".$manuf."')";
						$con->exec($sql);
						$di = data_interface::get_instance('m2_item_category');
						$con = $di->get_connector();
						$sql = 'delete from m2_item_category where item_id = '.$new_id;
						$con->exec($sql);
						$sql = "insert into m2_item_category (`id`,`item_id`,`category_id`) values('','".$new_id."','".$category."')";
						$con->exec($sql);
						$di = data_interface::get_instance('m2_chars');
						$con = $di->get_connector();
						$sql = 'delete from m2_chars where m2_id = '.$new_id;
						$con->exec($sql);
						$sql = "insert into m2_chars (`id`,`m2_id`,`parent_id`,`type_value`,`type_id`,`order`,`variable_value`,`str_title`,`is_custom`,`char_type`,`type_value_str`) values
							('',".$new_id.",0,105,94,0,'','Стаус наличия товара',0,1,'В наличии'),
							('',".$new_id.",0,0,112,1,'".$value[4]."','Литраж',0,0,''),
							('',".$new_id.",0,0,113,2,'".$value[5]."','Штук в упаковке',0,0,'');";
						$con->exec($sql);
						$di = data_interface::get_instance('m2_chars');
						$con = $di->get_connector();
						$sql = 'delete from m2_item_price where item_id = '.$new_id;
						$con->exec($sql);
						$sql = "insert into m2_item_price (`id`,`item_id`,`order`,`type`,`content`,`price_value`,`currency`) values
							('',".$new_id.",0,5,'За упаковку розница от 15 до 150 упаковок','".$value[6]."',3),
							('',".$new_id.",0,6,'За штуку розница от 15 до 150 упаковок','".$value[7]."',3),
							('',".$new_id.",0,8,'За упаковку ОПТ от 150 упаковок','".$value[8]."',3),
							('',".$new_id.",0,9,'За штуку ОПТ от 150 упаковок','".$value[9]."',3);";
						$con->exec($sql);
						$di = data_interface::get_instance('m2_item');
						$di->_flush();
						$post_args = array(
							'_sid'=>$new_id,
							'title'=>$value[2],
						);
						$di->set_args($post_args);
						$resi = $di->sys_set(true);
					}
				}
			}
		}
	}

	public function prepare_manufacturers()
	{
		$di = data_interface::get_instance('m2_manufacturers');
		$di->_flush();
		$res = $di->_get()->get_results();
		foreach($res as $key=>$value)
		{
			$out[$value->title] = $value->id;
		}
		return $out;

	}
	public function prepare_categories()
	{
		$di = data_interface::get_instance('m2_category');
		$di->_flush();
		$res = $di->_get()->get_results();
		foreach($res as $key=>$value)
		{
			$out[$value->title] = $value->id;
		}
		return $out;
	}
	public function read_into_array($file)
	{
			$di1 = data_interface::get_instance('phpexcel');
			$inputFileType = PHPExcel_IOFactory::identify($file);
			if($inputFileType == 'CSV')
			{
			}
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($file);
			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			$result = array();
			foreach ($objWorksheet->getRowIterator() as $row) 
			{
				
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
				$result[] = array();
				foreach ($cellIterator as $cell) 
				{
					array_push($result[count($result) - 1],$cell->getValue());
				}
			}
		return $result;
	}

	function get_items2($file)
	{
			$di1 = data_interface::get_instance('phpexcel');
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($file);
			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			$result = array();
			foreach($objWorksheet -> toArray(null,true,true) as $row_n => $row){
					$r =  gettype($row[4]);
					if($row[4] == '0.330')
					{
						$row[4] = '0.33';
					}
					else if($row[4] == '0.473')
					{
						$row[4] = '0.473';
					}
					else if($row[4] == '0.350')
					{
						$row[4] = '0.35';
					}
					else if($row[4] == '0.59999999999999998')
					{
						$row[4] = '0.6';
					}
					else
					{
						$row[4] = $row[4] + 0;
					}
					$result[] = $row;
			}
		return $result;
	}

}
?>
