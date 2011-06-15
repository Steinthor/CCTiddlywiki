<?php

$db_var['host'] = $tiddlyCfg['db']['host'];
$db_var['port'] = $tiddlyCfg['db']['port'];
$db_var['username'] = $tiddlyCfg['db']['login'];
$db_var['password'] = $tiddlyCfg['db']['pass'];
$db_var['db'] = $tiddlyCfg['db']['name'];

//define if script should stop when error occurs
//	0 = continue even when error occurs
//	1 = stop when error occurs
$db_var['settings']['defaultStop'] = 1;

//define what should happen if db_logerror is called
//	0 = do nothing
//	1 = output and log error
$db_var['settings']['handleError'] = 1;

//define how data is passed into these functions
//	0 = assume magic_quote OFF and always add slashes to values
//	1 = assume magic_quote ON and never add slashes
//	2 = detect magic_quote and act accordingly
$db_var['settings']['magic_quote'] = 0;		

//!	@fn resource db_connect($cont)
//!	@brief make connection to database and select database
//!	@param $cont do not stop script if TRUE
function db_connect_new($cont=FALSE)
{
	global $db_var;
	global $tiddlyCfg;
	$link = mysql_connect(
		$db_var['host'].((isset($db_var['port'])&&strlen($db_var['port'])>0)?":".$db_var['port']:"")
		,$db_var['username']
		,$db_var['password']
		,TRUE)
		or die(mysql_error());
	if($cont) 
	{	//if $cont is set, return FALSE instead of exit script. This is used for installation
		if(mysql_select_db($db_var['db'],$link)===FALSE) 
			return FALSE;
	} else {
		mysql_select_db($db_var['db'],$link) or die(mysql_error());
	}
	//set to utf-8 communication
	if($tiddlyCfg['pref']['utf8'] == 1)
		mysql_query("SET NAMES 'utf8'") or die(mysql_error());	
	return TRUE;
}

//!	@fn bool db_close($SQLH)
//!	@brief close db
//!	@param $SQLH SQL handle
function db_close($SQLH=FALSE)
{
	if($SQLH===FALSE)
		mysql_close();
	else
		mysql_close($SQLH);
	return TRUE;
}

///////////////////////////////////////////////////////install functions///////////////////////////////////////////////////////////
//!	@fn bool db_install_db()
//!	@brief install db
function db_install_db()
{
	global $db_var; 
	$sql = "CREATE DATABASE `".$db_var['db']."`";
	debug($sql, "mysql");
	mysql_query($sql) or die(mysql_error());
	return TRUE;
}

//!	@fn bool db_install_mainT()
//!	@brief install main table
function db_install_mainT()
{
	global $tiddlyCfg;		
	$query = "CREATE TABLE ".$tiddlyCfg['table']['main']." (
		`id` int(11) NOT NULL auto_increment,
		`title` varchar(255) NOT NULL default '',
		`body` text NOT NULL,
		`fields` text NOT NULL,
		`modified` varchar(128) NOT NULL default '',
		`created` varchar(128) NOT NULL default '',
		`modifier` varchar(255) NOT NULL default '',
		`creator` varchar(255) NOT NULL default '',
		`revision` int(11) NOT NULL default '0',
		`tags` varchar(255) NOT NULL default '',
		`workspace_name` int(3) NOT NULL default '0',
		PRIMARY KEY (id)
		)
		TYPE=MyISAM";
	if($tiddlyCfg['pref']['utf8']==1)
	{
		$query .= " CHARACTER SET utf8
			COLLATE utf8_unicode_ci";
	}
	if(mysql_query("DESCRIBE ".$tiddlyCfg['table']['main'])===FALSE) 
	{
		mysql_query($query) or die(mysql_error());
		return TRUE;
	} else { //table existed
		return FALSE;
	}
}

