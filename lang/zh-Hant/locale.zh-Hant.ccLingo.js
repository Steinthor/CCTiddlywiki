/***
|''Name''|ccLingo.zh-Hant|
|''Description:''||
|''Contributors''|BramChen|
|''Source:''| |
|''CodeRepository:''| |
|''Version:''|1.7.5 |
|''Date:''|Nov 14, 2008|
|''Comments:''|Please make comments at http://groups.google.co.uk/group/TiddlyWiki-zh |
|''License:''|[[Creative Commons Attribution-ShareAlike 3.0 License|http://creativecommons.org/licenses/by-sa/3.0/]] |
|''~CoreVersion:''|2.4.1|
***/
/***
!config.macros.ccAbout
***/
//{{{
merge(config.macros.ccAbout,{
	buttonBackstageText:"關於",
	buttonBackstageTooltip:"關於 ccTiddly",
	stepAboutTitle:"關於",
	stepAboutTextStart:"您現在正在使用 ccTiddly ",
	stepAboutTextEnd:"關於 ccTiddly 的資訊詳見 <a  target=new href=http://www.tiddlywiki.org/wiki/CcTiddly>http://www.tiddlywiki.org/wiki/CcTiddly</a><br/><br/>  關於 TiddlyWiki 的資訊詳見 <a target=new href=http://www.tiddlywiki.com>http://www.tiddlywiki.com</a><br/>"
});
merge(config.tasks,{about:{text: config.macros.ccAbout.buttonBackstageText,tooltip: config.macros.ccAbout.buttonBackstageTooltip,content: '<<ccAbout>>'}});
//}}}
/***
!config.macros.ccChangePassword
***/
//{{{
merge(config.macros.ccChangePassword,{
	title:"變更密碼", 
	subTitle : "用戶 ", 
	step1Html: " <label for='old'>舊密碼 </label><input name='old' type='password'/><br/> <label for='new1'>新密碼 </label> <input  name='new1' type='password' /><br /><label for='new2'>確認密碼</label> <input  name='new2' type='password' /> ",   
	buttonChangeText:"更改密碼",
	buttonChangeToolTip:"更改密碼", 
	buttonCancelText:"取消",
	buttonCancelToolTip:"取消更改密碼",
	noticePasswordsNoMatch : "新密碼不相符", 
	noticePasswordWrong : "密碼不正確",
	noticePasswordUpdated : "密碼已變更", 
	noticePasswordUpdateFailed : "密碼未變更" 
});
//}}}
/***
!config.macros.ccAdmin
***/
//{{{
	merge(config.macros.ccAdmin,{
	stepAddTitle:"新增工作區管理者",
	WizardTitleText:"工作區管理",
	buttonDeleteText:"刪除",
	buttonDeleteTooltip:"刪除管理者",
	buttonAddText:"新增",
	buttonAddTooltip:"新增管理者",
	buttonCancelText:"取消",
	buttonCalcelTooltip:"取消新增管理者",
	buttonCreateText:"新增",
	buttonCreateTooltip:"新增工作區管理者",
	labelWorkspace:"工作區：",
	labelUsername:"用戶：",
	stepErrorTitle:"您必須是此工作區管理者",
	stepErrorText:"無權限修改工作區：",
	stepNoAdminTitle:"此工作區尚無管理者",
	stepManageWorkspaceTitle:"",
	listAdminTemplate: {
	columns: [	
		{name: 'Selected', field: 'Selected', rowName: 'name', type: 'Selector'},
		{name: 'Name', field: 'name', title: "用戶", type: 'String'},
		{name: 'Last Visit', field: 'lastVisit', title: "最近登入時間", type: 'String'}
	],
	rowClasses: [
		{className: 'lowlight', field: 'lowlight'}
	]}
});
//}}}
/***
!ccTiddlyAutoSave
***/
//{{{
merge(ccTiddlyAutoSave, {
	msgSaved:"儲存完成： ",
	msgError:"儲存時，發生錯誤 "
});
//}}}
/***
!config.macros.ccCreateWorkspace
***/
//{{{
merge(config.macros.ccCreateWorkspace, {
	wizardTitle:"新增工作區",
	buttonCreateText:"建立",
	buttonCreateWorkspaceText:"建立新工作區",
	buttonCreateTooltip:'建立新工作區',
	errorPermissions:"沒有建立工區的權限， 登入後再試。",
	msgPleaseWait:"工作區建立中，請稍後",
	msgWorkspaceAvailable:"工作區有效",
	errorWorkspaceNameInUse:"工作區已存在",
	stepTitle:"請輸入工作區名稱",
	stepCreateHtml:"<input class='input' id='workspace_name' name='workspace_name' value='"+workspace+"' tabindex='1' /><span></span><input type='hidden' name='workspace_error'></input><h2></h2><input type='hidden' name='workspace_url'></input>"
}); 
//}}}
/***
!config.macros.ccEditWorkspace
***/
//{{{
merge(config.macros.ccEditWorkspace,{
	WizardTitleText:"修改工作區權限",
	stepEditTitle:null,
	stepLabelCreate:'建立',
	stepLabelRead:'讀取',
	stepLabelUpdate:'修改',
	stepLabelDelete:'刪除',
	stepLabelPermission:'',
	stepLabelAnon:'  訪客   ',
	stepLabelUser:' 一般用戶   ',
	stepLabelAdmin:' 管理者  ',
	buttonSubmitCaption:"更新",
	buttonSubmitToolTip:"更新工作區權限",
	button1SubmitCaption:"完成",
	button1SubmitToolTip:"重新檢視工作區權限",
	step2Error:"發生錯誤", 
	errorTextPermissionDenied:"您沒有修改工區的權限，請登入後再試",
	errorUpdateFailed:"權限未更動"
	});
