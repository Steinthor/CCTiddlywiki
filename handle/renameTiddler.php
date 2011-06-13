<?php
$cct_base = "../";
include_once($cct_base."includes/header.php");
if(!user_session_validate())
{
	sendHeader("401");
	exit;	
}


$tiddlyCfg['workspace_name'] = $_REQUEST['workspace'];  
debug($_SERVER['PHP_SELF'], "handle");	
$tiddler = db_tiddlers_mainSelectTitle($_REQUEST['otitle']);
$ntiddler = $tiddler; 
$ntiddler['title'] = $_REQUEST['ntitle']; 
$ntiddler['revision'] = "1";


if(@$pluginsLoader->events['preRename']) 
{
	
	foreach (@$pluginsLoader->events['preRename'] as $event)
	{
		if(is_file($event)) {
			include($event);
		}	
		
	}
}

if(user_editPrivilege(user_tiddlerPrivilegeOfUser($user,$tiddler['tags'])))
{
	if(tiddler_update_new($tiddler['id'], $ntiddler))
	{
//		error_log("sending 200");
	}
}

?>