<?php
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	

if($_POST['new1']!=$_POST['new2'])
{
	debug("Passwords do not match", "params");
	exit;
}

if(!user_login($user['username'],$_POST['old1']))
{
	debug("Permissions denied when changing password. Did you enter the correct details?");
	exit;
}

$odata['username'] = $user['username'];
$ndata['password'] = $_POST['new2'];
if(db_record_update($tiddlyCfg['table']['user'],$odata,$ndata))
	error_log("all went well changing the password");
else
	sendHeader(304);
?>
