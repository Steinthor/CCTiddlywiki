<?php

$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	


$title = formatParametersGET($_REQUEST['title']);
$revision = formatParametersGET($_REQUEST['revision']);

if(!isset($_GET['title']))
	sendHeader(400);

if(!isset($_GET['revision']))
	sendHeader(204);


//get tiddler with certain title to obtain its tiddler_id
$tiddler = db_tiddlers_mainSelectTitle($title);

if($tiddler === FALSE) //not found
	sendHeader(204);

//use tiddler_id to obtain list of tiddler for revision
$tiddler_list = db_tiddlers_backupSelectOid($tiddler['id']);

//find revision
foreach( $tiddler_list as $t ) {
	if( $revision == $t['revision'] ) {		//if revision equals, check privilege
		if( user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$t['tags'])) ) {	//if read privilege ok, output		
			echo '{"created":"'.$t['created'].'", "text":"'.tiddler_bodyEncode($t['body']).'", "tags":"'.$t['tags'].'", "modified":"'.$t['modified'].'", "modifier":"'.$t['modifier'].'", "revision":'.$t['revision'].'}';
		}else{		//if no read privilege, stop
			sendHeader(204);
		}
	}
}
?>