//!	@fn bool db_install_backupT()
//!	@brief install backup table
function db_install_backupT()
{
	global $tiddlyCfg;
	$query = "CREATE TABLE ".$tiddlyCfg['table']['backup']." (
		`id` int(11) NOT NULL auto_increment,
		`title` varchar(255) NOT NULL default '',
		`body` text NOT NULL,
		`fields` text NOT NULL,
		`modified` varchar(128) NOT NULL default '',
		`modifier` varchar(255) NOT NULL default '',
		`revision` int(11) NOT NULL default '0',
		`tags` varchar(255) NOT NULL default '',
		`oid` INT(11) NOT NULL,
		`workspace_name` int(3) NOT NULL default '0',
		PRIMARY KEY (id)
		)
		TYPE=MyISAM";
	if($tiddlyCfg['pref']['utf8']==1) 
	{
		$query .= " CHARACTER SET utf8
			COLLATE utf8_unicode_ci";
	}
	if(mysql_query("DESCRIBE ".$tiddlyCfg['table']['backup'])===FALSE) 
	{
		mysql_query($query)	or die(mysql_error());
		return TRUE;
	} else { //table existed
		return FALSE;
	}
}
	
///////////////////////////////////////////////////////record functions///////////////////////////////////////////////////////////
//!	@fn array db_fetch_assoc($result)
//!	@brief return first row of a query result(associative  indices) or data of array from current pointer
//!	@param $result result returned from sql query
function db_fetch_assoc(&$result)
{
	if(is_array($result)) 
	{
		if(sizeof($result) == 0)
			return FALSE;
		$tmp = current($result);		//return result from current element
		unset($result[key($result)]);
		return $tmp;
	} else {
		return mysql_fetch_assoc($result);
	}
	return FALSE;
}
///////////////////////////////////////////////////////workspace settings functions///////////////////////////////////////////////////////////
//!	@fn array db_workspace_selectSettings()
//!	@brief select setting from DB
function db_workspace_selectSettings()
{
	global $tiddlyCfg;
	$q = "SELECT * FROM ".$tiddlyCfg['table']['workspace']." WHERE name='".$tiddlyCfg['workspace_name']."'";
	$r = mysql_query($q) or die(mysql_error());
	//grab record and check if setting name match
	//this is required since mysql is not binary safe unless deliberately configured in table
	//result would be empty string if not found, array if found
	while($t = mysql_fetch_assoc($r))
	{
		if(strcmp($t['name'],$tiddlyCfg['workspace_name'])==0)
			return $t;
	}	
	return array();
}

//!	@fn array db_workspace_selectAll()
//!	@brief select all workspace name from DB
function db_workspace_selectAllPublic()
{
	global $tiddlyCfg;
	echo $q = "SELECT * FROM ".$tiddlyCfg['table']['workspace']." WHERE default_anonymous_perm LIKE 'A%%%'";
	$r = mysql_query($q) or die(mysql_error());
	echo mysql_num_rows($r);
	return $r;
}


//!	@fn array db_workspace_selectOwnedBy($user)
//!	@brief select all workspace name from DB where user owns the workspace
function db_workspace_selectOwnedBy($user)
{
	global $tiddlyCfg;
	//or use status=P for public???
	$q = "SELECT * FROM ".$tiddlyCfg['table']['admin']." WHERE username='".$user."'";
	debug("db_workspace_selectOwnedBy: ".$q, "mysql");
	$r = mysql_query($q) or die(mysql_error());
	return $r;
}

