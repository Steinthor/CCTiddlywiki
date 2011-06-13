<?php
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	
if(!user_session_validate())
	sendHeader("403", null, null, 1);
if (!user_isAdmin($user['username'], $_POST['ccCreateWorkspace']))
	sendHeader("401", null, null, 1);
$odata['name']= $_POST['ccCreateWorkspace'];
if(isset($_REQUEST['ccAnonPerm']) && $_REQUEST['ccAnonPerm'] != "")
	$ndata['default_anonymous_perm']=$_REQUEST['ccAnonPerm']; 
if(isset($_REQUEST['ccUserPerm']) && $_REQUEST['ccUserPerm'] != "")
	$ndata['default_user_perm']=$_REQUEST['ccUserPerm'];
$res = db_record_update($tiddlyCfg['table']['workspace'],$odata,$ndata);
if($res<0)
	sendHeader(304);

?>