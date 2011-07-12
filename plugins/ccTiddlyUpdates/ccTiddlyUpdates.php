<?php
$cct_base = "../../";
include_once($cct_base."includes/header.php");
if(!user_session_validate())
{
	debug("failed to validate session.", "save");
	sendHeader("401");
	exit;	
}
global $tiddlyCfg;
$dbhost = $tiddlyCfg['db']['host'];
$dbuser = $tiddlyCfg['db']['login'];
$dbpass = $tiddlyCfg['db']['pass'];
$dbname = $tiddlyCfg['db']['name'];
//error_log("host is:".$dbhost." db user is: ".$dbuser." dbpass is: ".$dbpass." dbname is: ".$dbname);
$display_num = 5;

error_reporting(E_ALL);  
$dbconn = mysql_connect($dbhost,$dbuser,$dbpass);  
mysql_select_db($dbname,$dbconn);
 foreach($_POST as $key => $value)  
   $$key = mysql_real_escape_string($value, $dbconn);
   $time = $time-1;
 
   $result = mysql_query("SELECT title,body,fields,tags,modifier,modified,revision
						 FROM tiddler
						 WHERE modified>$time
						 ORDER BY id DESC",$dbconn);
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    die($message);
}
 
if(mysql_num_rows($result) == 0) $status_code = 2;  
else $status_code = 1;

echo "<?xml version=\"1.0\"?>\n";
echo "<response>\n";
echo "\t<status>$status_code</status>\n";
echo "\t<time>".time()."</time>\n";

if($status_code == 1)
{
	while($message = mysql_fetch_array($result))
	{
		$message['body'] = htmlspecialchars($message['body']);
		echo "\t<message>\n";
		echo "\t\t<modifier>$message[modifier]</modifier>\n";
		echo "\t\t<title>$message[title]</title>\n";
		echo "\t\t<content>$message[body]</content>\n";
		echo "\t\t<fields>$message[fields]server.page.revision='$message[revision]' </fields>\n";
		echo "\t\t<tags>$message[tags]</tags>\n";
		echo "\t\t<modified>$message[modified]</modified>\n";
		echo "\t\t<revision>$message[revision]</revision>\n";
		echo "\t</message>\n";
	}
}
echo "</response>";


?>