<?php

$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	

$tiddler = db_tiddlers_mainSelectTitle($_REQUEST['title']);
if( $tiddler === FALSE ) {//not found
	sendHeader(204);
}
$tiddlyCfg['workspace_name'] = $_REQUEST['workspace'];
$tiddler = db_tiddlers_mainSelectTitle($title);
//use tiddler_id to obtain list of tiddler for revision
$t = db_tiddlers_backupSelectOid($tiddler['id']);
error_log(print_r($tiddler, true));

if( user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$tiddler['tags'])) ) { //if read privilege ok, output
	@sendHeader(200,"", tiddler_toJson($tiddler), 1);
}else{ //if no read privilege, stop
	sendHeader(204);
}
?>