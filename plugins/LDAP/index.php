<?php
$m = new Plugin('LDAP','0.1','simonmcmanus.com');


// Tiddler Definitions : 
$tiddler['title'] = "LDAP Settings";
$tiddler['body'] = "config.macros.ccLogin.sha1 = false;";
$tiddler['tags'] = "systemConfig";

// LDAP Plugin Definitions

$m->addEvent("preUserInclude", 'LDAP/files/functions.php');
$m->addTiddler($tiddler);

// LDAP Config Settings 
$tiddlyCfg['users_required_in_db'] = 0;  // means users are not required in the db to login/
$tiddlyCfg['pref']['ldap_enabled'] = 1;	
$tiddlyCfg['pref']['ldap_username']	= "balh";
$tiddlyCfg['pref']['ldap_base_dn']	="blah";
$tiddlyCfg['pref']['ldap_password'] = "password";
$tiddlyCfg['pref']['ldap_filter'] = "accountname=";
$tiddlyCfg['pref']['ldap_connection_string'] = "ldap://server.com:389";

//
?>