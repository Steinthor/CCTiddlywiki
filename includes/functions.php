<?php	

function getOfflineFile()
{
	global $tiddlyCfg;
	if($tiddlyCfg['workspace_name'] !=="")
		$offline_name = $tiddlyCfg['workspace_name'];
	if($offline_name=="")
		$offline_name = "default_workspace";
	header("Content-Disposition: attachment; filename=\"".$offline_name.".html\";\r\n");
}

// takes a string, finds the text before the first tag and also reutrn text after the second tag.
function splitString($string, $search1, $search2)
{
	$start_pos = strpos($string, $search1)+strlen($search1);
	$end_pos = strpos($string, $search2);
	$part['1'] = substr($string, 0, $start_pos);
	$part['2'] = substr($string, $end_pos, strlen($string));
	return $part;
}


function checkAndAddSlash($uri)
{
	if(stristr($uri, ".php"))
		return true;
	if($uri[strlen($uri)-1]!="/") 
		header("location: ".$uri."/"); 
}

function getWorkspaceName($_SERVER, $_REQUEST)
{
	global $tiddlyCfg;
	if(substr($_REQUEST['workspace'], strlen($_REQUEST['workspace'])-1, strlen($_REQUEST['workspace']))=="/")
	{
		$str =  substr($_REQUEST['workspace'], 0,  $_REQUEST['workspace']-1);
	} else {
		$str =  $_REQUEST['workspace'];
	}
	return $str;
}

function getBaseDir($_SERVER)
{
	$d = dirname($_SERVER['SCRIPT_NAME']);
	return str_replace("handle", "", $d);
}

function handleDebug($_SERVER)
{
	global $ccT_msg, $tiddlyCfg;
	debug("--------------------------- >> ".$ccT_msg['debug']['logBreaker']." << ---------------------------");
	debug($ccT_msg['debug']['queryString'].$_SERVER['QUERY_STRING'], "params");
	debug($ccT_msg['debug']['fileName'].$_SERVER["SCRIPT_NAME"], "config");
	debug($ccT_msg['debug']['workspaceName'].$tiddlyCfg['workspace_name'], "config");
}

function stringToPerm($string)
{
 	$out['read'] = substr($string, 0, 1);
	$out['create'] = substr($string, 1, 1);
	$out['update'] = substr($string, 2, 1);
	$out['delete'] = substr($string, 3, 1);
	return $out; 
}
        
function getAdminsOfWorkspace($workspace)
{
	global $tiddlyCfg;
	$admin_select_data['workspace_name'] = $workspace;
	$results = db_record_select($tiddlyCfg['table']['admin'], $admin_select_data);// get list of admin users for workspace
	$i = 0;
	foreach($results as $result)
		$admin_array[$i++] = $result['username'];
	if(is_array($admin_array))
		return $admin_array;
	else
		return array();
}

function checkWorkspace($workspace_settings, $_POST, $cct_base)
{
	
	global $tiddlyCfg;
	if(sizeof($workspace_settings)==0)
	{
		if(strlen($tiddlyCfg['workspace_name'])==0)
		{//do install
			include_once("../includes/workspace.php");
			workspace_create($tiddlyCfg['workspace_name'], $_POST['ccAnonPerm']);
		} else {	//ifnot empty, check ifinstallation can be done
			if($tiddlyCfg['allow_workspace_creation']>0)
			{//ifallow workspace creation
				if($_POST)
				{
					include($cct_base."includes/workspace.php");
					workspace_create($tiddlyCfg['workspace_name'], $_POST['ccAnonPerm']);
				}
				if($tiddlyCfg['allow_workspace_creation']==2)	//if=2, only allow user to create workspace
				{
					//check ifuser login valid
				}
				//db_workspace_install($tiddlyCfg);		//install using default parameters
			} else {	//give error message of workspace not found
				header("HTTP/1.0 404 Not Found"); 
				exit;
			}
		}
	}	
}

function permToBinary($string)
{
	if($string == "A")
		return 1;
	else
		return 0;
	if($string == "D")
		return "D";
		if($string == "U")
			return "U";
}

function arrayToJson($data, $entityname) 
{
    $out = "{";
 	$out .= "\"".$entityname."\" : [";
	while ($row = db_fetch_assoc($data))
	{
		$out .= "{";
		foreach($row as $key=>$val)
		{
		    if(is_array($val)) 
			{
			    $output .= "\"".$key."\" : [{";
			    foreach($val as $subkey=>$subval)
				{
					$out .= "\"".$subkey."\" : \"".$subval."\",";
				}
	    		$out .= "}],";
			} else {
				$out .= "\"".$key."\" : \"".$val."\",";
			}
		}
		$out .= "},";
	}
    $out .= "]}";
	$out = 	str_replace(",}", "}", $out);
    return $out;
}

