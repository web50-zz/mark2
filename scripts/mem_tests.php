<?php
/*
* Реиндексация m2_url_indexer UI 15102018
* in default mode by default
* to  use make $EMULATE= false;
*/

//$EMULATE = true;
$STOP_URI_INTERPRETER = true;

include('../../../base.php');
define('DI_CALL_PREFIX', ADM_PREFIX);
echo("starts.....\r\n");
$di = data_interface::get_instance('m2_item_indexer');
echo "Memory use before query : ", memory_get_peak_usage(true)/1024, "K", PHP_EOL;
			$sql = 'SELECT t.`type_id`,t.`type_value`,t.`str_title`,t.`variable_value`,t.`order`,t.`type_value_str`,t.`m2_id` FROM m2_chars t order by `order` asc';

			$dbh = glob::get('dbh');
//			$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, FALSE);
			$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute();
//			$results = $sth->fetchALL(PDO::FETCH_ASSOC);
			while($rows = $sth->fetch(PDO::FETCH_ASSOC)) 
			{
			    $tmp[] = $rows;
			}
echo "Memory use after query : ", memory_get_peak_usage(true)/1024, "K", PHP_EOL;
echo("ended \r\n");
?>
