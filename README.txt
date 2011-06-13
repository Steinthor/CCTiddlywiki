Welcome to ccTiddly 1.8


For information on the latest release please see the following sites : 
* http://www.tiddlywiki.org/wiki/CcTiddly/Releases
* http://groups.google.com/group/ccTiddly

We have not written the install script yet so you will need to create the database manually.

STEPS FOR INSTALLATION : 

1 .. Copy files into folder on your web server 
2 .. create database and run the install.sql file to create the tables.
3 .. edit /includes/config.php  to reflect your settings : 

	$tiddlyCfg['db']['host'] = "127.0.0.1";		//sql host
	$tiddlyCfg['db']['login'] = "root";		//login name
	$tiddlyCfg['db']['pass'] = "";		//login password
	$tiddlyCfg['db']['name'] = "cctw";		//db name

4 .. Then you should be able to access ccTiddly over HTTP. eg : 

http://127.0.0.1/cctiddly

Serverside Plugins (NEW to version 1.8)

By default all the serverside plugins are disabled. To enable a plugin you need edit config.php and REMOVE the plugin name from the $tiddlyCfg['plugins_disabled'] array.  It can be found at aproximately line 19.

$tiddlyCfg['plugins_disabled'] = array(
	"createPackages",
	"lifestream",
	"OpenID",
	"Portlet", 
	"seo",
	"SkinnyTiddlers",
	"WordpressMigration"
);


If you have any questions please contact : 

http://groups.google.com/group/ccTiddly


The SQL creates three users for you. 

1 .. username
2 .. admin

each has a password of password.


Configuring Uploading 

1 .. Create a folder in your root directory called uploads 

2 .. Ensure the folder is owned by the same user that apache is running under. 

3 .. Ensure that the folder can be written by its owner. 



NOTICE ABOUT PLUGINS IN 1.9 


If a plugin calls addRecipe() on a recipe which cannot be found it looks in the plugin directory called importedPlugins and loads the files from there. 