function getScheme()
{
	$scheme = 'http';
	if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on')
			$scheme .= 's';
	return $scheme; 
}

function getURL()
{
	$out = getScheme().'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."";
	return $out; 
}

//!	@fn array getAllTiddlers()
//!	@brief get all tiddlers in nested array, removing ones the user do not have read privilege
function getAllTiddlers($user_remove="", $search="")
{
	global $tiddlyCfg;
	global $user;
	db_connect();
	if($search != "")
	{
		$tiddlers = db_tiddlers_mainSearchAll($search);
	} else {
		$tiddlers = db_tiddlers_mainSelectAll();
	}
	$return_tiddlers = array();
	while($t = db_fetch_assoc($tiddlers))
	{
		if(user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$t['tags'])))
			$return_tiddlers[$t['title']] = $t;
	}
	return $return_tiddlers;
}


//!	@fn array getAllTiddlers()
//!	@brief get all tiddlers in nested array, removing ones the user do not have read privilege
function getSkinTiddlers($skin="")
{
	global $tiddlyCfg;
	global $user;
	$return_tiddlers = array();
	$real_workspace_name = $tiddlyCfg['workspace_name'];
	$tiddlyCfg['workspace_name'] = $skin;
	$tiddlers = db_tiddlers_mainSelectAll();
	if($tiddlers)
	{
		while($t = db_fetch_assoc($tiddlers))
		{
			if(user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$t['tags'])))
				$return_tiddlers[$t['title']] = $t;
		}
	}
	
	$tiddlyCfg['workspace_name'] = $real_workspace_name;
	return $return_tiddlers;
}

//!	@fn bool getAllVersionTiddly($title)
//!	@brief print all version of a particular tiddler, remove ones the user don't have read privilege.
//!	@param $title title of required tiddler
function getAllVersionTiddly($title)
{
	global $tiddlyCfg;
	$tiddler_id = tiddler_selectTitle($title);
	debug("getAllVersionTiddly", "steps");
	if(sizeof($tiddler_id)==0)
	{	//return empty array ifnot found
		return array();
	}
	$tiddlers = tiddler_selectBackupID($tiddler_id['id']);
	$user = user_create();
	if(sizeof($tiddlers)>0)
	{
		$return_tiddlers = array();
		foreach($tiddlers as $t)
		{
			if(user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$t['tags'])))
				$return_tiddlers[] = $t;
		}
		return $return_tiddlers;
	}
	return array();
}


function getTiddler($title, $workspace)
{
		global $tiddlyCfg;
	return $tiddlers = tiddler_selectTitle($title, $workspace);
	

		debug("getTiddler", "steps");
		if(sizeof($tiddler_id)==0)
		{	//return empty array ifnot found
			return array();
		}
		if(sizeof($tiddlers)>0)
		{
			$return_tiddlers = array();
			foreach($tiddlers as $t)
			{
				if(user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$t['tags'])))
					$return_tiddlers[] = $t;
			}
			return $return_tiddlers;
		}
		return array();
	
}

//!	@fn getTiddlersWithTags($yesTags,$noTags)
//!	@brief get tiddlers with and without certain tags
//!	@param $yesTags tag array, display tiddlers with this tag
//!	@param $noTags tag array, not display tiddlers with this tag
function getTiddlersWithTags($yesTags,$noTags)
{
	global $tiddlyCfg;
	$tiddlers = tiddler_selectAll();
	if(strlen($user)==0)
		$user = user_create();
	if(sizeof($tiddlers)>0)
	{
		$return_tiddlers = array();
		foreach($tiddlers as $t)
		{
			if(user_readPrivilege(user_tiddlerPrivilegeOfUser($user,$t['tags'])))
			{
				$tag = tiddler_breakTag($t['tags']);
				$tmp = array_merge($tag,$noTags);			
				if(sizeof($tmp) == sizeof(array_flip(array_flip($tmp))))		//ifno $noTags, continue
				{
					$tmp = array_merge($tag,$yesTags);
					//ifno yesTags, assume only want to remove some tag thus all but $noTags are returned
					//if$yesTags exist, display only if$yesTags is in tiddler
				//	if(sizeof($yesTags)==0 || sizeof($tmp) != sizeof(array_flip(array_flip($tmp)))){
						$return_tiddlers[$t['title']] = $t;
				//	}
				}
			}
		}
		return $return_tiddlers;		//tiddlers would be in the form array("<title>"=>array("title"=>"<title>", .....
	}
	return array();
}
///////////////////////////////////////////////////////////////get tiddlers/////////////////////////////////////////
	

