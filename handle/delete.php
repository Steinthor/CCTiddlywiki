<?php



$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	
$tiddlyCfg['workspace_name']  = $_POST['workspace'];
//$tiddlyCfg['workspace_name']  = ;

//return result/message
$title = formatParametersPOST($_POST['title']);

//check for markup
if( !tiddler_markupCheck($user,$title) )
{
	sendHeader(401);
}
//get tiddler to check for privilege
$tiddler = db_tiddlers_mainSelectTitle($title);
if( $tiddler===FALSE ) {
//	sendHeader(404);
}
if(user_deletePrivilege(user_tiddlerPrivilegeOfUser($user,$tiddler['tags']))) {
	tiddler_delete_new($tiddler['id'], $tiddlyCfg['workspace_name']);		//delete current tiddler
	sendHeader(200);
}else{
	sendHeader(401);
}
print_r($_POST);
echo "gello";
?>
