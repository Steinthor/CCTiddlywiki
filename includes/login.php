<?php
$workspace_settings_count=count($workspace_settings);
if($tiddlyCfg['on_the_fly_workspace_creation']==1)
{
	if(user_session_validate())	
	{
		include_once($cct_base."includes/workspace.php");
		if ($workspace_settings_count < 1)
		{
			if ($tiddlyCfg['extract_admin_from_url']==1)
			{
				$username = $tiddlyCfg['workspace_name'];		
			}
			workspace_create($tiddlyCfg['workspace_name'], "ADDD", $username);
			$workspace_settings = db_workspace_selectSettings();
			$workspace_settings_count = count($workspace_settings);	
		}
	}
} else {
	if ($workspace_settings_count < 1)
	{   
		// workspace does not exist\
		if($pluginsLoader->events['returnNotFound'])
		{
			foreach ($pluginsLoader->events['returnNotFound'] as $event)
			{
					if(is_file($event))
					include_once($event);	
					$error404 = true;	
			}
		}	
		if(!headers_sent())
			sendHeader(404);	
		$theme = "simple";
		
	}
}

if (isset($_POST['logout']) || isset($_REQUEST['logout']))
{
	if($tiddlyCfg['pref']['base_folder']!="/")
		$base = "/";
	user_logout('You have logged out.');
	if($tiddlyCfg['use_mod_rewrite']==0)
		header('Location:'.getURL().$base.'?workspace='.$_REQUEST['workspace']);
	else if(isset($_REQUEST['workspace']))
		header('Location:'.getURL().$base.$_REQUEST['workspace']);
	else
		header('Location:'.getURL().$base);
}
///////////////////////////////CC: user variable defined in header and $user['verified'] can be used directly to check user validation
 // check to see if user is logged in or not and then assign permissions accordingly. 
//if ($user['verified'] = user_session_validate())
$user['verified'] = user_session_validate();
if ($user['verified']){
 	$workspace_permissions = user_tiddlerPrivilegeOfUser($user);
	if(in_array("admin", $user['group']))
		$workspace_permissions = "AAAA";
}else{
	$workspace_permissions = $tiddlyCfg['default_anonymous_perm'];
}

if($workspace_permissions == ""){
	$workspace_permissions = "DDDD";
}

//////////////
//  Can this use an existing function ?!?!?!

//  SET WORKSPACE CREATE PERMISSION FLAG
if (substr($workspace_permissions, 1, 1) == "U")
{
	$workspace_create = $tiddlyCfg['privilege_misc']['undefined_privilege'];
}else{

	$workspace_create = substr($workspace_permissions, 1, 1);
}
//echo $workspace_create;

//  SET WORKSPACE READ PERMISSION FLAG
if (substr($workspace_permissions, 0, 1) == "U")
{
	$workspace_read = $tiddlyCfg['privilege_misc']['undefined_privilege'];
}else{
	$workspace_read = substr($workspace_permissions, 0, 1);
}

//  SET WORKSPACE UDATE PERMISSION FLAG
if (substr($workspace_permissions, 2, 1) == "U")
{
	$workspace_udate = $tiddlyCfg['privilege_misc']['undefined_privilege'];
}else{
	$workspace_udate = substr($workspace_permissions, 2, 1);
}

//  SET WORKSPACE DELETE PERMISSION FLAG
if (substr($workspace_permissions, 3, 1) == "U")
{
	$workspace_delete = $tiddlyCfg['privilege_misc']['undefined_privilege'];
}else{
	$workspace_delete = substr($workspace_permissions, 3, 1);
}

if(@$pluginsLoader->events['postSetLoginPerm']) 
{
	foreach (@$pluginsLoader->events['postSetLoginPerm'] as $event)
	{
		if(@is_file($event)) {
			include($event);
		}	
	}
}

?>