//!	@fn string insertTiddler($userArr,$tiddlerArr)
//!	@brief insert tiddler into DB
//!	@param $userArr user array
//!	@param $tiddlerArr tiddler array
function insertTiddler($userArr,$tiddlerArr)
{
	if(user_insertPrivilege(user_tiddlerPrivilegeOfUser($userArr,$tiddlerArr['tags'])))
	{
		$ntiddler = tiddler_create($title, $body, $modifier, $modified, $tags);
		$tiddlerArr['creator'] = $tiddlerArr['modifier'];
		$tiddlerArr['created'] = $tiddlerArr['modified'];
		$tiddlerArr['revision'] = 1;
		tiddler_insert($tiddlerArr);
		return "001";
	} else {
		return "020";
	}
}

//!	@fn string updateTiddler($userArr,$tiddlerArr)
//!	@brief update a tiddler in DB
//!	@param $userArr user array
//!	@param $tiddlerArr new tiddler array, data to be inserted into db
//!	@param $otiddlerArr old tiddler array, the tiddler that requires update
function updateTiddler($userArr, $tiddlerArr, $otiddlerArr)
{
	//require edit privilege on new and old tags
	if(user_editPrivilege(user_tiddlerPrivilegeOfUser($userArr,$tiddlerArr['tags'])) 
		&& 	user_editPrivilege(user_tiddlerPrivilegeOfUser($userArr,$otiddlerArr['tags'])))
	{
		$tiddlerArr['creator'] = $otiddlerArr['creator'];
		$tiddlerArr['created'] = $otiddlerArr['created'];
		$tiddlerArr['revision'] = $otiddlerArr['revision']+1;
		tiddler_update($otiddlerArr, $tiddlerArr);
		return "002";
	} else {
		return "020";
	}
}



//!	@fn string deleteTiddler($userArr,$tiddlerArr)
//!	@brief delete a tiddler in DB
//!	@param $userArr user array
//!	@param $tiddlerArr new tiddler array, data to be inserted into db
function deleteTiddler($userArr, $tiddlerArr)
{
	if(user_deletePrivilege(user_tiddlerPrivilegeOfUser($userArr,$tiddlerArr['tags'])))
	{
		tiddler_delete($tiddlerArr);		//delete current tiddler
		return "003";
	} else {
		return "020";
	}
}

//  Returns time in TiddlyWiki format from Epoch time stamp.
function epochToTiddlyTime($timestamp)
{
	return date('YmdHi', $timestamp); 
}

// Takes TiddlyTime (as stored in TiddlyWiki) and returns an epoch timestamp.
function TiddlyTimeToEpoch($TiddlyTime)
{
	$y = substr($TiddlyTime, 0, 4); 
	$m = substr($TiddlyTime, 4, 2); 
	$d = substr($TiddlyTime, 6, 2); 
	$h = substr($TiddlyTime, 8, 2);
	$min = substr($TiddlyTime, 10, 2);
	return mktime($h, $min, $s, $m, $d, $y);
}

//////////////////////////////////////////////////////// cookie related////////////////////////////////////////////////////////
//!	@fn cookie_set($k,$v)
//!	@brief set cookie, apply rawurlencode before setting cookie for compatibility with TW
//!	@param $k cookie name
//!	@param $v cookie value
function cookie_set($k,$v)
{
	global $tiddlyCfg;
	$expire =  time()+$tiddlyCfg['session_expire'];	
	if(setcookie($k,$v, $expire,"/"))
		return true;
	else
		return false;
}

function cookie_kill($k)
{
	global $tiddlyCfg;
	return @setcookie($k, "", time() - 3600);
}

//!	@fn cookie_get($k)
//!	@brief get cookie, apply rawurldecode before return and empty string ifnot exist
//!	@param $k cookie name
function cookie_get($k)
{
	return (isset($_COOKIE[$k])?rawurldecode($_COOKIE[$k]):"");
}
//////////////////////////////////////////////////////// format related////////////////////////////////////////////////////////
//!	@fn formatParameters($str)
//!	@brief format string for use in processing (from AJAX). Different from others in that string is escaped using "encodeURIComponent"
//!	@param $str string to format
function formatParameters($str)
{
	$result = (get_magic_quotes_gpc()?stripslashes($str):$str);
	return utf8RawUrlDecode($result);
}

//!	@fn formatParametersGET($str)
//!	@brief format string from GET for use in processing
//!	@param $str string to format
function formatParametersGET($str)
{
	return (get_magic_quotes_gpc()?stripslashes($str):$str);
}

//!	@fn formatParametersPOST($str)
//!	@brief format string from POST
//!	@param $str string to format
function formatParametersPOST($str)
{
	return (get_magic_quotes_gpc()?stripslashes($str):$str);
}