//!	@fn array db_workspace_install()
//!	@brief install workspace
function db_workspace_install()
{
	global $tiddlyCfg;
	$q = "INSERT INTO ".$tiddlyCfg['table']['workspace']
		."(`name`,`twLanguage`,`keep_revision`"
		.",`require_login`,`session_expire`,`tag_tiddler_with_modifier`"
		.",`char_set`,`hashseed`"
		.",`status`,`tiddlywiki_type`,`default_anonymous_perm`"
		.",`default_user_perm`,`rss_group`,`markup_group`)"
		." VALUES "
		."('".$tiddlyCfg['workspace_name']."'"
		.",'".$tiddlyCfg['twLanguage']."'"
		.",'".$tiddlyCfg['keep_revision']."'"
		.",'".$tiddlyCfg['require_login']."'"
		.",'".$tiddlyCfg['session_expire']."'"
		.",'".$tiddlyCfg['tag_tiddler_with_modifier']."'"
		.",'".$tiddlyCfg['char_set']."'"
		.",'".$tiddlyCfg['hashseed']."'"
		.",'".$tiddlyCfg['status']."'"
		.",'".$tiddlyCfg['tiddlywiki_type']."'"
		.",'".$tiddlyCfg['default_anonymous_perm']."'"
		.",'".$tiddlyCfg['default_user_perm']."'"
		.",'".$tiddlyCfg['rss_group']."'"
		.",'".$tiddlyCfg['markup_group']."')";
	debug("db_workspace_install: ".$q, "mysql");
	$r = mysql_query($q) or die(mysql_error());
	return TRUE;
}
///////////////////////////////////////////////////////record select functions///////////////////////////////////////////////////////////
//!	@fn array db_tiddlers_mainSelectAll()
//!	@brief select all tiddlers from db
function db_tiddlers_mainSelectAll()
{
	global $tiddlyCfg;
	$q= "SELECT * FROM ".$tiddlyCfg['table']['main']." WHERE workspace_name='".$tiddlyCfg['workspace_name']."'";
	debug($q, "mysql");
	$result = mysql_query($q)
		or die(mysql_error());
	return $result;
}

function db_tiddlers_mainSelectSkin($skin)
{
	global $tiddlyCfg;
	$start= "SELECT * FROM ".$tiddlyCfg['table']['main']." WHERE ";
	if($tiddlyCfg['workspace_skin'] !== 'none' && !$skin)
		$q.= " workspace_name='".$tiddlyCfg['workspace_skin']."'";
	else if($skin) 
		$q.= "  workspace_name='".$skin."'";
	if(!$q)
		return false;
	$q = $start.$q;
	debug($q, "mysql");
	$result = mysql_query($q) or die(mysql_error());
	return $result;
}

//!	@fn array db_tiddlers_mainSelect4RSS()
//!	@brief select query for RSS
function db_tiddlers_mainSelect4RSS($tag)
{
	global $tiddlyCfg;
	$query= "SELECT * FROM ".$tiddlyCfg['table']['main']
		." WHERE workspace_name='".$tiddlyCfg['workspace_name']."' AND tags LIKE '%".$tags."%'"
		." ORDER BY modified DESC LIMIT 20";
	debug("db_tiddlers_mainSelect4RSS: ".$query, "mysql");
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}

//!	@fn array db_tiddlers_mainSelect4RSS()
//!	@brief select query for RSS
function db_tiddlers_mainSelectSiteConfig()
{
	global $tiddlyCfg;
	$q = "SELECT * FROM ".$tiddlyCfg['table']['main']." WHERE workspace_name='".$tiddlyCfg['workspace_name']."'";
	$q .= " AND (title='SiteTitle'";
	$q .= " OR title='SiteUrl'";
	$q .= " OR title='SiteSubtitle'";
	$q .= ")";
	debug("db_tiddlers_mainSelectSiteConfig: ".$q, "mysql");
	$result = mysql_query($q) or die(mysql_error());
	return $result;
}

function db_tiddlers_mainSearchAll($term)
{
	global $tiddlyCfg;
	$q= "SELECT * FROM ".$tiddlyCfg['table']['main']." WHERE workspace_name='".$tiddlyCfg['workspace_name']."' and (title  like '%".$term."%' or body  like '%".$term."%')";
	debug("db_tiddlers_mainSearchAll: ".$q, "mysql");
	$result = mysql_query($q);
	return $result;
}




