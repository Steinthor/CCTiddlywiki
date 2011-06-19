<?php

//////////////////////////////////////////////////////// FUNCTIONS ////////////////////////////////////////////////////////

	//!	@fn array user_create($username="", $group="", $verified=0, $id="")
	//!	@brief create user array, verify, obtain group and privileges
	//!	@param $username username
	//!	@param $group group that the user belongs to, in array form
	//!	@param $verified whether the username and password is correct
	//!	@param $id user id, currently useless
	//!	@param $password password override
	//!	@param $reqHash state if password need to be hashed
	// TODO : REMOVE verified = 1
	function user_create($username="", $group="", $verified=-1, $id="", $password="", $reqHash = 0)
	{
		global $tiddlyCfg;
		$user = array();
		$user['id'] = (strlen($id)>0?(int)$id:"");		//if empty, leave it as empty. otherwise make it as int
	
		if ($tiddlyCfg['deligate_session_management'] ==1)
		{
			$req_url = $tiddlyCfg['pref']['deligate_session_url'].$_REQUEST['sess'];
				if($a = @file_get_contents($req_url))
				{
					if ($a != "")
					{
						debug("login ok  - setting details", "login");
						user_set_session($a, "MY PASSWORD");
						$user['username'] = $a;	
						//no slashes, star and question mark in username
						$user['verified'] = 1;
					}
				}
				
		}else
		{
			//get username from cookie if nothing is passed
			$user['username'] = preg_replace("![/,\\\\,?,*]!","",(strcmp($username,"")==0?user_getUsername():$username));	
			//no slashes, star and question mark in username
			$user['verified'] = (($verified==-1)?user_session_validate():$verified);
		}
		//NOTE: group is always in array
		//FORMAT: $user['group'] = array("group1", "group2");
		$user['group'] = (strcmp($group,"")==0?user_getGroup($user['username'],$user['verified']):$group);
		//privilege for various tags
		//FORMAT: $user['privilege'] = array("tag1"=>"AAAA");
		$user['privilege'] = user_getPrivilege($user['group']);		
	//	var_dump($user['group']);
		return $user;
	}

	function user_reset_session($un,$pw)
	{
		global $tiddlyCfg;
		$odata['session_token']= $pw;
		if($tiddlyCfg['session_expire'])
			$add = $tiddlyCfg['session_expire'];
		else
			$add = 180 * 60; // #HACK - add three hours if we do not know what the session timeout is 
		$ndata['expire']=epochToTiddlyTime(time()+$add);
		db_record_update('login_session',$odata,$ndata);
	}
	
	function user_session_validate()
	{
		//global $user;
		global $tiddlyCfg;
		db_connect_new();
		$pw = cookie_get('sessionToken');
		if ($tiddlyCfg['deligate_session_management'] ==1)
		{
			debug($ccT_msg['debug']['delegatedSessionManagementEnabled'], "login");
			$req_url = $tiddlyCfg['pref']['deligate_session_url'].$_REQUEST['sess'];
			if($a = @file_get_contents($req_url))
			{
				if ($a != "")
				{
					return true;
				}
			}				
		}

		if ($pw && $pw !== "invalid")
		{
			$data_session['session_token'] = $pw;
			$results = db_record_select('login_session', $data_session);			// get array of results		
			debug(count($results)."session(s) exists", "login");
			if (count($results) > 0 )                   //  if the array has 1  session
			{
				//$user['verified'] = 1;	
				if($results[0]['expire'] > epochToTiddlyTime(time())) 
				{
					if($tiddlyCfg['pref']['renew_session_on_each_request']==1)
						$un = user_getUsername();
						user_reset_session($un, $pw);
					return TRUE;
				}
				else 
				{
					debug($ccT_msg['debug']['sessionExpired'], "login");	
					user_logout();
				 	return FALSE; 
				 	//delete the cookies and session record 
				}
			}
			else
			{ 
				user_logout(' 	');
				return FALSE;		
			}
		}
		// no session key or its invalid
		debug("invalid session key", "login");
		return FALSE;
	}
	
	
	function user_set_session($un, $pw)
	{
		global $tiddlyCfg;
		if(isset($ccT_msg['debug']['setSession']))
			debug($ccT_msg['debug']['setSession'], "login");
		if ($tiddlyCfg['users_required_in_db']==1)
		{
			debug($ccT_msg['debug']['userRequiredInDb'], "login");
		 	$data['username'] = $un;
			$login_check = db_record_select($tiddlyCfg['table']['user'], $data);			// get array of results		
		
			if (count($login_check) > 0 ) 
			{
				debug($ccT_msg['debug']['userExistsInDb'], "login");
				//return true;
			}
			else
			{	// user does not exists in the user database. 
				debug($ccT_msg['debug']['userDoesNotExistInDb'], "login");
				return false;
			}     
		}
		$insert_data['user_id'] = $un;
		$expire =time()+$tiddlyCfg['session_expire'];
		// create a date far in the future if session timeout is set to 0
		$a = time();
		$total = $a+$tiddlyCfg['session_expire'];	
		$insert_data['expire'] = epochToTiddlyTime($total); // add expire time to data array for insert	
		if(isset($ccT_msg['debug']['sessionWillExpire']))
			debug($ccT_msg['debug']['sessionWillExpire'].$insert_data['expire'], "login");	
		$insert_data['ip'] = $_SERVER['REMOTE_ADDR'];  // get the ip address
		$insert_data['session_token'] = sha1($un.$_SERVER['REMOTE_ADDR'].$expire); // colect data together and sh1 it so that we have a unique indentifier 
		if ($tiddlyCfg['pref']['delete_other_sessions_on_login']) {
			$del_data['user_id'] =$un;
			db_record_delete('login_session',$del_data);
		}
		cookie_set('txtUserName', $un);
 		cookie_set('sessionToken', $insert_data['session_token']);
		$rs = db_record_insert('login_session',$insert_data);
		if ($rs){
			if(isset($ccT_msg['debug']['sessionAddedToDb']))
				debug($ccT_msg['debug']['sessionAddedToDb'], "login");
			return $insert_data['session_token'];
		}	
	}		

