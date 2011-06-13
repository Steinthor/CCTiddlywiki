<?php
// TODO - decide how we want to use this code with lazy loading. 
//This server side search has been implemented so the search functionality still exists when the entire Tiddlywiki content is not downloaded.

//  This code is not currently  being used by the core

// NOT BEING USED :::

$cct_base = "../";
include_once($cct_base."includes/header.php");

$search = $_REQUEST['search'];
$search = $_POST['search'];

$tiddlers = getAllTiddlers('simon', $search);

if(!$tiddlers)
{
	echo "{no results found}";
	exit;
}
$count = 1;
$str=  "{";
foreach($tiddlers as $t)
{
	$str .= "'".$count++."':'";
	$str .= $t['title']."',";
}
echo $str = substr($str,0,	(strlen($str)-1));		//remove last ","
print "}";
?>