//!	@fn array db_tiddlers_selectId($id)
//!	@brief select tiddler with particular id
//!	@param $id id of tiddler
function db_tiddlers_mainSelectId($id)
{
	global $tiddlyCfg;
	$q = "SELECT * FROM `".$tiddlyCfg['table']['main']
		."` WHERE workspace_name='".$tiddlyCfg['workspace_name']
		."' AND id='".db_format4SQL($id)."'";
	debug("db_tiddlers_mainSelectTitle: ".$q, "mysql");
	$result = mysql_query($q) or die(mysql_error());
	//grab record and check if title are the same
	//this is required since mysql is not binary safe unless deliberately configured in table
	//result would be empty string if not found, array if found
	while($t = mysql_fetch_assoc($result))
	{
		if(strcmp($t['id'],$id)==0)
			return $t;
	}
	return FALSE;
}


//!	@fn array db_tiddlers_selectTitle($title,$workspace)
//!	@brief select tiddler with particular title
//!	@param $table table name
//!	@param $title title of tiddler
//!	@param $workspace workspace of db
function db_tiddlers_mainSelectTitle($title)
{
	global $tiddlyCfg;
	$q = "SELECT * FROM `".$tiddlyCfg['table']['main']
		."` WHERE workspace_name='".$tiddlyCfg['workspace_name']
		."' AND title='".db_format4SQL($title)."'";
	debug("db_tiddlers_mainSelectTitle: ".$q, "mysql");
	$result = mysql_query($q) or die(mysql_error());
	//grab record and check if title are the same
	//this is required since mysql is not binary safe unless deliberately configured in table
	//result would be empty string if not found, array if found
	while($t = mysql_fetch_assoc($result))
	{
		if(strcmp($t['title'],$title)==0)
			return $t;
	}
	return FALSE;
}

function db_tiddlers_backupSelectOid($oid)
{
	global $tiddlyCfg;
	$q = "SELECT * FROM `".$tiddlyCfg['table']['backup']
		."` WHERE `tiddler_id`='".db_format4SQL($oid)."'"
		." ORDER BY `revision` DESC";
	debug("db_tiddlers_backupSelectOid: ".$q, "mysql");
	$result = mysql_query($q) or die(mysql_error());
	//grab record and check if title are the same
	//this is required since mysql is not binary safe unless deliberately configured in table
	//result would be empty string if not found, array if found
	$r = array();
	while($t = mysql_fetch_assoc($result))
	{
		$r[] = $t;
	}
	return $r;
}
///////////////////////////////////////////////////////record manupulate///////////////////////////////////////////////////////////

function db_tiddlers_mainInsert($tiddler,$stop=1)
{
	global $tiddlyCfg;
	while((list($k,$v) = each($tiddler)))
	{
		$q = "`".db_format4SQL($k).'`="'.db_format4SQL($v).'",';
		if(strcmp($k,"id")!=0) 
		{
			$key[] = $k;
			$val[$k] = (string)db_format4SQL($v);
		}
	}
	$q = "INSERT INTO ".$tiddlyCfg['table']['main']
			."(`".implode("`,`",$key)."`,`workspace_name`)"
			.' VALUES ("'.implode('","',$val).'","'.$tiddlyCfg['workspace_name'].'")';
	debug("db_tiddlers_mainInsert: ".$q, "mysql");
	if($stop==1) 
		$result = mysql_query($q) or die(mysql_error().$q);
	else
		$result = mysql_query($q);
	return $result;
}

//!	@fn array db_tiddlers_insert($table,$workspace)
//!	@brief insert tiddlers
//!	@param $table table name required
//!	@param $workspace workspace of db
function db_tiddlers_backupInsert($tiddler,$stop=1)
{
	global $tiddlyCfg;
	while((list($k,$v) = each($tiddler)))
	{
		$q = "`".db_format4SQL($k)."`='".db_format4SQL($v)."',";
		if(strcmp($k,"id")!=0) 
		{
			$key[] = $k;
			$val[$k] = (string)db_format4SQL($v);
		}
	}
	$q = "INSERT INTO ".$tiddlyCfg['table']['backup']
			."(`".implode("`,`",$key)."`)"
			." VALUES ('".implode("','",$val)."')";
	debug("db_tiddlers_backupInsert: ".$q, "mysql");
	if($stop==1) 
		$result = mysql_query($q)	or die(mysql_error());
	else
		$result = mysql_query($q);
	return $result;
}


