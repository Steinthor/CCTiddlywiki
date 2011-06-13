<?php
error_log("save request has been saved");
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	

if(!user_session_validate())
{
	debug("failed to validate session.", "save");
	sendHeader("401");
	exit;	
}
$tiddlyCfg['workspace_name'] = formatParametersPOST($_POST['workspace']);
$tiddler = db_tiddlers_mainSelectId($_POST['id']);

if($_POST['id']!="undefined")  // if the tiddler is being renamed we need ensure the id is correct. 
	$tiddler['id'] = $_POST['id']; 

$otiddler['revision'] = formatParametersPOST($_POST['revision']);

$ntiddler['title'] = formatParametersPOST($_POST['title']);
$ntiddler['modifier'] = formatParametersPOST($_POST['modifier']);
$ntiddler['modified'] = formatParametersPOST($_POST['modified']);
$ntiddler['created'] = formatParametersPOST($_POST['created']); 
$ntiddler['tags'] = formatParametersPOST($_POST['tags']);
$ntiddler['body'] =  formatParametersPOST($_POST['body']);
$ntiddler['fields'] = formatParametersPOST($_POST['fields']);



// Plugin preSave Event.
if(@$pluginsLoader->events['preSave']) {
	foreach (@$pluginsLoader->events['preSave'] as $event) {
		if(is_file($event)) 
			include($event);
	}
}				


// does the tiddler already exists?
if(db_tiddlers_mainSelectTitle($ntiddler['title']) || is_numeric($_POST['id']))
{	
	
	if($tiddler['revision'] !== $_POST['revision']) {		//ask to reload if the tiddler has been edited it was last downloaded
		debug($ccT_msg['debug']['reloadRequired'], "save");
		sendHeader(409);
		exit;
	}

	if($ntiddler['title']!=$tiddler['title']) // The Tiddler is being renamed.THIS MAY BE REDUNDANT. 
	{		
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
			
				$ntiddler['creator'] = $otiddler['creator'];
				$ntiddler['created'] = $otiddler['created'];
				$ntiddler['revision'] = $otiddler['revision']+1;
				debug("Attempting to update server for tiddler...");
			if(tiddler_update_new($tiddler['id'], $ntiddler))
			{
				sendHeader(201);
			}
		}
		exit;

	}
// end rename


		//require edit privilege on new and old tags			
	if(user_editPrivilege(user_tiddlerPrivilegeOfUser($user,$ntiddler['tags'])) && user_editPrivilege(user_tiddlerPrivilegeOfUser($user,$otiddler['tags'])))
	{
		$ntiddler['creator'] = $otiddler['creator'];
		$ntiddler['created'] = $otiddler['created'];
		$ntiddler['revision'] = $otiddler['revision']+1;
		debug("Attempting to update server for tiddler...");
		unset($ntiddler['workspace_name']); 	// hack to remove the workspace being set twice. 
		if(tiddler_update_new($tiddler['id'], $ntiddler)) {
			sendHeader(201);
		}
	}else{
		sendHeader(400);	
		debug("Permissions denied to save.", "save");
	}
}else
{	//This Tiddler does not exist in the database.
	if( user_insertPrivilege(user_tiddlerPrivilegeOfUser($user,$ntiddler['tags'])) ) 
	{
		debug("Inserting New Tiddler...", "save");
		$ntiddler['creator'] = $ntiddler['modifier'];
		$ntiddler['created'] = $ntiddler['modified'];
		$ntiddler['revision'] = 1;
		unset($ntiddler['workspace_name']); 	// hack to remove the workspace being set twice. 
			if($id = tiddler_insert_new($ntiddler))
			{
				sendHeader(201);
				echo $id;
			}
	}else
	{
		debug("Permissions denied to save.", "save");
		sendHeader(400);
	}
}

?>
