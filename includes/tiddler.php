<?php

//////////////////////////////////////////////////////// FUNCTIONS ////////////////////////////////////////////////////////

		//!	@fn array tiddler_create($title, $body="", $modifier="", $modified="", $tags="", $id="", $creator="", $created="", $fields="", $version=1)
	//!	@brief create tiddler array with validation
	//!	@param $title title of tiddler
	//!	@param $body body of tiddler
	//!	@param $modifier modifier of tiddler
	//!	@param $modified modified date of tiddler
	//!	@param $tags tags string of tiddler
	//!	@param $id id of tiddler, 0 = unknown or new
	//!	@param $creator creator of tiddler
	//!	@param $created created date of tiddler
	//!	@param $fields field variable
	//!	@param $version version of tiddler, 1 = new
	function tiddler_create($title, $body="", $modifier="", $modified="", $tags="", 
				$id="", $creator="", $created="", $fields="", $revision=1)
	{
		$tiddler = array();
		$tiddler['id'] = preg_replace("![^0-9]!","",$id);		//if empty, leave it as empty. otherwise make it as int
		//$tiddler['title'] = tiddler_bodyEncode($title);pp
	 	$tiddler['title'] = $title;
		//$tiddler['body'] = tiddler_bodyEncode($body);
		$tiddler['body'] = $body;
		$tiddler['modifier'] = $modifier;
		$tiddler['modified'] = preg_replace("![^0-9]!","",$modified);
		$tiddler['creator'] = $creator;
		$tiddler['created'] = preg_replace("![^0-9]!","",$created);
		$tiddler['tags'] = $tags;
		$tiddler['fields'] = $fields;
		$tiddler['revision'] = preg_replace("![^0-9]!","",$revision);
		
		return $tiddler;
	}
	
	function tiddler_fieldsToJson($fieldString){
		$out = $fieldString;
		$fieldItem = explode(" ", $fieldString);
		foreach($fieldItem as $i) {
			$bv = explode("=", $i);
			if($bv[0] && $bv[1])
		 		$out = "'".$bv[0]."':".$bv[1].",";
		}
		return "{".substr($out, 0, -1)."}";
	}
	
	function tiddler_toJson($tiddler)
	{

		$output = "{";
		$output .=  '"id":"'.$tiddler['id'].'",';
		$output .= '"title":"'.$tiddler['title'].'",';
		$output .= '"text":"'.str_replace("\n", "\\n", $tiddler['body']).'",';
		$output .= '"modifier":"'.$tiddler['modifier'].'",';
		$output .= '"created":"'.$tiddler['created'].'",';
		$output .= '"modified":"'.$tiddler['modified'].'",';
		$output .= '"tags":"'.$tiddler['tags'].'",';
		$output .= '"fields":'.tiddler_fieldsToJson($tiddler['fields']).',';
		$output .= '"server.page.revision":"'.$tiddler['revision'];
		$output .= '"}';
		return $output;
	}
	
	//!	@fn array tiddler_backup_create($un)
	//!	@brief create backup tiddler array format using tiddler array
	//!	@param $tiddler_create tiddler array, created and verified using function tiddler_create
	//!	@param $oid oid of tiddler for used in versioning only
	//function tiddler_backup_create($title, $body="", $modifier="", $modified="", $tags="", $id="", $creator="", $created="", $version=1, $oid="")
	function tiddler_backup_create($tiddler_create, $oid="")
	{
		$tiddler = array();
		if(isset($tiddler_create['id']))
			$tiddler['id'] = preg_replace("![^0-9]!","",$tiddler_create['id']);		//if empty, leave it as empty. otherwise make it as int
		$tiddler['tiddler_id'] = preg_replace("![^0-9]!","",$oid);
		$tiddler['title'] = $tiddler_create['title'];
		$tiddler['body'] = $tiddler_create['body'];
		$tiddler['modifier'] = $tiddler_create['modifier'];
		$tiddler['modified'] = preg_replace("![^0-9]!","",$tiddler_create['modified']);
		//$tiddler['creator'] = $creator;
		//$tiddler['created'] = (int)$created;
		$tiddler['tags'] = $tiddler_create['tags'];
		$tiddler['fields'] = $tiddler_create['fields'];
		$tiddler['revision'] = $tiddler_create['revision'];
		return $tiddler;
	}
	
	
	

	///////////////////////////////////////////////////////////////encoding and formatting//////////////////////////////////////////////////

	//!	@fn array tiddler_breakTag($tagStr)
	//!	@brief break tag into array
	//!	@param $tagStr string of tags
	function tiddler_breakTag($tagStr)
	{
		$array = array();
		//obtain and remove [[tags]]
		$r=0;
		$e=0;		//ending tag position
		while( ($r=strpos( $tagStr, "[[", 0))!==FALSE && ($e=strpos( $tagStr, "]]", $r))!==FALSE ) //$e > $r so will use $r to find $e
		{
			$tag = substr($tagStr, $r+2, $e-$r-2);
			$array[] = $tag;
			$tagStr = str_replace('[['.$tag.']]'," ",$tagStr);
		}
		//obtain regular tags separate by space
		//put in all tags into $array
		$array = array_merge($array,explode(" ",$tagStr));
		//strip empty string and trim tags
		$return = array();
		foreach($array as $t)
		{
			if(strlen($t)>0)
			{
				$return[] = trim($t);
			}
		}
		return $return;
	}
	
	function tiddler_outputFolder($dir, $cct_base) 
	{	
		global $tiddlyCfg;
		$dir = $dir;
		// Open plugins directory, and read its contents
		if (is_dir($dir)) 
		{
		    if ($dh = opendir($dir)) 
			{
//				echo "powerpape".$file;
		       while (($file = readdir($dh)) !== false) 
				{
					$full  = $_SERVER['DOCUMENT_ROOT'].$tiddlyCfg['pref']['base_folder']."/".$dir."/".$file;
					if(is_dir($full))
						getDir($full, $file); 
					$ext = substr($file, strrpos($file, '.') + 1); 
					if ($ext == "js")			
						echo tiddler_outputJsFile($dir."/".$file, $cct_base);
					 else if ($ext == "tiddler")
						echo tiddler_outputTiddlerFile($dir."/".$file, $cct_base);
		    	}
		        closedir($dh);
		    }
		}
	}	
	
	function getDir($full, $file)
	{
		if($file!= "." && $file!=".." && $file!=".svn") 
			tiddler_outputFolder($full, $cct_base);
	}
	
	function tiddler_outputOffline()
	{
		global $tiddlyCfg;
		foreach ($tiddlyCfg['pref']['offline']['tiddler']  as $tf)
		{
        	echo tiddler_outputTiddlerFile(getcwd()."/plugins/ccTiddlyCore/".$tf, $cct_base);
        	echo tiddler_outputTiddlerFile(getcwd()."/lang/".$tiddlyCfg['pref']['language']."/".$tf.".tiddler", $cct_base);
		}
		foreach ($tiddlyCfg['pref']['offline']['js']  as $tf)
		{
			echo tiddler_outputJsFile("plugins/ccTiddlyCore/".$tf.".js", $cct_base);
		}
	}
	
	// Puts JS file into a tiddler with the same name as the file. The tiddler will be tagged systemConfig which means its loaded as a plugin.
	function tiddler_outputJsFile($file, $cct_base)
	{
			$file_parts=explode("/", $file);
			$tiddler_name = str_replace('.js', '', $file_parts[count($file_parts)-1]);
			$file = file_get_contents($cct_base.$file);
			if($file)
				return  "<div title=\"".$tiddler_name."\" modifier=\"ccTiddly\" tags=\"systemConfig excludeLists excludeSearch ccTiddly\">\n<pre>".htmlspecialchars($file)."</pre>\n</div>\n";
			else
				return false;
	}
	/* a .tiddler file takes the following format. 
		<div title="titlehere" modifier="me" modified="200804011417" created="200706181748" tags="ccTiddly excludeSearch excludeLists">
		<pre>
		CONTENT HERE 
		</pre>
		</div>	*/
	function tiddler_outputTiddlerFile($file, $cct_base)
	{
		$tiddler = file_get_contents($cct_base.$file);
		// now we have to pull out the tiddler content and encode it. 
		if ($pos1 = stripos($tiddler, "<pre>"))
		{
			// find the first pre tag and remove everything before it. 
			$top = substr($tiddler,  0, $pos1+5);
			$content = substr($tiddler,  $pos1+5); 
			// get the last </pre> tag
			$pos2 = strripos($content, "</pre>", 0);
			$bottom = substr($content,  $pos2); 
			$content = substr($content,0,$pos2);
		} else {
			// look for first closing tag
			$pos1 = stripos($tiddler, ">");
			$top = substr($tiddler,  0, $pos1+1);
			$content = substr($tiddler,  $pos1+1); 
			// look for the final closing div tag
		//	  $pos2 = strlen($content) - strpos(strrev($content), strrev("</div>")) - strlen("</div>");
			$pos2 = strripos($content, "</div>", 0);
			$bottom = substr($content,  $pos2); 
			$content = substr($content,0,$pos2);
		}
		return "\n\r".$top.htmlspecialchars($content).$bottom;
	}
	
	function tiddler_outputDIV($tiddler)
	{	
		global $tiddlyCfg;
		if (strpos(getURL(), "/handle"))
			$server = dirname(getURL());
		else
			$server = getURL();
		if(isset($tiddler["tags"]))
		{
			if(is_array($tiddler["tags"]))
				$tiddler["tags"] = implode(" ", $tiddler["tags"]);
		}
		if(isset($tiddler["id"]))
			$id = "server.id='".$tiddler["id"]."'";
		else
			$id = ""; // must be a system tiddler

		//echo "<div title=\"".$tiddler["title"]."\" modifier='".$tiddler["modifier"]."' modified='".$tiddler["modified"]."' created='".$tiddler["created"]."' tags='".$tiddler["tags"]."' server.page.revision='".$tiddler["revision"]."' server.host='".$server."' server.type='cctiddly'  server.workspace='".$tiddlyCfg['workspace_name']."' ".$tiddler["fields"]." ".$id.">\r\n<pre>".htmlspecialchars($tiddler['body'])."</pre>\r\n</div>\n\r";
		$str = "<div title=\"".$tiddler["title"]."\" modifier='";
		if(isset($tiddler["modifier"])) $str .= $tiddler["modifier"]."' modified='";
			else $str .= "' modified='";
		if(isset($tiddler["modified"])) $str .= $tiddler["modified"]."' created='";
			else $str .= "' created='";
		if(isset($tiddler["created"])) $str .= $tiddler["created"]."' tags='";
			else $str .= "' tags='";
		if(isset($tiddler["tags"])) $str .= $tiddler["tags"]."' server.page.revision='";
			else $str .= "' server.page.revision='";
		if(isset($tiddler["revision"])) $str .= $tiddler["revision"]."' server.host='".$server."' server.type='cctiddly'  server.workspace='".$tiddlyCfg['workspace_name']."' ";
			else $str .= "' server.host='".$server."' server.type='cctiddly'  server.workspace='".$tiddlyCfg['workspace_name']."' ";
		if(isset($tiddler["fields"])) $str .= $tiddler["fields"]." ".$id.">\r\n<pre>";
			else $str .= " ".$id.">\r\n<pre>";
		if(isset($tiddler['body'])) $str .= htmlspecialchars($tiddler['body'])."</pre>\r\n</div>\n\r";
			else $str .= "</pre>\r\n</div>\n\r";
		echo $str;
		
	return;
	}
	
	//!	@fn array tiddler_bodyEncode($body)
	//!	@brief encode string into TW div form
	//!	@param $body body string to be converted
	function tiddler_bodyEncode($body)
	{
		$body = str_replace('\\',"\\s",$body);		//replace'\' with '\s'
		$body = str_replace("\n","\\n",$body);		//replace newline with '\n'
		$body = str_replace("\r","",$body);		//return character is not required
		$body = htmlspecialchars($body);		//replace <, >, &, " with their html code
		return $body;
	}
	
	//!	@fn array tiddler_bodyDecode($body)
	//!	@brief convert TW div form to display form
	//!	@param $body body string to be converted
	function tiddler_bodyDecode($body)
	{
	//	$body = htmlspecialchars_decode($body);		//replace <, >, &, " with their html code, htmlspecialchars_decode only available in PHP5
		$body = str_replace("&quot;","\"",$body);
		$body = str_replace("&#039;","'",$body);
		$body = str_replace("&lt;","<",$body);
		$body = str_replace("&gt;",">",$body);
		$body = str_replace("&amp;","&",$body);
		$body = str_replace("\\n","\n",$body);		//replace newline with '\n'
		$body = str_replace('\\s',"\\",$body);		//replace'\' with '\s'
		//$body = str_replace("\r","",$body);		//return character is not required
		return $body;
	}
	///////////////////////////////////////////////////////////////privilege function//////////////////////////////////////////////////
	//!	@fn bool tiddler_markupCheck($userArr, $title)
	//!	@brief check if user got permission to change title, only do markup check. [TRUE = ok, FALSE = no permission to change markup]
	//!	@param $userArr user array
	//!	@param $title title of tiddler
	function tiddler_markupCheck($userArr, $title)
	{
		//global $tiddlyCfg;
		if(strcmp($title,"MarkupPostBody")==0 || strcmp($title,"MarkupPostHead")==0 || strcmp($title,"MarkupPreBody")==0 || strcmp($title,"MarkupPreHead")==0)
		{
			return tiddler_privilegeMiscCheck($userArr, "markup");
		}
		return TRUE;
	}
	
	//!	@fn bool tiddler_privilegeMiscCheck($userArr, $type)
	//!	@brief check user  permission to $type defined by $tiddlyCfg['privilege_misc']. [TRUE = ok, FALSE = no permission]
	//!	@param $userArr user array
	//!	@param $type type of privilege to check
	function tiddler_privilegeMiscCheck($userArr, $type)
	{
		global $tiddlyCfg;
		
		$ugroup = array_merge($userArr['group'],$tiddlyCfg['privilege_misc'][$type]);		//append one array to another
		$ugroupsize = sizeof($ugroup);		//get initial size
		$ugroup = array_flip(array_flip($ugroup));		//flip^2 to remove duplicate
		if(sizeof($ugroup) == $ugroupsize)		//check group size. return FALSE if not in markup group
		{
			return FALSE;
		}
		return TRUE;
	}
	///////////////////////////////////////////////////////////////DB access//////////////////////////////////////////////////
	//!	@fn bool tiddler_insert($tiddler, $backup=-1)
	//!	@brief save tiddler to DB
	//!	@param $tiddler tiddler array
	//!	@param $backup save backup, [-1 means using value in config]
	function tiddler_insert($tiddler, $backup=-1)
	{
		global $tiddlyCfg;
		$result = db_record_insert($tiddlyCfg['table']['main'], $tiddler);
		print db_error();
		
		if( $result===FALSE )
		{
			return FALSE;
		}
		//insert backup if required
		if( $backup==1 || ($backup==-1 && $tiddlyCfg['keep_revision']==1) )
		{	//set inserted record id as oid
			$tiddler = tiddler_backup_create($tiddler, db_insert_id($result));
			$result = db_record_insert($tiddlyCfg['table']['backup'], $tiddler);
		}
		return TRUE;
	}
	
	//!	@fn bool tiddler_delete($tiddler)
	//!	@brief delete tiddler from DB
	//!	@param $tiddler tiddler array, use id for delete
	function tiddler_delete($tiddler)
	{
		global $tiddlyCfg;	
		//insert record, will stop at db_query function if error occurs
		return db_record_delete($tiddlyCfg['table']['main'], $tiddler);
	}
	
	//!	@fn bool tiddler_update($oldtiddler, $tiddler, $backup=-1)
	//!	@brief update tiddler in DB
	//!	@param $oldtiddler old tiddler array, only to hold the id for updating
	//!	@param $tiddler tiddler array
	//!	@param $backup save backup, [-1 means using value in config]
	function tiddler_update($oldtiddler, $tiddler, $backup=-1)
	{
		global $tiddlyCfg;
		//insert record, will stop at db_query function if error occurs
		$result = db_record_update($tiddlyCfg['table']['main'], $oldtiddler, $tiddler);
		if( $result===FALSE )
		{
			return FALSE;
		}
		//insert backup if required
		if( $backup==1 || ($backup==-1 && $tiddlyCfg['keep_revision']==1) )
		{	//set inserted record id as oid
			$tiddler = tiddler_backup_create($tiddler, $oldtiddler['id']);
			$result = db_record_insert($tiddlyCfg['table']['backup'], $tiddler);
		}
		return TRUE;
	}

	//!	@fn array tiddler_selectTitle($tiddler)
	//!	@brief get tiddler with title $title (case sensitive)
	//!	@param $tiddler tiddler array, can also be title
	function tiddler_selectTitle($tiddler, $workspace=null)
	{
		global $tiddlyCfg;
		if( !is_array($tiddler) )
		{
			$tiddler = tiddler_create($tiddler);
		}
		if($workspace)
			$tiddler['workspace_name'] = $workspace;

		$tiddlers = db_record_select($tiddlyCfg['table']['main'],$tiddler,1);

		//grab record and check if title are the same
		//this is required since mysql is not binary safe unless deliberately configured in table
		//result would be empty string if not found, array if found
		foreach($tiddlers as $t)
		{
			if( strcmp($t['title'],$tiddler['title'])==0 )
			{
				//$tmp[] = $t;
				return $t;
			}
		}
		return array();		//only return 1 title
	}
	
	//!	@fn array tiddler_selectAll()
	//!	@brief get all tiddler and return as array
	function tiddler_selectAll()
	{
		global $tiddlyCfg;
		return db_record_selectAll($tiddlyCfg['table']['main']);
	}
	
	//!	@fn array tiddler_selectBackupID($id)
	//!	@brief get all tiddler in backup table with id = $id
	//!	@param $id id of tiddler
	function tiddler_selectBackupID($id)
	{
		global $tiddlyCfg;
		return db_record_select($tiddlyCfg['table']['backup'],tiddler_backup_create(array(),$id),1);
	}
	///////////////////////////////////////////////new functions/////////////////////////////////////////
	//!	@fn bool tiddler_insert($tiddler, $backup=-1)
	//!	@brief save tiddler to DB
	//!	@param $tiddler tiddler array
	//!	@param $backup save backup, [-1 means using value in config]
	function tiddler_insert_new($tiddler,$stop=1)
	{
		global $tiddlyCfg;
		$result = db_tiddlers_mainInsert($tiddler,$stop);
		if( $result===FALSE ) 
			return FALSE;
		//insert backup if required
		if( $tiddlyCfg['keep_revision']==1 ) 
		{
			$tiddler = tiddler_backup_create($tiddler, db_insert_id());
			$id = mysql_insert_id();
			$result = db_tiddlers_backupInsert($tiddler,$stop);
		}
		return $id;
	}
	
	//!	@fn bool tiddler_delete($tiddler)
	//!	@brief delete tiddler from DB
	//!	@param $tiddler tiddler array, use id for delete
	function tiddler_delete_new($id)
	{
		return db_tiddlers_mainDelete($id);
	}
	
	//!	@fn bool tiddler_update($oldtiddler, $tiddler, $backup=-1)
	//!	@brief update tiddler in DB
	//!	@param $oldtiddler old tiddler array, only to hold the id for updating
	//!	@param $tiddler tiddler array
	//!	@param $backup save backup, [-1 means using value in config]
	function tiddler_update_new($oid, $tiddler, $stop=1)
	{
		global $tiddlyCfg;
		

		//updaterecord
		$result = db_tiddlers_mainUpdate($oid,$tiddler,$stop);
		debug("res : "+$result, "save");
		if( $result===FALSE )
			return FALSE;
		if( $tiddlyCfg['keep_revision']==1 ) 
		{	//insert backup if required
			$tiddler = tiddler_backup_create($tiddler, $oid);
			$result = db_tiddlers_backupInsert($tiddler,$stop);
		}
		return TRUE;
	}
	
	// takes a path to a .tid file and returns a tiddler object.
	function tiddler_parse_tid_file($file)
	{	
		$file = trim($file);
		$fh = @fopen($file, 'r');
		$tiddly_body = @fread($fh, filesize($file));		
		//	$tiddly_body = file_get_contents($file);		
		$position = strpos($tiddly_body, "\n\n");
		$top = substr($tiddly_body, 0, $position);
	 	$file_slash_position = strrpos($file, "/");
		$tiddler['title'] = substr($file,$file_slash_position+1,-4);
		$tiddler['body'] = substr($tiddly_body, $position+1);
		$fields = explode("\n", $top);
		foreach($fields as $field)
		{
			$pairs = explode(":", $field);
			if(isset($pairs[0]) && isset($pairs[1])) $tiddler[$pairs[0]] = trim($pairs[1]);
		}
		return $tiddler;
	}
	
?>
