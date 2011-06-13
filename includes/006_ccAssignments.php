<div title="006_ccAssignments" modifier="ccTiddly" tags="systemConfig ccTiddly excludeSearch excludeLists">
<pre>
/*{{{*/

// may be required for mikes comments plugi
//window.saveChanges = function(){};

setStylesheet(
	"label {width:8em; float:left; text-align:right; width:9em; font-size:1.1em; padding:3px;  height:1.5em: top:-20px; margin: 0px -2px 0 0;}"+ 
//	"div.wizardFooter {padding-left:0em}"+ 
	"div.wizardStep > input {display:fixed; padding:3px; margin-bottom:5px; margin-top:0px; margin-right:0px}",
'labelStyles');




config.backstageTasks.remove("upgrade");
config.backstageTasks.remove("save");
config.backstageTasks.remove("sync");


//  ccAutoSave config//

config.options.chkAutoSave = true;

if(!config.extensions) { config.extensions = {}; } //# obsolete from v2.4.2
config.extensions.ServerSideSavingPlugin = {
	adaptor: config.adaptors.cctiddly
};


window.ccTiddlyVersion = '<?php echo $tiddlyCfg['version'];?>';
window.workspacePermission= {};
window.url = "<?php echo getURL();?>";
<?php
// hack to add a forward slash to the url if not the base dir.
if($tiddlyCfg['pref']['base_folder']!="/")
{
?>
window.url= '<?php echo getURL();?>/';
<?php
}
?>
window.workspace = "<?php echo $tiddlyCfg['workspace_name'];?>";
<?php 
if ($tiddlyCfg['workspace_name'] == ""){
?>
window.fullUrl = window.url;	
<?php
} elseif ($tiddlyCfg['use_mod_rewrite'] == 1){ 
?>
window.fullUrl = window.url+window.workspace;
<?php
}else{
?>
window.fullUrl = window.url+"?workspace="+window.workspace;
<?php
}
if($tiddlyCfg['use_mod_rewrite'] == 1)
{
?>
window.useModRewrite = 1;
<?php
}


if($tiddlyCfg['can_create_account']==1)
{
?>
window.canRegister = 1;
<?php
}

if($tiddlyCfg['show_register_on_login']==1)
{
?>
window.displayRegisterOnLogin  = 1;
<?php
}
?>
if (config.options.txtTheme == "")
	config.options.txtTheme = '<?php echo $tiddlyCfg['txtTheme'];?>';
<?php
if(isset($error404) && $error404 == true)
{
?>
// Workspace does not exist.
var titleTiddler = store.createTiddler('SiteTitle');
titleTiddler.text = "'<?php echo $tiddlyCfg['workspace_name'];?>' does not exists";
var subTitleTiddler = store.createTiddler('SiteSubtitle');
subTitleTiddler.text = 'The workspace you requested does not exist.';
var subTitleTiddler = store.createTiddler('MainMenu');
subTitleTiddler.text = '';	
config.shadowTiddlers.DefaultTiddlers = 'CreateWorkspace';
refreshDisplay();
<?php
}

if ($user['verified'] && $workspace_create == "A" &&  $tiddlyCfg['allow_workspace_creation'] ==1)
{
	echo "workspacePermission.create = 1;\n";
} 

if ($user['verified'] && user_isAdmin($user['username'], $tiddlyCfg['workspace_name']))
{
	echo "workspacePermission.upload = 1;";
	echo "workspacePermission.owner = 1;";
} else if ($tiddlyCfg['only_workspace_admin_can_upload'] != 1)
{
	echo "workspacePermission.upload = 1;";
}

if ($user['verified'] && $_REQUEST['standalone']!=1)
{
?>
window.loggedIn ="1";
<?php
}
$anonPerm  = stringToPerm($tiddlyCfg['default_anonymous_perm']);
$userPerm  = stringToPerm($tiddlyCfg['default_user_perm']);	
?>
workspacePermission.anonC = <?php echo permToBinary($anonPerm['create']); ?> ;
workspacePermission.anonR = <?php echo permToBinary($anonPerm['read']); ?>; 
workspacePermission.anonU = <?php echo permToBinary($anonPerm['update']); ?>;
workspacePermission.anonD = <?php echo permToBinary($anonPerm['delete']); ?>;

workspacePermission.userC = <?php echo permToBinary($userPerm['create']); ?> ;
workspacePermission.userR = <?php echo permToBinary($userPerm['read']); ?>; 
workspacePermission.userU = <?php echo permToBinary($userPerm['update']); ?>;
workspacePermission.userD = <?php echo permToBinary($userPerm['delete']); ?>;
workspacePermission.canCreateWorkspace = <?php echo $tiddlyCfg['create_workspace']; ?>;

<?php 
if ($workspace_create == "D")
{
// REMOVE "new tiddler" and "new Journal link"
// SHOW LOGIN TIDDLER
?>
// hide new journal
config.macros.newJournal.handler=function(place,macroName,params,wikifier,paramString,tiddler){};
// hide new tiddler 
config.macros.newTiddler.handler = function(place,macroName,params,wikifier,paramString,tiddler){};
<?php
} 
?>

window.workspace_delete = "<?php echo $workspace_delete;?>";
window.workspace_udate = "<?php echo $workspace_udate;?>";

var serverside={
	url:"<?php echo getURL();?>",		//server url, for use in local TW or TW hosted elsewhere
	workspace:"<?php echo $tiddlyCfg['workspace_name'];?>",
	queryString:"<?php echo $_SERVER['QUERY_STRING'];?>",
	debug:<?php print $tiddlyCfg['developing'] ?>,		//debug mode, display alert box for each action
	passwordTime:0,		//defines how long password variable store in cookie. 0 = indefinite
	messageDuration:5000,				//displayMessage autoclose duration (in milliseconds), 0=leave open
	loggedIn:<?php echo  isset($usr_val)?user_session_validate():0;?>,
	can_create_account:"<?php echo $tiddlyCfg['can_create_account'];?>",
	openId:"<?php echo $tiddlyCfg['pref']['openid_enabled']; ?>"
};

config.defaultCustomFields = {"server.host":window.url, "server.type":"cctiddly", "server.workspace":window.workspace};
config.shadowTiddlers.OptionsPanel = "[[help|Help]] &lt;br /&gt;[[settings|AdvancedOptions]]&lt;br /&gt;&lt;&lt;ccOptions&gt;&gt;";

readOnly =false;
config.options.chkHttpReadOnly = false;		//make it HTTP writable by default
config.options.chkSaveBackups = false;		//disable save backup
//config.options.chkAutoSave = true;			//disable autosave
config.options.chkUsePreForStorage = false;

/*}}}*/
</pre>
</div>
