
merge(config.options, {search:"search.."})


config.theme = {
	contentTitle : ' ssss »',
	contentToolTip : 'aaaa',
	contentTiddler : 'Content »',
	contentTiddlerTooltip : 'click to view TiddlyWiki content'
};
merge(config.macros.ccAbout,{
	buttonBackstageText:"about",
	buttonBackstageTooltip:"Find out more about ccTiddly ",
	stepAboutTitle:"About",
	stepAboutTextStart:"You are running ccTiddly ",
	stepAboutTextEnd:"More info about ccTiddly can be found  at <a  target=new href=http://www.tiddlywiki.org/wiki/CcTiddly>http://www.tiddlywiki.org/wiki/CcTiddly</a><br/><br/>  More information about TiddlyWiki can be found at <a target=new href=http://www.tiddlywiki.com>http://www.tiddlywiki.com</a><br/>"
});

merge(config.macros.ccChangePassword,{
	title:"Change Password", 
	subTitle : "for user ", 
	step1Html: " <label for='old'>Old Password </label><input name='old' type='password'/><br/> <label for='new1'>New Password </label> <input  name='new1' type='password' /><br /><label for='new2'>Repeat Password</label> <input  name='new2' type='password' /> ",   
	buttonChangeText:"Change Password",
	buttonChangeToolTip:"Click to change your password", 
	buttonCancelText:"Cancel",
	buttonCancelToolTip:"Click to cancel",
	noticePasswordsNoMatch : "Your new passwords do not match", 
	noticePasswordWrong : "Your password is incorrect.",
	noticePasswordUpdated : "Your Password has been updated", 
	noticePasswordUpdateFailed : "Your Password was NOT updated." 
});

merge(config.macros.ccAdmin,{
	stepAddTitle:"Add a new Workspace Administrator",
	WizardTitleText:"Workspace Administration.",
	buttonDeleteText:"Delete Users",
	buttonDeleteTooltip:"Click to delete users.",
	buttonAddText:"Add User",
	buttonAddTooltip:"Click to add user.",
	buttonCancelText:"Cancel",
	buttonCalcelTooltip:"Calcel adding user.",
	buttonCreateText:"Make User Admin",
	buttonCreateTooltip:"Click to make user admin.",
	labelWorkspace:"Workspace: ",
	labelUsername:"Username  : ",
	stepErrorTitle:"You need to be an administrator of this workspace.",
	stepErrorText:"Permission Denied to edit workspace : ",
	stepNoAdminTitle:"There are no admins of this workspace.",
	stepManageWorkspaceTitle:"",
	listAdminTemplate: {
	columns: [	
		{name: 'Selected', field: 'Selected', rowName: 'name', type: 'Selector'},
		{name: 'Name', field: 'name', title: "Username", type: 'String'},	
		{name: 'Last Visit', field: 'lastVisit', title: "Last Login", type: 'String'}
	],
	rowClasses: [
		{className: 'lowlight', field: 'lowlight'}
	]}
});
/*
merge(ccTiddlyAutoSave,{
	msgSaved:"Saved ",
	msgError:"There was an error saving "
});
*/

merge(config.macros.ccCreateWorkspace, {
	wizardTitle:"Create Workspace",
	buttonCreateText:"create",
	buttonCreateWorkspaceText:"Create Workspace",
	buttonCreateTooltip:'Create new workspace',
	errorPermissions:"You do not have permissions to create a workspace.  You may need to log in.",
	msgPleaseWait:"Please wait, your workspace is being created.",
	msgWorkspaceAvailable:"Workspace name is available.",
	errorWorkspaceNameInUse:"Workspace name is already in use.",
	stepTitle:"Please enter workspace name",
	stepCreateHtml:"<input class='input' id='workspace_name' name='workspace_name' value='' tabindex='1' /><span></span><input type='hidden' name='workspace_error'></input><h2></h2><input type='hidden' name='workspace_url'></input>"
});

merge(config.macros.ccEditWorkspace,{
	WizardTitleText:"Edit Workspace Permissions",
	stepEditTitle:null,
	stepLabelCreate:'Create',
	stepLabelRead:'Read',
	stepLabelUpdate:'Edit',
	stepLabelDelete:'Delete',
	stepLabelPermission:'',
	stepLabelAnon:'  Anonymous   ',
	stepLabelUser:' Authenticated   ',
	stepLabelAdmin:' Admin  ',
	buttonSubmitCaption:"Update Workspace Permissions",
	buttonSubmitToolTip:"Update workspace permissions",
	button1SubmitCaption:"ok",
	button1SubmitToolTip:"review permissions",
	step2Error:"Error", 
	errorTextPermissionDenied:"You do not have permissions to edit this workspace permissions.  You may need to log in.",
	errorUpdateFailed:"Permissions Not changed"
});

