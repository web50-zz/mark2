<?php
/**
*  Лобовые операции xlsx 25/09/2018
*
* @author 9* 9@u9.ru	
*/
class simple_xlsx
{

	public static function read_into_array($zipFile = '')
	{

		$alphas = range('A', 'Z');
		foreach($alphas as $k=>$v)
		{
			$alphas_assoc[$v] = $k;
		}
		try{
			if($zipFile == ''){
				throw new Exception('input file required');
			}
			if(!is_file($zipFile))
			{
				throw new Exception('input file problems');
			}
			$fileInZip = 'xl/sharedStrings.xml';         
			$fileInSheetZip = 'xl/worksheets/sheet1.xml';         
			$path = sprintf('zip://%s#%s', $zipFile, $fileInZip);
			$sharedData = file_get_contents($path);
			$path = sprintf('zip://%s#%s', $zipFile, $fileInSheetZip);
			$sheetData = file_get_contents($path);

			$xml1 = simplexml_load_string($sharedData);
			$xml = simplexml_load_string($sheetData);
			$sharedStringsArr = array();
			foreach ($xml1->children() as $item) 
			{
				$sharedStringsArr[] = trim((string)$item->t);
			}
			$out = array();
			$file= 1;
			$row = 0;
			foreach ($xml->sheetData->row as $item) {
				$out[$file][$row] = array();
				$cell = 0;
				$i_attr = $item->attributes();
				$row_num = $i_attr->r;
				$row_position = 0;
				foreach ($item as $child) {
					$attr = $child->attributes();
					$value = isset($child->v)? (string)$child->v:false;
					if(isset($attr->r))
					{
						$cell_num = str_replace($row_num,'',$attr->r);
						$row_position = $alphas_assoc[$cell_num];
					}
					if(isset($attr['t']))
					{
						if(array_key_exists($value,$sharedStringsArr))
						{
							$out[$file][$row][$row_position] = $sharedStringsArr[trim($value)];
						}
					}
					else
					{
						//фикс для косяка экслевского хранения десятинчых в формате с разделителем запятой и получения цифр типа 0.23499999990 из 0.235
						if(fmod($value, 1) !== 0.00 && strlen($value)>5)
						{
							// Тупо проверяем если десятичное и длина больше 3 после запятой округляем до 3го знака
							$value = round($value,3);
						}
						$out[$file][$row][$row_position] = trim($value);
					}
					$cell++;
				}
				$row++;
			}
			return $out[1];
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
}
?>
