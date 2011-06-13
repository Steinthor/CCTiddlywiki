<?php
if($tiddlyCfg['workspace_name']!="")
	$ws_folder = $tiddlyCfg['workspace_name']."/";
$dir = $tiddlyCfg['pref']['upload_dir']."tiddlers/".$ws_folder."";
$oldFile = $dir.$_REQUEST['otitle'].".html";
require("createHtml.php");
?>