merge(config.macros.ccFile,{
	wizardTitleText:"Manage Files",
	wizardStepText:"Manage files in workspace ",
	buttonDeleteText:"Delete Files",
	buttonDeleteTooltip:"Click to Delete files.",
	buttonUploadText:"Upload File",
	buttonUploadTooltip:"Click to Upload files.",
	buttonCancelText:"Cancel",
	buttonCancelTooltip:"Click to cancel.",
	labelFiles:"Existing Files ",
	errorPermissionDeniedTitle:"Permission Denied",
	errorPermissionDeniedUpload:"You do not have permissions to create a file on this server. ",
	errorPermissionDeniedView:"You do not have permissions to view files in this workspace. ",
	listAdminTemplate: {
	columns: [	
	{name: 'wiki text', field: 'wikiText', title: "", type: 'WikiText'},
	{name: 'Selected', field: 'Selected', rowName: 'name', type: 'Selector'},
	{name: 'Name', field: 'name', title: "File", type: 'WikiText'},
	{name: 'URI', field: 'URI', title: "URI", type: 'WikiText'},
	{name: 'Size', field: 'fileSize', title: "size", type: 'String'}
	],
	rowClasses: [
	{className: 'lowlight', field: 'lowlight'}
	]}
});

merge(config.macros.ccLogin,{
	WizardTitleText:null,
	usernameRequest:"Username",
	passwordRequest:"Password",
	stepLoginTitle:null,
	stepLoginIntroTextHtml:"<label>username</label><input name=username id=username tabindex='1'><br /><label>password</label><input type='password' tabindex='2' name='txtPassword' class='txtPassword'><input   name='password'>",
	stepDoLoginTitle:"Logging you in",
	stepDoLoginIntroText:"we are currently trying to log you in.... ",
	stepForgotPasswordTitle:"Password Request",
	stepForgotPasswordIntroText:"Please contact your system administrator or register for a new account.  <br /><input id='forgottenPassword' type='hidden' name='forgottenPassword'/>",
	stepLogoutTitle:"Logout",
	stepLogoutText:"You are currently logged in as ",
	buttonLogout:"logout",
	buttonLogoutToolTip:"Click here to logout.",
	buttonLogin:"Login",
	buttonlogin:"login",
	buttonLoginToolTip:"Click to Login.",	
	buttonCancel:"Cancel",
	buttonCancelToolTip:"Cancel transaction ",
	buttonForgottenPassword:"Forgotten Password",	
	buttonSendForgottenPassword:"Mail me a New Password",
	buttonSendForgottenPasswordToolTip:"Click here if you have forgotten your password",
	buttonForgottenPasswordToolTip:"Click to be reminded of your password",
	msgNoUsername:"Please enter a username", 
	msgNoPassword:"Please enter a password",
	msgLoginFailed:"Login Failed, please try again. ", 
	configURL:window.url+"/handle/login.php", 
	configUsernameInputName:"cctuser",
	configPasswordInputName:"cctpass",
	configPasswordCookieName:"cctPass", 
	registrationDisabled:"Registration Disabled"
});

merge(config.macros.ccLoginStatus,{
	textDefaultWorkspaceLoggedIn:"Viewing default workspace",
	textViewingWorkspace:"Viewing Workspace : ",
	textLoggedInAs:"Logged in as ",
	status:"status »",
	textNotLoggedIn:"You are not logged in.",
	textAdmin:"You are an Administrator."
});

merge(config.macros.ccOptions, {
	linkManageUsers:"users",
	linkPermissions:"permissions",
	linkFiles:"files",
	linkPassword:"password",
	linkCreate:"create",
	linkOffline:"offline",
	linkStats:"statistics",
	options:"options »"	
});