//!	@fn array db_tiddlers_insert($table,$workspace)
//!	@brief insert tiddlers
//!	@param $table table name required
//!	@param $workspace workspace of db
function db_tiddlers_mainUpdate($oid,$tiddler,$stop=1)
{
	global $tiddlyCfg;
	//remove primary key (first element in array)
	//array_shift($tiddler);
	//make query
	$q = "UPDATE ".$tiddlyCfg['table']['main']." SET ";
	while((list($k,$v) = each($tiddler)))
	{
		if($k!=="id") // hack to avoid updating the id
		$q .= "`".db_format4SQL($k).'`="'.db_format4SQL($v).'",';
	}
	$q = substr($q,0,(strlen($q)-1));		//remove last ","
	$q .= " WHERE `id` = '".$oid."'";
	debug($q, "mysql");
	if($stop==1) 
		$result = mysql_query($q)or die(mysql_error());
	 else 
		$result = mysql_query($q);
	return db_affected_rows();
}
//!	@fn array db_tiddlers_insert($table,$workspace)
//!	@brief insert tiddlers
//!	@param $table table name required
//!	@param $workspace workspace of db
function db_tiddlers_mainDelete($id)
{

	global $tiddlyCfg;
	$q = "DELETE FROM ".$tiddlyCfg['table']['main']." WHERE `id` = '".$id."'";
	//send query
	debug($q, "mysql");
	$result = mysql_query($q)
		or die(mysql_error());
	return $result;
}
///////////////////////////////////////////////////////record functions///////////////////////////////////////////////////////////
/**
Record functions are intermediate functions to interact with DB in array forms.
It does not do error checking, error checking is done in DB functions or outside here
**/

//----------------------------------------record functions--------------------------------------------------------------------------//
//!	@fn bool db_record_insert($table,$data)
//!	@brief insert record into db
//!	@param $table table name required
//!	@param $data data array
//WARNING: did not check field length, thus truncated by mysql if too long
//ASSUMPTION: first record is id and thus not add to db if empty (using auto_increment)
function db_record_insert($table,$data)
{
	global $tiddlyCfg;		
	if(strlen(current($data))==0)
	{	//if first element is empty, remove it since its the primary key and using auto_increment
		//remove primary key (first element in array)
		array_shift($data);
	}
	$key=array_keys($data);
	$i=0;
	$size=sizeof($key);
	while($i<$size)
	{
		$data[$key[$i]]=(string)db_format4SQL($data[$key[$i]]);
		$i++;
	}
	$q = "INSERT INTO ".$table." (`".implode("`,`",$key)."`) VALUES ('".implode("','",$data)."')";
	debug($q, "mysql");
	$r = db_query($q);
	return $r;
}

//!	@fn bool db_record_delete($table,$data)
//!	@brief delete record in db
//!	@param $table table name required
//!	@param $data data array, only use first (or defined position) as id  to identify record
//!	@param $keyPosition where in the array is the key, normally position '0'
//ASSUMPTION: first record is id/key and used to identify record. Can be changed with $keyPosition.
function db_record_delete($table,$data,$keyPosition=0, $operator='=')
{
	//move array pointer to id
	$i=0;
	while($i<$keyPosition)
	{
		next($data);
		$i++;
	}
	$q = "DELETE FROM ".$table." WHERE `".db_format4SQL(key($data))."` ".$operator." '".db_format4SQL(current($data))."'";
	debug($q, "mysql");
	return db_query($q);
}

