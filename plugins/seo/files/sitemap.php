<?php
$output = "<?xml version='1.0' encoding='UTF-8'?>";
$output.= "<urlset xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd' xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
$start_dirname = getcwd()."/uploads/tiddlers/";
function recurse($dirname) 
{
	global $start_dirname, $output;
	$dir = dir($dirname);
	while (false !== $entry = $dir->read()) 
	{
		if(is_dir($dirname.$entry) && $entry !==".")
			recurse($dirname.$entry);
		if(stristr($entry, ".html"))
		{
			$uri_dir = str_replace($start_dirname, "", $dirname);
			
			if($uri_dir)
				$uri_dir = "/".$uri_dir;
			$output .= "<url><loc>http://".$_SERVER["SERVER_NAME"].$uri_dir."/".$entry."</loc></url>";

		}
	}
	$dir->close();		
}
recurse($start_dirname);
$output .= "</urlset>";
echo utf8_encode($output);
?>		
