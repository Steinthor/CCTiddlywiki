<?php


/*
// SHOULD BE RUN FROM THE CCTIDDLY ROOT DIRECTORY!!


When run this file fetches all the files required by the plugins in the plugins directory.

This file should be run prior to a release. 

The entire TiddlyWiki SVN repository should be checked out NOT just the ccTiddly directory to ensure relative links work.

WARNING: This code is UNTESTED.

*/

$cct_base = '';
$tiddlyCfg['plugins_disabled'] =  array();

include_once('includes/functions.php');
include_once('includes/tiddler.php');
include_once('includes/pluginsClass.php');
include_once('includes/pluginsLoaderClass.php');

class PluginsLoaderReplace extends PluginsLoader
{
	public function includePlugins($cct_base) {
		// add one param to the beginning of PluginFetcher call and eval the new code.
		$plugins = $this->readPlugins($cct_base);
		foreach($plugins as $plugin)
		{
			$pluginPathArray = explode("/", $plugin);
			$pluginContent = file_get_contents($plugin);
			$newPluginContent  = str_replace("<?php", "", $pluginContent);
		 	$newPluginContent = str_replace('new Plugin(', 'new PluginFetcher("'.$pluginPathArray[1].'",', $newPluginContent);
			eval($newPluginContent);

		}
	}
}

class PluginFetcher extends Plugin
{
	public function __construct($pluginName, $author, $version, $website) {
		global $Plugins;
		$this->pluginName = $pluginName;
		$this->author = $author;
		$this->version = $version;
		$this->website = $website;
		$this->phpEvents = array();
		$this->tiddlers = array();
		array_push($Plugins,$this);
	}
	
	public function createTidFile($path, $tiddler)
	{	
		@mkdir(dirname($path));
		$fhandle = fopen($path, 'w') or die("can't open file");
		fwrite($fhandle, "created:".$tiddler['created']."\n");
		fwrite($fhandle, "modified:".$tiddler['modified']."\n");
		fwrite($fhandle, "tags:".$tiddler['tags']."\n");
		fwrite($fhandle, "modifier:".$tiddler['modifier']."\n");
		fwrite($fhandle, "\n".$tiddler['body']);
		fclose($fhandle);
	}
	
	public function createTiddler($data, $path=null) {
		var_dump($data);
		if(is_file($path))
			$tiddler = $this->tiddlerFromFile($path);
		else 
			$tiddler = array();
		if(is_array($data)) 
			$tiddler = array_merge_recursive($data,$tiddler);
		if(substr($tiddler['title'], 0, 3)!="js:" && substr($tiddler['title'], 0, 13)!="jsdeprecated:" &&  substr($tiddler['title'], 0, 7)!="jquery:" &&  substr($tiddler['title'], 0, 6)!="jslib:"&&  substr($tiddler['title'], 0, 8)!="version:"){
			//	echo "importing tiddler: ".$tiddler['title']."\n<br/>";
			$this->tiddlers[$tiddler['title']] = $tiddler;
 			$filePath = getcwd().'/plugins/'.$this->pluginName.'/files/importedPlugins/'.$tiddler['title'].'.tid';
			$this->createTidFile($filePath, $tiddler);
		}
	}
	
	public function addRecipe($path) 
	{
		echo $path;
			
		if(is_file($path))
		{
			$file = $this->getContentFromFile($this->preparePath($path));
			$this->parseRecipe($file, dirname($path));	
		} else {	
			// look for the imported folder
			$importedPluginsPath = getcwd()."/plugins/".$this->title."/files/importedPlugins";
			$this->addTiddlersFolder($importedPluginsPath);
		}
	}
	public function parseRecipeLine($line, $recipePath) {
		$semi_colon_pos = stripos($line, ":");
		$linePath = trim(substr($line, $semi_colon_pos+1));
		$realPath = realpath($recipePath."/".$linePath);
		$ext = trim(end(explode(".", $line)));
		switch ($ext) {
			case 'recipe':
				$path = $recipePath.'/'.str_replace('recipe: ', '', $line);
				$this->addRecipe($path);
			break;
			case 'js' :
				if($realPath && !stristr(getcwd(), $realPath) ) {
					$tiddler['title'] = substr(basename(str_replace('tiddler: ', '', $line)), 0, -strlen($ext)-1);
					$tiddler['tags'] = 'systemConfig';
					$tiddler['body'] = $this->getContentFromFile(str_replace('tiddler: ', '', $recipePath.'/'.$line));
					$this->createTiddler($tiddler);		
				}
			break;
			case 'tid' :
				if($realPath!=null && !stristr(getcwd(), $realPath)){
					$this->createTiddler($this->tiddlerFromFile($this->preparePath(str_replace('tiddler: ', '', $recipePath.'/'.$line))));	
				}
			break;
			default: 
			break;
			
		}			
	}
}

$pluginsLoader = new PluginsLoaderReplace();
$pluginsLoader->includePlugins($cct_base);

?>