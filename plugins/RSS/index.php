<?php
$p = new Plugin('RSS','0.1','simonmcmanus.com');

// before returning file not found check if the file exists in the uploads/tiddlers/ directory. 
$tiddler['title'] = "MarkupPostHead";
//if ($tiddlyCfg['workspace_name']!="")
//	$workspace = "/".$tiddlyCfg['workspace_name']."/";
$tiddler['body'] = "<link rel='alternate' type='application/rss+xml' title='RSS Feed for ccTiddly workspace : ".getWorkspaceName($_SERVER, $_REQUEST)."' href='".getURL()."?workspace=".getWorkspaceName($_SERVER, $_REQUEST)."&format=RSS'/>";

$p->addTiddler($tiddler);
$p->addEvent("returnNotFound", 'RSS/files/URImapping.php');
$p->addEvent("afterIncludes", 'RSS/files/URImapping.php');
?>