//}}}
/***
!config.macros.ccFile
***/
//{{{
merge(config.macros.ccFile,{
	wizardTitleText:"檔案管理",
	wizardStepText:"管理工作區所屬檔案",
	buttonDeleteText:"刪除",
	buttonDeleteTooltip:"刪除檔案",
	buttonUploadText:"上傳",
	buttonUploadTooltip:"上傳檔案",
	buttonCancelText:"取消",
	buttonCancelTooltip:"取消上傳檔案",
	labelFiles:"檔案列表 ",
	errorPermissionDeniedTitle:"未被授權",
	errorPermissionDeniedUpload:"您沒有權限於此主機上建立檔案",
	errorPermissionDeniedView:"您沒有權限檢視此工作區的檔案",
	listAdminTemplate: {
	columns: [	
	{name: 'wiki text', field: 'wikiText', title: "", type: 'WikiText'},
	{name: 'Selected', field: 'Selected', rowName: 'name', type: 'Selector'},
	{name: 'Name', field: 'name', title: "檔名", type: 'WikiText'},
	{name: 'Size', field: 'fileSize', title: "大小", type: 'String'}
	],
	rowClasses: [
	{className: 'lowlight', field: 'lowlight'}
	]}
});
//}}}
/***
!config.macros.ccLogin
***/
//{{{
merge(config.macros.ccLogin,{
	WizardTitleText:"登入系統",
	usernameRequest:"用戶",
	passwordRequest:"密碼",
	stepLoginTitle:null,
	stepLoginIntroTextHtml:"<label>用戶</label><input name=username id=username tabindex='1'><br /><label>密碼</label><input type='password' tabindex='2' class='txtPassword'><input   name='password'>",
	stepDoLoginTitle:"登入用戶為",
	stepDoLoginIntroText:"登入中 .... ",
	stepForgotPasswordTitle:"查詢密碼",
	stepForgotPasswordIntroText:"請與系統管理者聯絡，或重新註冊一個新的帳號。  <br /><input id='forgottenPassword' type='hidden' name='forgottenPassword'/>",
	stepLogoutTitle:"登出",
	stepLogoutText:"您目前登入的用戶名稱為 ",
	buttonLogout:"登出",
	buttonLogoutToolTip:"點擊此處登出系統",
	buttonLogin:"登入",
	buttonlogin:"登入",
	buttonLoginToolTip:"點擊此處登入系統",
	buttonCancel:"取消",
	buttonCancelToolTip:"取消作業 ",
	buttonForgottenPassword:"忘記密碼",
	buttonSendForgottenPassword:"寄給我一組新的密碼",
	buttonSendForgottenPasswordToolTip:"若您已忘記密碼，請點擊此處",
	buttonForgottenPasswordToolTip:"點擊此處取得密碼提示",
	msgNoUsername:"請輸入用戶名稱", 
	msgNoPassword:"請輸入密碼",
	msgLoginFailed:"登入錯誤，請重新登入", 
	configURL:url+"/handle/login.php", 
	configUsernameInputName:"cctuser",
	configPasswordInputName:"cctpass",
	configPasswordCookieName:"cctPass"
});

