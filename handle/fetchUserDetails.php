<?php
//TODO - config option to allow configuring permissions

$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	

$data['username'] = $_REQUEST['user'];
$res =  db_record_select($tiddlyCfg['table']['user'],$data);

foreach( $res as $r) {
echo $r['username'];
echo "<br />";
echo $r['short_name'];
}
?> 
