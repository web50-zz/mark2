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
$di = data_interface::get_instance('m2_url_indexer');
$res = $di->reindex(true);
echo("ended\r\n");


?>
