<?php
$ws_folder = $tiddlyCfg['workspace_name'];
$dir = $tiddlyCfg['pref']['upload_dir']."tiddlers/".$ws_folder."";
@mkdir($dir, 0777, true);
$myFile = $dir."/".$ntiddler['title'].".html";
$fh = @fopen($myFile, 'w+');
$doc = "<html>\r\n<head>\r\n<script language='javascript'><!-- \r\nlocation.replace('".dirname(getUrl())."/".$tiddlyCfg['workspace_name']."#".$ntiddler['title']."') \r\n //--></script>\r\n";
$doc .= "<title>".$ntiddler['title']."</title>\r\n</head>\r\n<body>\r\n";
$doc .= "<h1>".$ntiddler['title']."</h1>\r\n";
$doc .= str_replace(dirname(getUrl())."/".$tiddlyCfg['workspace_name']."#", dirname(getUrl())."/".$tiddlyCfg['workspace_name']."/", formatParametersPOST($_POST['wikifiedBody']));
$doc .= "<br /><i>Modified : ".$ntiddler['modified']." by ".$ntiddler['modifier']."</i><br /><br />";
$doc .= "\r\n<h3>Fields</h3>\r\n<ul>\r\n";
$fields = split(" ", $ntiddler['fields']);
foreach($fields as $field)
{
	if($field)
	{
		$f = split("=", $field);
		$doc .="<dt>".$f[0]."</dt><dd>".$f[1]."</dd>\r\n";
	}
}
$doc .= "</ul>\r\n<h3>Tags</h3>\r\n<ul>\r\n";
$tags = split(" ", $ntiddler['tags']);
foreach($tags as $tag)
	if($tag)
		$doc .="<a href='#' rel='tag'>".$tag."</a>\r\n";
$doc .= "</ul>\r\n</body>\r\n</html>";
if(@fwrite($fh, $doc)){
}
@fclose($fh);
?>