//!	@fn format4Name($str)
//!	@brief format string for used in names, allow [a-zA-Z0-9-_.]
//!	@param $str string to format
function format4Name($str) 
{
	return preg_replace('![^a-zA-Z0-9\-_\.]!', '', $str);
}

//////////////////////////////////////////////////////// result related////////////////////////////////////////////////////////
//!	@fn sendHeader($httpCode, $returnStr, $processReport, $stop)
//!	@brief send header and result. Last line of return str is displayed in MessageBox of TW
//!	@param $httpCode header code returned
//!	@param $returnStr summary of process result, shown in MessageBox of TW
//!	@param $processReport a detailed result, detail of error ifoccured
//!	@param $stop stop the process [1=stop, 0=continue]
function sendHeader($httpCode, $returnStr="", $processReport="", $stop=0)
{
	$httpCode = (int)$httpCode;		//code must be in int
	switch($httpCode)
	{
		case 200:
			header("HTTP/1.0 200 OK");
			break;
		case 201:
			header("HTTP/1.0 201 Created");
			break;
		case 202:
			header("HTTP/1.0 202 Accepted");
			break;
		case 204:
			header("HTTP/1.0 204 No Content");
			break;
		case 302:
			header("HTTP/1.1 302 Found");
			break;
		case 304:
			header("HTTP/1.0 304 Not Modified");
			break;
		case 400:
			header("HTTP/1.0 400 Bad Request");
			break;
		case 401:
			header("HTTP/1.0 401 Unauthorized");
			break;
		case 403:
			header("HTTP/1.0 403 Forbidden");
			break;
		case 404:
			header("HTTP/1.0 404 Not Found");
			break;
		case 405:
			header("HTTP/1.1 405 Method Not Allowed");
			break;
		case 406:
			header("HTTP/1.1 406 Not Acceptable");//14 40min
			break;
		case 408:
			header("HTTP/1.1 408 Request Time-out");//14 40min
			break;
		case 409:
			header("HTTP/1.1 409 Conflict");//14 40min
			break;
		case 410:
			header("HTTP/1.1 410 Gone");//14 40min
			break;
		case 501:
			header("HTTP/1.0 501 Not Implemented");
			break;
	}
	if($stop!=0) 
		exit($processReport."\n".$returnStr);	
	return TRUE;
}
//////////////////////////////////////////////////////// error related////////////////////////////////////////////////////////
//!	@fn bool logerror($display_error, $stop_script=0, $record_error="")
//!	@brief log error in this function
//!	@param $display_error displayed error
//!	@param $stop_script exit script if1 is passed
//!	@param $record_error error that goes in log, use display error ifdifferent
function logerror($display_error, $stop_script=0, $record_error="")
{
	debug($record_error);
	if($stop_script == 1)		//display error and exit
	{
		exit();
		return TRUE;
	}
	return TRUE;
}

//////////////////////////////////////////////////////// debug only////////////////////////////////////////////////////////

//!	@fn bool debug($str)
//!	@brief debug function, similar to debugV but use print instead
//!	@param $str string to be printed
//!	@param $type type of debug - see different types of debug in config.php
function debug($str, $type="")
{
	global $tiddlyCfg;
	global $standalone;
	if($tiddlyCfg['developing']==0) 
		return false;
	if($tiddlyCfg['developing']==2) 
	{
		error_log($str, 0);
		return true;
	}
	if($tiddlyCfg['developing']==1 && $standalone!=1)
	{	
		if($type!=="")
		{	
			if($tiddlyCfg['debug'][$type]==1)
			{
				error_log($type." : ".$str, 0);
			}
		} else {
			error_log($str, 0);
		}
	} 
	return TRUE;
}


// PHP4 SUPPORT 

// Override functions for case of using PHP4
if(!function_exists("stripos"))
{
	function stripos($str, $needle, $offset = 0)
	{
		return strpos(strtolower($str), strtolower($needle), $offset);
	}
}
if(!function_exists("strripos"))
{
	function strripos($haystack, $needle, $offset = 0) 
	{
		if(!is_string($needle))$needle = chr(intval($needle));
		if($offset < 0)
		{
			$temp_cut = strrev(substr($haystack, 0, abs($offset)));
		} else {
			$temp_cut = strrev(substr($haystack, 0,
			max((strlen($haystack) - $offset), 0)));
		}
		if(($found = stripos($temp_cut,strrev($needle))) === FALSE)
			return FALSE;
		$pos = (strlen($haystack) - ($found + $offset + strlen($needle)));
		return $pos;
    }
}


if (!function_exists('mime_content_type')) {
   function mime_content_type($f) {
       $f = escapeshellarg($f);
       return trim( `file -bi $f` );
   }
}



?>
