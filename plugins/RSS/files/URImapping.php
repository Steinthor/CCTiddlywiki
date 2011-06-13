<?php


	$tiddlyCfg['workspace_name'] = str_replace("/index.xml", "", $tiddlyCfg['workspace_name']);
	if($_GET['format']=="RSS" || stristr($_SERVER['REQUEST_URI'], ".xml"))
	{
		$cct_base = "";
		include("plugins/RSS/files/rss.php");
	}


?>
