<?php
/**
*
* @author	9@u9.ru 16012014	
* @package	SBIN Diesel
*/
class di_m2_item_indexer extends di_index_processor
{
	public $title = 'm2: Item Indexer';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	public $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	public $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	public $name = 'm2_item_indexer';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'last_changed' => array('type'=>'datetime'),
		'item_id' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'article' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'not_available' => array('type' => 'integer'),
		'category_list'=> array('type'=>'string'),
		'files_list'=>array('type'=>'string'),
		'text_list'=>array('type'=>'string'),
		'prices_list'=>array('type'=>'string'),
		'manufacturers_list'=>array('type'=>'string'),
		'chars_list'=>array('type'=>'string'),
		'order' => array('type'=>'integer'),

	);

	public $settings = array(
		'index_target'=>array(
			'di_name'=>'m2_item',//9* di которыйиндексируем
			'fields_to_index'=>array(
					'id'=>array(
						'alias'=>'item_id'
					),
					'article'=>'',
					'title'=>'',
					'name'=>'',
					'not_available'=>'',
				)

			),
			'composite_fields'=>array(
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'files_list',
					'di_name'=>'m2_item_files',
					'di_key'=>'item_id',
					'fields'=>array(
						'real_name'=>'',
						'file_type'=>'',
						'type'=>''
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'text_list',
					'di_name'=>'m2_item_text',
					'di_key'=>'item_id',
					'fields'=>array(
						'title'=>'',
						'content'=>'',
						'type'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'prices_list',
					'di_name'=>'m2_item_price',
					'di_key'=>'item_id',
					'fields'=>array(
						'type'=>'',
						'price_value'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'manufacturers_list',
					'di_name'=>'m2_item_manufacturer',
					'di_key'=>'item_id',
					'fields'=>array(
						'manufacturer_id'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'chars_list',
					'di_name'=>'m2_chars',
					'di_key'=>'m2_id',
					'fields'=>array(
						'type_id'=>'',
						'str_title'=>'',
						'variable_value'=>'',
						'order'=>'',
						'type_value_str'=>'',
						)
					),
					array(
					'type'=>'records_by_key',
					'index_field_name'=>'category_list',
					'di_name'=>'m2_item_category',
					'di_key'=>'item_id',
					'fields'=>array(
						'category_id'=>'',
						),
					'joins'=>array(
						'm2_category'=>array(
								'source_key'=>'category_id',
								'remote_key'=>'id',
								'fields'=>array(
									'title'=>'title'
								)
							)
						)
					),
			)	
	);



	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

}
?>