//}}}
/***
!config.macros.ccLoginStatus
***/
//{{{
merge(config.macros.ccLoginStatus,{
	textDefaultWorkspaceLoggedIn:"您正在瀏覽預設工作區",
	textViewingWorkspace:"您正在瀏覽工作區：",
	textLoggedInAs:"登入用戶為：",
	textNotLoggedIn:"您尚未登入",
	textAdmin:"您是本工作區的管理者之一"
});
//}}}
/***
!config.macros.ccOptions
***/
//{{{
merge(config.macros.ccOptions, {
	linkManageUsers:"用戶管理",
	linkPermissions:"權限管理",
	linkFiles:"檔案管理",
	linkCreate:"新增工作區",
	linkOffline:"離線檢視",
	linkPassword:"變更密碼",
	linkStats:"流量統計"
});
//}}}
/***
!config.macros.register
***/
//{{{
merge(config.macros.register,{
	usernameRequest:"用戶",
	passwordRequest:"密碼",
	passwordConfirmationRequest:"確認密碼",
	emailRequest:"電子郵件",
	stepRegisterTitle:"註冊用戶.",
	stepRegisterIntroText:"嗨，歡迎註冊 .... ",
	stepRegisterHtml:"<label>用戶</label><input class='input' id='reg_username' name='reg_username' tabindex='1'/><span></span><input type='hidden'  name='username_error'></input><br /><label>電子郵件</label><input class='input' name=reg_mail id='reg_mail' tabindex='2'/><span> </span><input type='hidden' name='mail_error'></input><br/><label>密碼</label><input type='password' class='input' id='password1' name='reg_password1' tabindex='3'/><span> </span><input type='hidden'  name='pass1_error'></input><br/><label>確認密碼</label><input type='password' class='input' id='password2' name='reg_password2' tabindex='4'/><span> </span><input type='hidden'  name='pass2_error'></input>",
	buttonCancel:"取消",
	buttonCancelToolTip:"取消註冊作業",
	buttonRegister:"註冊",	
	buttonRegisterToolTip:"點擊此處註冊",
	msgCreatingAccount:"即將為您建立用戶", 
	msgNoUsername:"未輸入用戶名稱", 
	msgEmailOk:"電子郵件有效",
	msgNoPassword:"未輸入密碼",
	msgDifferentPasswords:"密碼不符合",
	msgUsernameTaken:"此用戶名稱已註冊",
	msgUsernameAvailable:"用戶名稱有效",
	step2Title:"",
	step2Html:"建立用戶中，請稍後 ...",
	errorRegisterTitle:"發生錯誤",
	errorRegister:"未建立用戶，請用不同帳號重新註冊。"
});
//}}}
/***
!config.macros.ccStats
***/
//{{{
merge(config.macros.ccStats, {
	graph24HourTitle:"最近 24 小時",
	graph24HourDesc:"最近 24 小時內，本工作區的點閱數。",
	graph20MinsTitle:"最近 20 分",
	graph20MinsDesc:"最近 20 分鐘內，本工作區的點閱數。",
	graph7DaysTitle:"最近 7 天",
	graph7DaysDesc:"最近 7 天內，本工作區的點閱數。",
	graph5MonthsTitle:"最近 5 個月",
	graph5MonthsDesc:"最近 5 個月內，本工作區的點閱數。",
	errorPermissionDenied:"%0 無權檢視，您必須是工區 %1 的管理者",
	stepTitle:"工作區統計"
});
//}}}
/***
!ccTiddlyAdaptor
***/
//{{{
merge(config.commands.saveTiddlerHosted1, config.commands.saveTiddler);

merge(config.commands.revisions,{
	text: "修訂版本",
	tooltip: "檢視此文之其他修訂版本",
	loading: "載入中...",
	done: "已取得修訂版本",
	revisionTooltip: "檢視此修訂版",
	popupNone: "無修訂版本",
	revisionTemplate: "%0 - 版本:%1，修訂者:%2",
	dateFormat:"YYYY年0mm月0DD日 0hh:0mm"
	});

merge(config.commands.deleteTiddlerHosted,{
	text: "刪除",
	tooltip: "刪除文章",
	warning: "確定刪除 '%0'?",
	hideReadOnly: true,
	done: "已刪除： "
	});
//}}}