config.macros.register = {};
merge(config.macros.register,{
	usernameRequest:"username",
	passwordRequest:"password",
	passwordConfirmationRequest:"confirm password",
	emailRequest:"email",
	stepRegisterTitle:"Register for an account.",
	stepRegisterIntroText:"Hi, please register below.... ",
	stepRegisterHtml:"<label> username</label><input class='input' id='reg_username' name='reg_username' tabindex='1'/><span></span><input type='hidden'  name='username_error'></input><br /><label>email</label><input class='input' name=reg_mail id='reg_mail' tabindex='2'/><span> </span><input type='hidden' name='mail_error'></input><br/><label>password</label><input type='password' class='input' id='password1' name='reg_password1' tabindex='3'/><span> </span><input type='hidden'  name='pass1_error'></input><br/><label>confirm password</label><input type='password' class='input' id='password2' name='reg_password2' tabindex='4'/><span> </span><input type='hidden'  name='pass2_error'></input>",
	buttonCancel:"Cancel",
	buttonCancelToolTip:"Cancel transaction ",
	buttonRegister:"Register",	
	buttonRegisterToolTip:"click to register",
	msgCreatingAccount:"Attempting to create the account for you.", 
	msgNoUsername:"No username entered", 
	msgEmailOk:"Email address is OK.",
	msgNoPassword:"no password entered.",
	msgDifferentPasswords:"Your Passwords do not match.",
	msgUsernameTaken:"The username requested has been taken.",
	msgUsernameAvailable:"The username is available.",
	step2Title:"",
	step2Html:"Please wait while we create you an account...",
	errorRegisterTitle:"Error",
	errorRegister:"User not created, please try again with a different username."
});

merge(config.macros.ccStats,{
	graph24HourTitle:"Last 24 hours",
	graph24HourDesc:"The number of views of this workspace in the past 24 hours",
	graph20MinsTitle:"Last 20 Minutes",
	graph20MinsDesc:"The number of views of this workspace in the last 20 minutes",
	graph7DaysTitle:"Last 7 days",
	graph7DaysDesc:"The number of views of this workspace in the last 7 days.",
	graph5MonthsTitle:"Last 5 months",
	graph5MonthsDesc:"The number of views of this workspace in the past 30 days.",
	errorPermissionDenied:"Permissions Denied to data for %0 You need to be an administrator on the %1 workspace.",
	stepTitle:"Workspace Statistics"
});


// GENERAL NON CCT CONTENT 

config.theme = {
	contentTitle:"content »",
	contentToolTip : "View the TiddlyWiki tabs",
	help : "Help"
};

merge(config.macros.importTiddlers, {
	wizardTitle: "Import tiddlers",
	step1Title: "Step 1: Locate the server or TiddlyWiki file",
	step1Html: "Specify the type of the server: <select name='selTypes'><option value=''>Choose...</option></select><br>Enter the URL here: <input type='text' size=50 name='txtPath'><br><input type='hidden' size=50 name='txtBrowse'><br><hr>...or select a pre-defined feed: <select name='selFeeds'><option value=''>Choose...</option></select>"
});

merge(config.optionsDesc,{
	txtUserName: "",
	chkRegExpSearch: "Enable regular expressions for searches",
	chkCaseSensitiveSearch: "Case-sensitive searching",
	chkIncrementalSearch: "Incremental key-by-key searching",
	chkAnimate: "Enable animations",
	chkSaveBackups: "",
	chkAutoSave: "",
	txtTheme: "Change the TiddlyWiki theme being used",
	chkGenerateAnRssFeed: "",
	chkSaveEmptyTemplate: "",
	chkOpenInNewWindow: "Open external links in a new window",
	chkToggleLinks: "Clicking on links to open tiddlers causes them to close",
	chkHttpReadOnly: "",
	chkForceMinorUpdate: "",
	chkConfirmDelete: "Require confirmation before deleting tiddlers",
	chkInsertTabs: "Use the tab key to insert tab characters instead of moving between fields",
	txtBackupFolder: "",
	txtMaxEditRows: "Maximum number of rows in edit boxes",
	txtFileSystemCharSet: "Default character set for saving changes (Firefox/Mozilla only)"});

merge(config.macros.options,{
	wizardTitle: "Change Settings",
	step1Title: "",
	step1Html: '<input type="hidden" name="markList"></input><br><input type="hidden" checked="false" name="chkUnknown"></input>These options are saved in a cookie.'
});

merge(config.macros.options,{
	wizardTitle:"Advanced settings",
	step1Title:null,
		unknownDescription: "//(unknown)//",
	listViewTemplate: {
		columns: [
			{name: 'Option', field: 'option', title: "", type: 'String'},
			{name: 'Description', field: 'description', title: "", type: 'WikiText'}
			],
			rowClasses: [
						{className: 'lowlight', field: 'lowlight'}
						]
}
});
