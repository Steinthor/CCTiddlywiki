<?php

include_once($cct_base."includes/header.php");
//force anonymous due to security concerns
$user['verified']=0;
$tag = ""; // if a tag is specified the RSS feed will only return tiddler with that tag. 

if($tiddlyCfg['workspace_name'] == "index.xml")
	$tiddlyCfg['workspace_name'] = '';


$data = db_tiddlers_mainSelectSiteConfig();

$tmp=array();
while( $d=db_fetch_assoc($data) ) {
	$tmp[$d['title']] = $d;
}
$data = $tmp;
//$siteUrl = isset($data['SiteUrl'])?htmlspecialchars($data['SiteUrl']['body']):"http://www.tiddlywiki.com/";
$siteUrl = getUrl().$tiddlyCfg['workspace_name'];
$result = '<?xml version="1.0"?><rss version="2.0"><channel>
<title>'.(isset($data['SiteTitle'])?htmlspecialchars($data['SiteTitle']['body']):"My TiddlyWiki").'</title>
<link>'.$siteUrl.'</link>
<description>'.(isset($data['SiteSubtitle'])?htmlspecialchars($data['SiteSubtitle']['body']):"a reusable non-linear personal web notebook").'</description>
<pubDate>'.gmdate("D, j M Y h:i:s e").'</pubDate>
<lastBuildDate>'.gmdate("D, j M Y h:i:s e").'</lastBuildDate>
<generator>ccTiddly '.$tiddlyCfg['version'].'</generator>';

//get required data from database
 $data = db_tiddlers_mainSelect4RSS($tag);
$count=0;
while( $d=db_fetch_assoc($data) ) 
{
	//check privilege
	if( user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$d['tags'])) )
	{
		$result .= '<item>
		<title>'.htmlspecialchars($d['title']).'</title>
		<description>'.htmlspecialchars($d['body']).'</description>';
		$tags = tiddler_breakTag($d['tags']);
		foreach( $tags as $t ) {
			$result .= '
			<category>'.$t.'</category>';
		}
		$result .= '
		<link>'.$siteUrl.'#'.htmlspecialchars($d['title']).'</link>
		<pubDate>'.gmdate("D, j M Y h:i:s",TiddlyTimeToEpoch($d['modified'])).'</pubDate>
		</item>';
		$count++;
	}
}
$result .= '</channel>
</rss>';

sendHeader(200);
echo $result;
//print str_replace("\n","<br>\n",htmlspecialchars($result));

exit;
?>