///////////////////////////////////////////////////////////////validate and login (set cookie)//////////////////////////////////////////////////
	//!	@fn bool user_validate($un)
	//!	@brief check username and password f
	//!	@param $un username override
	//!	@param $pw password over
	function user_validate($un, $pw)
	{
		global $tiddlyCfg;
		debug("validating the session with Username/pass:  ".$un, "steps");
			
		// session has not been created, lets try the user/pass on our ldap server. 
		if(isset($tiddlyCfg['pref']['ldap_enabled']))
		{
			if ($tiddlyCfg['pref']['ldap_enabled']==1)
			{
				if (user_ldap_login($un, $pw))	
				{
					return TRUE;
				}
			}
		}
		
		if ($un != '' && $pw != '')
		{
			$data['username'] = $un;
			$data['password'] = $pw;
			$results = db_record_select($tiddlyCfg['table']['prefix'].$tiddlyCfg['table']['user'], $data);			// get array of results		
			if (count($results) > 0 )                   //  if the array has 1 or more acounts 
			{
				$del_data1['expire'] = epochToTiddlyTime(time());
				db_record_delete('login_session', $del_data1, 0,  "<");
				return TRUE;
			}else
			{
				user_logout('Login Failed, please confirm username/password');
			}		
			return FALSE;
		}
	}
	
	
	//!	@fn bool user_login($un="", $pw="", $reqHash=0)
	//!	@brief check username and password and set them to cookies
	//!	@param $un username
	//!	@param $pw password (assume hashed password)
	//!	@param $reqHash if 1, it will hash $pw
	function user_login($un, $pw, $reqHash=0)
	{
		global $tiddlyCfg;
		if(user_validate($un, $pw))
		{
			echo user_set_session($un, $pw);	
			debug("Login - User validated so session should have been set return true", "login");
			// output the session id
			return TRUE;
		}
		else
		{		//if username password pair wrong, clear cookie
			user_logout('Login Failed');
			Header("HTTP/1.0 401 Unauthorized");
			return FALSE;
		}
	}
	
	
	// used by openID to check if the ID provided already exists in the user database. 
	

	///////////////////////////////////////////////////////////////get user info//////////////////////////////////////////////////
	//!	@fn string user_getUsername()
	//!	@brief get username from cookie
	function user_getUsername()
	{
		$t = cookie_get('TiddlyWiki');
		$userGrab = strpos($t, "txtUserName")+13;
		$userEnd = strpos($t, '"', $userGrab)-$userGrab;
		$u = substr($t, $userGrab, $userEnd);
		//$u = cookie_get('txtUserName');
		if( strlen($u)==0 )
		{
			return "YourName";
		}
		return $u;
	}
	
	function user_logout($errorMsg = null)
	{	global $user;
		$user['username'] = 'invalid';
		$data['session_token']= cookie_get('sessionToken');
		db_record_delete('login_session',$data);
		cookie_set('sessionToken', 'invalid');
	    debug("Logout : ".$errorMsg, "steps");
		return TRUE;
	}
	
	//!	@fn array user_getGroup($un)
	//!	@brief get group a user belongs to
	//!	@param $username username required
	//!	@param $verified specify whether the user is verified or not
	function user_getGroup($username, $verified=-1)
	{
		global $tiddlyCfg;
		$verified = ($verified===-1?user_validate($username):(bool)$verified);
		
		if( $verified )
		{
			$group = array("user");		//logged on user default to "user"
			//separate admins from non_admins
		
			if( in_array($username, $tiddlyCfg['group']['admin']) )
			{
				$group[] = "admin";
			}else{
				$group[] = "non_admin";
			}
			//check each group to see if user belongs to them
			//will have duplicate group if belongs to admin
			while( list($gp_name, $gp_member) = each($tiddlyCfg['group']) )
			{
				if( in_array($username, $gp_member) )
				{
					$group[] = $gp_name;
				}
			}		
			return $group;
		}
		
		return array("anonymous");		//non-logged on user belongs to "anonymous" group
	}
	///////////////////////////////////////////////////////////////encode//////////////////////////////////////////////////
	//!	@fn array user_getGroup($un)
	//!	@brief get group a user belongs to
	//!	@param $username username required
	//!	@param $verified specify whether the user is verified or not
	function user_encodePassword($password)
	{
		global $tiddlyCfg;
		//return sha1($password.$tiddlyCfg['pref']['hashSeed']);
		return sha1($password);
	}
	
	///////////////////////////////////////////////////////////////privilege function//////////////////////////////////////////////////
		
	//!	@fn array user_getPrivilege($group)
	//!	@brief get privilege array in the form array(<tag>=> <privilege>)
	//!	@param $group group array, obtained with "user_getGroup" function
	//$tiddlyCfg['privilege']['default']['testtag'] = "AAAA";
	function user_getPrivilege($group)
	{
		global $tiddlyCfg;
		$privilege = array();
		
		foreach($group as $g)		//go through each group and check if privilege is set for the group
		{
			if( isset($tiddlyCfg['privilege'][$g]) )		//put in privilege array if privilege is set for the group
			{
				if( sizeof($privilege)==0 )		//if privilege is empty, just copy over the tag array
				{
					$privilege = $tiddlyCfg['privilege'][$g];
				}else{		//otherwise have to check for duplication of tags and merge
					while( list($k, $v) = each($tiddlyCfg['privilege'][$g]) )
					{
						if( isset($privilege[$k]) )
						{
							$privilege[$k] = user_mergePrivilege($privilege[$k],$v);
						}else{
							$privilege[$k] = $v;
						}
						
					}
				}
			}
		}
		
		return $privilege;
	}
	
	
	// @brief - confirms if a users had admin rights over a workspace - returns true or false. 
	// @param $user - the username that you want to confirm their admin status over a workspace. 
	// @param $workspace - specifies which workspace you wish to check for admin permissions against. 
	function user_isAdmin($user, $workspace)
	{
		global $tiddlyCfg;
		$data['workspace_name'] = $workspace;
		if ($tiddlyCfg['pref']['openid_enabled'] ==1)
		{
			$user = str_replace("http:", "http://", $user);
		}
		$data['username'] = $user;
		$results = db_record_select($tiddlyCfg['table']['admin'], $data);			// get array of results		
		if (count($results) > 0 )                   //  if the user is an admin. 
		{
			return true;
		}
		return false;
	}
	
	//!	@fn string user_mergePrivilege($p1,$p2)
	//!	@brief merge two privileges  i.e. "A"
	//!	@param $p1 privilege 1
	//!	@param $p2 privilege 2
	function user_mergePrivilege($p1,$p2)
	{	
		$s = strlen($p1);		//defined such that it can be used with shorter privilege string
		for( $i=0; $i<$s; $i++ )
		{
			switch($p1[$i])
			{
				case 'D':
					break;
				case 'A':
					if( strcmp($p2[$i],'D')==0 )
					{
						$p1[$i] = 'D';
					}
					break;
				case 'U':
					$p1[$i] = $p2[$i];
					break;
			}
		}
		
		return $p1;
	}
	
	//!	@fn string user_undefinePrivilege($privilege)
	//!	@brief replace undefined privilege (U) to the one in config
	//!	@param $privilege privilege string
	function user_undefinePrivilege($privilege)
	{
		global $tiddlyCfg;
		return str_replace("U",$tiddlyCfg['privilege_misc']['undefined_privilege'],$privilege);
	}
	
	function user_isAdminGroup()
	{
		global $user;
		if(in_array("admin", $user['group']))
			return true;
		else
			return false;
	}
	
	//!	@fn string user_readPrivilege($privilege)
	//!	@brief obtain read privilege from privilege string
	//!	@param $privilege privilege string
	function user_readPrivilege($privilege)
	{
		if(user_isAdminGroup())
			return true;
		if(strcmp($privilege[0], 'A')==0)
			return TRUE;
		return FALSE;
	}
	//!	@fn string user_insertPrivilege($privilege)
	//!	@brief obtain insert privilege from privilege string
	//!	@param $privilege privilege string
	function user_insertPrivilege($privilege)
	{
		if(user_isAdminGroup())
			return true;
		if( strcmp($privilege[1], 'A')==0 )
		{
			return TRUE;
		}
		return FALSE;
	}
	//!	@fn string user_editPrivilege($privilege)
	//!	@brief obtain edit privilege from privilege string
	//!	@param $privilege privilege string
	function user_editPrivilege($privilege)
	{
		if(user_isAdminGroup())
			return true;
		if( strcmp($privilege[2], 'A')==0 )
		{
			return TRUE;
		}
		return FALSE;
	}
	//!	@fn string user_deletePrivilege($privilege)
	//!	@brief obtain delete privilege from privilege string
	//!	@param $privilege privilege string
	function user_deletePrivilege($privilege)
	{
		if(user_isAdminGroup())
			return true;
		if( strcmp($privilege[3], 'A')==0 )
		{
			return TRUE;
		}
		return FALSE;
	}
	
	//!	@fn string user_tiddlerPrivilegeOfUser($user,$tag)
	//!	@brief return privilege of tiddler according to the tag array/string and user privilege array
	//!	@param $user user array
	//!	@param $tag tag string or array
	function user_tiddlerPrivilegeOfUser($user,$tag="")
	{
		global $tiddlyCfg;
		$privilege = $tiddlyCfg['privilege_misc']['default_privilege'];
		$privilegeArr = $user['privilege'];
		$group = $user['group'];
		
		//redefine default privilege
		foreach($group as $g)
		{
			if( isset($tiddlyCfg['privilege_misc']['group_default_privilege'][$g]) )
			{
				$privilege = user_mergePrivilege($privilege,$tiddlyCfg['privilege_misc']['group_default_privilege'][$g]);
			}
		}
		
		//convert tag list to array if required
		if( !is_array($tag) )
		{
			if( strlen($tag)==0 )		//if no tag, return default privilege
			{
				return user_undefinePrivilege($privilege);
			}else
			{
				$tag = tiddler_breakTag($tag);
			}
		}
		
		//if no tag, return default privilege
		if( sizeof($tag)==0 || sizeof($privilegeArr)==0 )
		{
			return ($privilege);
		}
		
		
		//check each tag in user permission, merge if exist
		foreach( $tag as $t )
		{
			if( isset($privilegeArr[$t]) )
			{
				$privilege = user_mergePrivilege($privilege, $privilegeArr[$t]);
			}
		}
		
		//redefine undefine privilege
		return user_undefinePrivilege($privilege);
	}
?>