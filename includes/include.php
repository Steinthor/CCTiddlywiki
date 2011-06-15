<?php 

/*
if($_REQUEST['translation']) 
{
	echo tiddler_outputJsFile("lang/".$_REQUEST['translation']."/ccTransLocale.".$_REQUEST['translation'].".ccLingo.js", getcwd()."/");
	echo tiddler_outputJsFile("lang/".$_REQUEST['translation']."/001_locale.".$_REQUEST['translation'].".js", getcwd()."/");
}

*/


include_once($cct_base."includes/006_ccAssignments.php");

if (isset($_REQUEST["standalone"]) && $_REQUEST["standalone"]==1) {
       tiddler_outputOffline();
} else {
    echo tiddler_outputFolder("lang/".$tiddlyCfg['pref']['language'], $cct_base);
}

if( sizeof($tiddlers)>0 )
{
	foreach($tiddlers as $t)
	{
		if(isset($pluginsLoader->events['preOutputTiddler']) )
		{
			foreach ($pluginsLoader->events['preOutputTiddler'] as $event)
			{
				if(is_file($event)) {
					include($event);
				}	
			}
		}
		tiddler_outputDIV($t);
	}
}
if (isset($_REQUEST["standalone"]) && $_REQUEST["standalone"]==1)
{
?>
// OFF LINE TIDDLERS 
<div title='ccAdaptorSaveLocal' modifier='ccTiddly' tags='systemConfig excludeLists excludeSearch ccTiddly'>
<pre>
	
//config.macros.saveChanges.handler=function(place,macroName,params,wikifier,paramString,tiddler){
//	if(isLoggedIn()){
//		wikify("[[sync]]", place);
//	}
//}	
		
config.backstageTasks.remove("upgrade");
config.macros.ccLogin={};
config.macros.ccLogin.handler = function() {};
window.readOnly = false;
window.saveChanges = function(){};
if (config.options.txtTheme == "")
config.options.txtTheme = '<?php echo $tiddlyCfg['txtTheme'];?>';
config.options.chkUsePreForStorage=true;
if (config.options.txtTheme == "")
config.options.txtTheme = '<?php echo $tiddlyCfg['txtTheme'];?>';
config.options.chkAutoSave = true;
window.offline = true;
config.defaultCustomFields = {"server.host":"http://127.0.0.1", "server.type":"cctiddly", "server.workspace":"<?php echo $_REQUEST['workspace']?>"};
config.macros.ccOptions={};	
config.macros.ccOptions.handler=function(place,macroName,params,wikifier,paramString,tiddler){};
</pre>
</div>
<div title="loginStatus">
<pre>
You are viewing the file in offline mode.

To update your changes please log into ccTiddly in a seperate window and then press the sync button.

[[sync]] 
</pre>
</div>
<div title="sync" tags='wizard'>
<pre>
&lt;&lt;sync&gt;&gt;
</pre>
</div>
<?php
}
?>