//!	@fn resource db_record_update($table,$data)
//!	@brief update record in db
//!	@param $table table name required
//!	@param $odata old data array, mainly to use the first element (primary key) to look for record to update
//!	@param $ndata new data array to update record
function db_record_update($table,$odata,$ndata)
{
	if(strlen(current($ndata))==0)
	{	//if first element of new data is empty, remove it since its the primary key and wont usualy need updating
		//remove primary key (first element in array)
		array_shift($ndata);
	}
	$sql="UPDATE ".$table." SET ";
	while((list($k,$v) = each($ndata)))
	{
		$sql .= "`".db_format4SQL($k)."`='".db_format4SQL($v)."',";
	}
	$sql=substr($sql,0,(strlen($sql)-1));		//remove last ","
	$sql .= " WHERE `".db_format4SQL(key($odata))."` = '".db_format4SQL(current($odata))."'";
	debug($sql, "mysql");
	db_query($sql);
	return db_affected_rows();
}

//!	@fn array db_record_select($table,$data)
//!	@brief select record from db
//!	@param $table table name required
//!	@param $data data array, only use first (or defined position) as id  to identify record
//!	@param $keyPosition the $keyPosition th number of element in array to search for, $keyPosition=0 means search with id
function db_record_select($table,$data,$keyPosition=0,$end="")
{
	$i=0;
	while($i<$keyPosition)
	{
		next($data);
		$i++;
	}
	$sql_start = "SELECT * FROM ".$table." WHERE ";
	$sql = "";
	while((list($k,$v) = each($data)))
	{
		if(($v != '') || ($k=='workspace_name'))  // make sure we dont search on emtpy values unless its 
			$sql .= "`".db_format4SQL($k)."`='".db_format4SQL($v)."' and ";
	}
	$sql= $sql_start.substr($sql,0,(strlen($sql)-4));		//remove last "and"
	if($sql == $sql_start)
		$sql = str_replace("WHERE", "", $sql);
	$sql .= $end;
	$result = db_query($sql);
	if($result === FALSE)
		return FALSE;
	//grab all result from resource to form array
	$return=array();
	while(($tmp=db_fetch_assoc($result))!==FALSE)
	{
		$return[]=$tmp;
	}
	return $return;
}

//!	@fn array db_record_select($table,$data)
//!	@brief select record from db
//!	@param $table table name required
function db_record_selectAll($table)
{
	global $tiddlyCfg;
	if($table == $tiddlyCfg['table']['main'])
		$q = "SELECT * FROM ".$table." where workspace_name='".$tiddlyCfg['workspace_name']."'";
	else 
		$q = "SELECT * FROM ".$table;
	debug($q, "mysql");
	$result = db_query($q);
	if($result === FALSE)
		return FALSE;
	$data=array();
	while(($tmp=db_fetch_assoc($result)))
	{
		$data[]=$tmp;
	}
	return $data;
}

///////////////////////////////////////////////////////misc functions///////////////////////////////////////////////////////////
/**
misc functions for use in here
**/
//!	@fn bool db_logerror($display_error, $stop_script=0, $record_error="")
//!	@brief log error in this function
//!	@param $display_error displayed error
//!	@param $stop_script exit script if 1 is passed
//!	@param $record_error error that goes in log, use display error if different
function db_logerror($display_error, $stop_script=0, $record_error="")
{
	global $db_var;
	if($db_var['settings']['handleError'])
		logerror($display_error, $stop_script, $record_error);
	return TRUE;
}

//!	@fn bool db_format4SQL($str)
//!	@brief format string for SQL (add slashes accordingly)
//!	@param $str string to format
//definition of magic_quote config
//	0 = assume magic_quote OFF and always add slashes to values
//	1 = assume magic_quote ON and never add slashes
//	2 = detect magic_quote and act accordingly
function db_format4SQL($str)
{
	global $db_var;
	if(!is_string($str))
	{
		return $str;
	}
	if($db_var['settings']['magic_quote']==1 || ($db_var['settings']['magic_quote']==2 && get_magic_quotes_gpc()))
	{	//if set to not add slashes (1) or set to detect with magic_quote on (2), return string
		return $str;
	}
	return addslashes($str);
}

