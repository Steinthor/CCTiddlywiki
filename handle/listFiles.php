<?php
if(isset($_POST['username']))
	$u = $_POST['username'];
if(isset($_REQUEST['workspace']))
	$w = $_REQUEST['workspace'];
if(isset($_POST['action']))
	$a = $_POST['action'];

$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	
if(!user_session_validate())
{
	sendHeader("403");
	exit;	
}
if (!user_isAdmin($user['username'], $w))
{
	if ($tiddlyCfg['only_workspace_admin_can_upload']==1)
	{
		sendHeader("401");
		exit;
	}
}
if($w=="")
	$w='default';
if(isset($a))
{
	if($a=="DELETEFILE")
	{
		echo unlink($_SERVER['DOCUMENT_ROOT'].dirname(dirname($_SERVER['SCRIPT_NAME']))."/uploads/".$w."/".$_POST['file']);
		exit;
	}
}

$folder =  $_SERVER['DOCUMENT_ROOT'].dirname(dirname($_SERVER['SCRIPT_NAME']))."/uploads/".$w;
if(!is_dir($folder))
{
	if(!is_dir($_SERVER['DOCUMENT_ROOT'].dirname(dirname($_SERVER['SCRIPT_NAME']))."/uploads/"))
		mkdir($_SERVER['DOCUMENT_ROOT'].dirname(dirname($_SERVER['SCRIPT_NAME']))."/uploads/");
	mkdir($folder);
}

if ($handle = opendir($folder)) {
	$out = "";
	echo "[";    
	while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
			$loc = $folder."/".$file;
			$file_size = array_reduce (array (" B", " KB", " MB"), create_function ('$a,$b', 'return is_numeric($a)?($a>=1024?$a/1024:number_format($a,2).$b):$a;'), filesize ($loc));
			$out .= "{'filename':'".htmlentities($file)."','fileSize':'".$file_size."', 'url':'".str_replace(" ", "%20", dirname(getUrl())."/uploads/".$w."/".mb_convert_encoding($file, 'UTF-8', 'HTML-ENTITIES'))."'},";
		}
	}
	if(isset($out))
		echo substr_replace($out ,"",-1)."]";
}
?>