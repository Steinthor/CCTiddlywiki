<?php
class Plugin {
	public $phpEvents;
	public $tiddlers;
		
	public function __construct($title, $version, $author="", $website="") {
		global $Plugins;
		$this->title = $title; // title should be the same as the plugin folder 
		$this->author = $author;
		$this->version = $version;
		$this->website = $website;
		$this->phpEvents = array();
		$this->tiddlers = array();
		array_push($Plugins,$this);
	}

	public function addTiddler($data, $path=null) {
		if(is_file($path)) {
			$tiddler = $this->tiddlerFromFile($path);
		} else {
			if(is_array($data)) 
			{
				$tiddler = array();
				$tiddler = array_merge_recursive($data,$tiddler);
			}
		}
		$this->tiddlers[$tiddler['title']] = $tiddler;	
		
	}
	
	function tiddlerFromFile($file) {
//		echo $file;
		$tiddler['created'] = epochToTiddlyTime(time());
		$tiddler['modified'] = epochToTiddlyTime(time());
		$tiddler['modifier'] = "ccTiddly";
		$tiddler['creator'] = "ccTiddly";
		$ext = substr($file, strrpos($file, '.') + 1);
		$tiddler['title'] = substr($file, strrpos($file, '/')+1, -strlen($ext)-1); 
		if($ext=='tiddler') {
			$tiddler['body'] = $this->getContentFromFile($file);	
			$tiddler['tags'] = "";
		} elseif($ext=='js') {
			$tiddler['body'] = file_get_contents($file);	
			$tiddler['tags'] = "systemConfig excludeLists";
		} elseif($ext=='tid') {
			$tiddler = tiddler_parse_tid_file($file);
//			print_r($tiddler);
		}
		if(isset($tiddlyCfg['plugins_tags'])) $tiddler['tags'] .= $tiddlyCfg['plugins_tags'];	
		return $tiddler;
	}
	
	public function addTiddlersFolder($dir, $data=null) {
		if (is_dir($dir)) 
		{
		    if ($dh = opendir($dir)) 
			{
		    	while (($file = readdir($dh)) !== false) 
				{
					if(is_dir($dir."/".$file)){
		
						if(substr($file, 0, 1)!=".")
							$this->addTiddlersFolder($dir."/".$file);
						}
						if(substr($file,0,1)!=".") 
						{ // do not include system/hidden files. 
							$tiddler = $this->tiddlerFromFile($dir."/".$file);
							if(is_array($data))
								$tiddler = array_merge($tiddler, $data); // allows users to add extra data.
							$this->addTiddler($tiddler);			
						}
					}
				}
			}
	}

	public function preparePath($path) {
		return $path;
	}
	public function getContentFromFile($path) {
		$file = @file_get_contents($path);
		return $file;
	}

	public function addRecipe($path) {	
		if(file_exists($this->preparePath($path))){
			$file = $this->getContentFromFile($this->preparePath($path));
			$this->parseRecipe($file, dirname($path));
		} else {
			$this->addTiddlersFolder(dirname($path)."/importedPlugins/");
		}
	}

	public function parseRecipe($string, $recipePath) {
		$lines = explode("\n", $string);
		foreach($lines as $line) {
			$this->parseRecipeLine($line, $recipePath);
		}
	}
	
	public function parseRecipeLine($line, $recipePath) {
		$ext = trim(end(explode(".", $line)));
		switch ($ext) {
			case 'recipe':
				$path = $recipePath.'/'.str_replace('recipe: ', '', $line);
				$this->addRecipe($path);
			break;
			case 'js' :
				$tiddler['title'] = substr(basename(str_replace('tiddler: ', '', $line)), 0, -strlen($ext)-1);
				$tiddler['tags'] = 'systemConfig';
				$tiddler['body'] = $this->getContentFromFile(str_replace('tiddler: ', '', $recipePath.'/'.$line));
				$this->addTiddler($tiddler);		
			break;
			case 'tid' :
 				$this->addTiddler($this->tiddlerFromFile($this->preparePath(str_replace('tiddler: ', '', $recipePath.'/'.$line))));
			break;
			default: 
		break;
		}		
	}
      
	public function addEvent($eventname, $fileInclude) {
		if (!isset($this->phpEvents[$eventname]))
			$this->phpEvents[$eventname] = array();
		array_push($this->phpEvents[$eventname], $fileInclude); 
	}
	     
	public function run() {
		global $pluginsLoader;  
		foreach ($this->phpEvents as $eventnames=>$eventArray) {
			foreach ($eventArray as $event)
				$pluginsLoader->addEvent($eventnames,$event);
		}
		foreach ($this->tiddlers as $tiddler) {
			$pluginsLoader->addTiddler($tiddler);
		}
	}   
}
?>
