<?php


$data['username'] = $_POST['username'];
$data['password'] = $_POST['password'];


$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	


if ($tiddlyCfg['can_create_account'] !=1)
{
	sendHeader("403", null, null, 1);
}

// if the user is checking if the username is available.
if($_POST['free'] ==1 )
{
	debug($ccT_msg['debug']['usernameAvailable'].$data['username'], "params");
	debug($ccT_msg['debug']['countIs'].count(db_record_select($tiddlyCfg['table']['user'],$data)), "params");
	echo count(db_record_select($tiddlyCfg['table']['user'],$data));
	exit;
}


$res = db_record_insert($tiddlyCfg['table']['user'],$data);

if ($res !=1)
	sendHeader(304);
else
	user_login(formatParametersPOST($data['username']),formatParametersPOST($data['password']));

?> 
