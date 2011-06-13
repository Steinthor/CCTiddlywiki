<?php

// This code is not being used. 
exit;

$data['username'] = $_REQUEST['username'];
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	

$results = db_record_select('user', $data);			// get array of results		
if (count($results) == 1)                   //  if the array has 1 or more acounts 
{
	if ($results[0]['mail']) {
		echo $results[0]['mail'];
		mail($results[0]['mail'], "text", true);
	} else {
		echo "User does not have an email address stored.";
	}
}

// check mail functionaility is enabled, provide nice notification on install if the mail functionality is not present. 
// SQL to install mail field in user table : ALTER TABLE `user` ADD `mail` VARCHAR( 800 ) NOT NULL ;

?>