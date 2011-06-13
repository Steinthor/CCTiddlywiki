<?php
$cct_base = "../";
include($cct_base."includes/header.php");
$feed = $_REQUEST['feed'];
$url = parse_url($feed);

// security checks 

if(!in_array($url['host'], $tiddlyCfg['allowed_proxy_list']))
{
	exit;
}
$url = $feed;


// Attempt different proxy methods. 

if(curl_version())
{
	
	$ch = curl_init($_REQUEST['feed']);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10); /* Max redirection to follow */
	curl_exec($ch);
	 $info = curl_getinfo($ch);
	//print_r($info);
	// HANDLE 301 redirects
	if($info['http_code']==301)
	{
		echo $info['url'];
	}
	curl_close($ch);
	exit;
	
} elseif(readfile()) 
{
	readfile($feed);
} else 
{
	$params = array('http' => array(
	'method' => 'GET',
	'header'=> 'accept:application/json',
	'content' => $data));
	$ctx = stream_context_create($params);
	$fp = fopen($url, 'rb', false, $ctx);
	echo $response = stream_get_contents($fp);
	
}


// in some situtations this needs to replace the above line. 
//echo $response = readExternalFile($feed);


?>