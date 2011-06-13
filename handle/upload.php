<?php 
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	
include_once($cct_base."includes/uploads.php");
if(!user_session_validate())
{
	sendHeader("403");
	exit;	
}


if (!user_isAdmin($user['username'], $tiddlyCfg['workspace_name'])){
	if ($tiddlyCfg['only_workspace_admin_can_upload']==1)
	{
		sendHeader("401");
		exit;
	}
}


if ($tiddlyCfg['workspace_name'] == "")
	$w = "default";
else
	$w = $tiddlyCfg['workspace_name'];
$folder = $tiddlyCfg['pref']['upload_dir']."workspace/".$w;


if(!file_exists($folder))
{
	mkdir($folder, 0777, true);
}

$err = ""; 
$status = 0;
if (isset($_FILES["userFile"]))
{
	
	if (check_vals())
	{
		if (($_FILES["userFile"]["type"] == "image/gif") || ($_FILES["userFile"]["type"] == "image/jpeg")|| ($_FILES["userFile"]["type"] == "image/pjpeg"))
		{
			$file_type = 'image';
		} else if(in_array($_FILES["userFile"]["type"], $tiddlyCfg['upload_allow_extensions'])) {
			$file_type = 'text';
		} else {
			echo '<b>'.$ccT_msg['upload']['typeNotSupported'].'</b>';
			exit;
		}
		$upload_dir = $folder;
		if(filesize($_FILES["userFile"]["tmp_name"]) > $tiddlyCfg['max_file_size'])
		{
			sendHeader("400");
			$err .= $ccT_msg['upload']['maxFileSize'];
		} else {
			$from =  $_FILES["userFile"]["tmp_name"];
			$to = $folder."/".$_FILES["userFile"]["name"];
			if(file_exists($to)) 
			{
				echo '<b>'.$ccT_msg['upload']['fileExists'].'</b>';
				exit;
			}
			if (move_uploaded_file($from, $to))
				$status = 1;
			else
				$err .= $ccT_msg['upload']['unknownError'];
				
		}
	}
}
if (!$status) {
	if (strlen($err) > 0)
	echo "<h4>$err</h4>";
} else {
	$url = dirname(getUrl())."/uploads/workspace/".$w."/".$_FILES["userFile"]["name"];
	if($file_type == 'image') 
	{
		$output .= '<h2>'.$ccT_msg['upload']['uploadedTitle'].'</h2> ';
		$output .= "<a href='".$url."'><img src='".$url."' height=100 /></a><p>".$ccT_msg['upload']['includeCode']."</p><form name='tiddlyCode' ><input type=text name='code' id='code' onclick='this.focus();this.select();' cols=90 rows=1 value='[img[".$url."][EmbeddedImages]]' /></form>";
	} else {		
		$output .= "<a href='".$url."'/>".$url."</a>";	
	}
	echo $output;
}

?>