///////////////////////////////////////////////////////db functions///////////////////////////////////////////////////////////
/**
Core DB functions which includes connect, close connection, make query and fetch data from returned query
Also does error checking and display of errors
**/

//-------------------------------DB connect functions--------------------------------------------------------------------------//
//!	@fn resource db_connect($db)
//!	@brief make connection to database
//!	@param $db database variable array, consisted of [host, port, user, pass, db_name]
function db_connect()
{
	global $db_var;
	global $tiddlyCfg;
	$link = db_connectDB();
	if($link===FALSE)
	{
		db_logerror($db_var['error']['connect'],$db_var['settings']['defaultStop'],$db_var['error']['connect']."(".$db_var['error']['error'].mysql_error().")");
		return FALSE;
	}
	$r = db_selectDB($db_var['db'],$link);
	if($r===FALSE)
	{
		 db_logerror($db_var['error']['selectDB'],$db_var['settings']['defaultStop'],$db_var['error']['selectDB']."(".$db_var['error']['error'].mysql_error().")");
		return FALSE;
	}
	//set to utf-8 communication
	if($tiddlyCfg['pref']['utf8'] == 1)
	{
		if(db_query("SET NAMES 'utf8'")===FALSE)
		{
			db_logerror($db_var['error']['queryErr'],$db_var['settings']['defaultStop']
				,$db_var['error']['queryErr']."(".$db_var['error']['query']."SET NAMES 'utf8'".$db_var['error']['error'].mysql_error().")");
			return FALSE;
		}
	}
	return $link;
}

//!	@fn resource db_connectDB()
//!	@brief connect to DB
function db_connectDB()
{
	global $db_var;
	return mysql_connect($db_var['host'].((isset($db_var['port'])&&strlen($db_var['port'])>0)?":".$db_var['port']:""),$db_var['username'],$db_var['password'],TRUE);
}

//!	@fn resource db_selectDB($db,$link="")
//!	@brief select DB
//!	@param $db database name
//!	@param $link sql connection resource
function db_selectDB($db,$link="")
{
	if(is_string($link) && strlen($link)==0)
	{
		return mysql_select_db($db);
	}
	return mysql_select_db($db,$link);
}

//-----------------------------------------------DB core functions--------------------------------------------------------------------------//
//!	make query to db with statement $sql
//!	$sql = query statement
function db_createDB($db)
{
	return db_query("CREATE DATABASE `".$db."`");
}

//!	make query to db with statement $sql
//!	$sql = query statement
function db_query($sql)
{
	global $db_var;
	global $tiddlyCfg;
	//make query
		debug($sql, "mysql");
		
	//print $sql;
	$SQLR=mysql_query($sql);
	
	if($SQLR===FALSE)
	{
		
		db_logerror($db_var['error']['queryErr'], $db_var['settings']['defaultStop'],
			$db_var['error']['queryErr']."(".$db_var['error']['query'].$sql.$db_var['error']['error'].mysql_error().")");
		return FALSE;
	}
	
	return $SQLR;					//return data in array
}

//!	@fn object db_fetch_object($result)
//!	@brief return first row of a query result as an object
//!	@param $result result returned from sql query
function db_fetch_object($result)
{
	if($result)
	{
		return mysql_fetch_object($result);
	}
	return FALSE;
}

//!	@fn int db_num_rows($result)
//!	@brief number of rows in returned results
//!	@param $result result returned from sql query
function db_num_rows($result)
{
	if($result)
	{
		return mysql_num_rows($result);
	}
	return FALSE;
}

//!	@fn int db_affected_rows($result)
//!	@brief number of rows affected
//!	@param $result result returned from sql query
function db_affected_rows()
{
	return mysql_affected_rows();
}

//!	@fn int db_affected_rows($result)
//!	@brief number of rows affected
//!	@param $result result returned from sql query
function db_insert_id()
{
	return mysql_insert_id();
}

//!	@fn int db_affected_rows($result)
//!	@brief number of rows affected
//!	@param $result result returned from sql query
function db_error()
{
	return mysql_error();
}
?>