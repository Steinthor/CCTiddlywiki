<?php

/*
PLUGINS LOADER 


*/


class PluginsLoader {
	public $events;
	public $tiddlers;
	
	public function __construct(){
		$this->events = array();
		$this->tiddlers = array();
	}	
	
	public function addEvent($eventname,$event){
		if ( !isset($this->events[$eventname]))
             $this->events[$eventname] = array();
			array_push($this->events[$eventname], $event); 
	}
	
	public function addTiddler($tiddler){
		$this->tiddlers[$tiddler['title']] = $tiddler;
	}
	
	public function readPlugins($cct_base){
		$dir = $cct_base."plugins/";
		include_once("plugins.php");
		if (is_dir($dir)) {		// Open a known directory, and proceed to read its contents
		    if ($dh = opendir($dir)) {
		       while (($file = readdir($dh)) !== false) {
					if( is_dir($dir.$file) && !in_array($file, $tiddlyCfg['plugins_disabled']))
					{
					 	$pluginPath = $dir.$file."/index.php";
						if (is_file($pluginPath) && $file!=='..')
						{	// check for index.php and remove the ..
							 $plugins[] = $pluginPath;
						}
					}
		    	}
		        closedir($dh);
				return $plugins;
		    }
		}
	}
	
	public function includePlugins($cct_base) {
		$plugins = $this->readPlugins($cct_base);
		foreach($plugins as $plugin)
		{
			include($plugin);			
		}
	}
	
	
	public function runPlugins(){
		global $Plugins;
		foreach ($Plugins as $plugin)
		{
			$plugin->run();
		}		
	}
}

?>