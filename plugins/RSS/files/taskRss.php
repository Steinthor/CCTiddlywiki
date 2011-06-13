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
$result = '<?xml version="1.0"?><rss version="2.0"  xmlns:atom="http://www.w3.org/2005/Atom"><channel>
<atom:link href="http://simonmcmanus.com/TiddlyDocsDemo/index.xml?user=josh" type="application/rss+xml" />
<title>'.(isset($data['SiteTitle'])?htmlspecialchars($data['SiteTitle']['body']):"TiddlyDocs").'</title>
<link>'.$siteUrl.'</link>
<description>'.(isset($data['SiteSubtitle'])?htmlspecialchars($data['SiteSubtitle']['body']):"a list of the sections assigned to you.").'</description>
<pubDate>Wed, 02 Oct 2002 13:00:00 GMT</pubDate>
<lastBuildDate>Wed, 02 Oct 2002 13:00:00 GMT</lastBuildDate>
<generator>ccTiddly '.$tiddlyCfg['version'].'</generator>';

//get required data from database
$data = db_tiddlers_mainSelect4RSS($tag);
$count=0;
while( $d=db_fetch_assoc($data) ) 
{
	//check privilege
	if( user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$d['tags'])) )
	{
		if(!stristr($d['fields'], "tt_status='Complete") && stristr($d['fields'], "tt_user='".$_REQUEST['user']."'"))
		{
			$result .= '<item>
			<title>'.htmlspecialchars($d['title']).'</title>';
			$tags = tiddler_breakTag($d['tags']);
			foreach( $tags as $t ) {
				$result .= '
				<category>'.$t.'</category>';
			}
			$result .= '
			<link>'.$siteUrl.'#'.htmlspecialchars(urlencode("[[".$d['title']."]]")).'</link>
			<pubDate>Wed, 02 Oct 2002 13:00:00 GMT</pubDate>
			</item>';
			$count++;
		}
	}
}
$result .= '</channel>
</rss>';
echo $result;

//print str_replace("\n","<br>\n",htmlspecialchars($result));

exit;
?>
