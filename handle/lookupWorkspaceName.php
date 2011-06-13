<?php
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	
$data['name'] = $_POST['ccWorkspaceLookup'];
// if the user is checking if the username is available.
if($_POST['free']==1 )
{
	echo count(db_record_select($tiddlyCfg['table']['workspace'],$data));
	exit;
}
?> 
