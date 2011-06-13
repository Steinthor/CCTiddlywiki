<?php

function user_ldap_login($username, $password)
{
	global $tiddlyCfg;
	debug("user_ldap_login", "login");
	if ($password == "")
	{
		debug($ccT_msg['debug']['ldapFailNoPassword'], "login");
		return false;
	}
	$ds = ldap_connect($tiddlyCfg['pref']['ldap_connection_string']); 
	if( !$ds ) 
	{ 
		debug($ccT_msg['debug']['ldapFailNoConnect'], "login");	
		return false;
	} 
 
//Connection made -- bind anonymously and get dn for username. 

$bind_user =$tiddlyCfg['pref']['ldap_username'];
$bind_pass = $tiddlyCfg['pref']['ldap_password'];
$bind = @ldap_bind($ds,$bind_user,$bind_pass); 
//Check to make sure we're bound. 
if( !$bind )  
{ 
	debug($ccT_msg['debug']['ldapFailNoConnect'], "login");	
   return false;
} 
 
$search = ldap_search($ds, $tiddlyCfg['pref']['ldap_base_dn'] ,$tiddlyCfg['pref']['ldap_filter']."$username"); 
 
//Make sure only ONE result was returned -- if not, they might've thrown a * into the username.  Bad user! 
if( @ldap_count_entries($ds,$search) != 1 ) 
{ 
	debug("Login Failed : Only one user was not returned.", "login");	
	return FALSE;
} else
{
}
  $info = ldap_get_entries($ds, $search); 
  
  $user_dn = $info[0]["dn"];
	$userBind = @ldap_bind($ds, $user_dn,$password);
	if(!$userBind){
		debug($ccT_msg['debug']['ldapFailNoConnect'], "login");	
		return false;
	}else{
		debug($ccT_msg['debug']['ldapMakingProgress'], "login");
		return TRUE;
	}
	@ldap_close($ds); 
}


?>
