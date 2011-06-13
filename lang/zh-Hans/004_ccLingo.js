/***
|''Name''|ccLingo.zh-Hans|
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
	
	
merge(config.options, {search:"查找..."})

config.theme = {
	contentTitle : '  状态 »',
	contentToolTip : '依登入帐号而异',
	contentTiddler : '内容 »',
	contentTiddlerTooltip : '检视归档页签'
};

merge(config.macros.ccAbout,{
	buttonBackstageText:"关于",
	buttonBackstageTooltip:"关于 ccTiddly",
	stepAboutTitle:"关于",
	stepAboutTextStart:"您现在正在使用 ccTiddly ",
	stepAboutTextEnd:"关于 ccTiddly 的资讯详见 <a  target=new href=http://www.tiddlywiki.org/wiki/CcTiddly>http://www.tiddlywiki.org/wiki/CcTiddly</a><br/><br/>  关于 TiddlyWiki 的资讯详见 <a target=new href=http://www.tiddlywiki.com>http://www.tiddlywiki.com</a><br/>"
});

merge(config.tasks,{about:{text: config.macros.ccAbout.buttonBackstageText,tooltip: config.macros.ccAbout.buttonBackstageTooltip,content: '<<ccAbout>>'}});
//}}}
/***
!config.macros.ccChangePassword
***/
//{{{
merge(config.macros.ccChangePassword,{
	title:"变更口令", 
	subTitle : "用户 ", 
	step1Html: " <label for='old'>旧口令 </label><input name='old' type='password'/><br/> <label for='new1'>新口令 </label> <input  name='new1' type='password' /><br /><label for='new2'>确认口令</label> <input  name='new2' type='password' /> ",   
	buttonChangeText:"更改口令",
	buttonChangeToolTip:"更改口令", 
	buttonCancelText:"取消",
	buttonCancelToolTip:"取消更改口令",
	noticePasswordsNoMatch : "新口令不相符", 
	noticePasswordWrong : "口令不正确",
	noticePasswordUpdated : "口令已变更", 
	noticePasswordUpdateFailed : "口令未变更" 
});
//}}}
/***
!config.macros.ccAdmin
***/
//{{{
	merge(config.macros.ccAdmin,{
	stepAddTitle:"添加工作区管理者",
	WizardTitleText:"工作区管理",
	buttonDeleteText:"删除",
	buttonDeleteTooltip:"删除管理者",
	buttonAddText:"添加",
	buttonAddTooltip:"添加管理者",
	buttonCancelText:"取消",
	buttonCalcelTooltip:"取消添加管理者",
	buttonCreateText:"添加",
	buttonCreateTooltip:"添加工作区管理者",
	labelWorkspace:"工作区：",
	labelUsername:"用户：",
	stepErrorTitle:"您必须是此工作区管理者",
	stepErrorText:"无权限修改工作区：",
	stepNoAdminTitle:"此工作区尚无管理者",
	stepManageWorkspaceTitle:"",
	listAdminTemplate: {
	columns: [	
		{name: 'Selected', field: 'Selected', rowName: 'name', type: 'Selector'},
		{name: 'Name', field: 'name', title: "用户", type: 'String'},
		{name: 'Last Visit', field: 'lastVisit', title: "最近登入时间", type: 'String'}
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
	msgSaved:"保存完成： ",
	msgError:"保存时，发生错误 "
});
//}}}
/***
!config.macros.ccCreateWorkspace
***/
//{{{
merge(config.macros.ccCreateWorkspace, {
	wizardTitle:"创建工作区",
	buttonCreateText:"创建",
	buttonCreateWorkspaceText:"创建新工作区",
	buttonCreateTooltip:'创建新工作区',
	errorPermissions:"没有创建工区的权限， 登入后再试。",
	msgPleaseWait:"工作区创建中，请稍后",
	msgWorkspaceAvailable:"工作区有效",
	errorWorkspaceNameInUse:"工作区已存在",
	stepTitle:"请输入工作区名称",
	stepCreateHtml:"<input class='input' id='workspace_name' name='workspace_name' value='"+window.workspace+"' tabindex='1' /><span></span><input type='hidden' name='workspace_error'></input><h2></h2><input type='hidden' name='workspace_url'></input>"
});
//}}}
/***
!config.macros.ccEditWorkspace
***/
//{{{
merge(config.macros.ccEditWorkspace,{
	WizardTitleText:"修改工作区权限",
	stepEditTitle:null,
	stepLabelCreate:'创建',
	stepLabelRead:'读取',
	stepLabelUpdate:'修改',
	stepLabelDelete:'删除',
	stepLabelPermission:'',
	stepLabelAnon:'  访客   ',
	stepLabelUser:' 一般用户   ',
	stepLabelAdmin:' 管理者  ',
	buttonSubmitCaption:"更新",
	buttonSubmitToolTip:"更新工作区权限",
	button1SubmitCaption:"完成",
	button1SubmitToolTip:"重新检视工作区权限",
	step2Error:"发生错误", 
	errorTextPermissionDenied:"您没有修改工区的权限，请登入后再试",
	errorUpdateFailed:"权限未更动"
	});
//}}}
/***
!config.macros.ccFile
***/
//{{{
merge(config.macros.ccFile,{
	wizardTitleText:"文件管理",
	wizardStepText:"管理工作区所属文件",
	buttonDeleteText:"删除",
	buttonDeleteTooltip:"删除文件",
	buttonUploadText:"上传",
	buttonUploadTooltip:"上传文件",
	buttonCancelText:"取消",
	buttonCancelTooltip:"取消上传文件",
	labelFiles:"文件列表 ",
	errorPermissionDeniedTitle:"未被授权",
	errorPermissionDeniedUpload:"您没有权限于此主机上创建文件",
	errorPermissionDeniedView:"您没有权限检视此工作区的文件",
	listAdminTemplate: {
	columns: [	
	{name: 'wiki text', field: 'wikiText', title: "", type: 'WikiText'},
	{name: 'Selected', field: 'Selected', rowName: 'name', type: 'Selector'},
	{name: 'Name', field: 'name', title: "文件", type: 'WikiText'},
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
	WizardTitleText:"登入系统",
	usernameRequest:"用户",
	passwordRequest:"口令",
	stepLoginTitle:null,
	stepLoginIntroTextHtml:"<label>用户</label><input name=username id=username tabindex='1'><br /><label>口令</label><input type='password' tabindex='2' class='txtPassword'><input   name='password'>",
	stepDoLoginTitle:"登入用户为",
	stepDoLoginIntroText:"登入中 .... ",
	stepForgotPasswordTitle:"查询口令",
	stepForgotPasswordIntroText:"请与系统管理者联络，或重新注册一个新的帐号。  <br /><input id='forgottenPassword' type='hidden' name='forgottenPassword'/>",
	stepLogoutTitle:"登出",
	stepLogoutText:"您目前登入的用户名称为 ",
	buttonLogout:"登出",
	buttonLogoutToolTip:"点击此处登出系统",
	buttonLogin:"登入",
	buttonlogin:"登入",
	buttonLoginToolTip:"点击此处登入系统",
	buttonCancel:"取消",
	buttonCancelToolTip:"取消作业 ",
	buttonForgottenPassword:"忘记口令",
	buttonSendForgottenPassword:"寄给我一组新的口令",
	buttonSendForgottenPasswordToolTip:"若您已忘记口令，请点击此处",
	buttonForgottenPasswordToolTip:"点击此处取得口令提示",
	msgNoUsername:"请输入用户名称", 
	msgNoPassword:"请输入口令",
	msgLoginFailed:"登入错误，请重新登入", 
	configURL:window.url+"/handle/login.php", 
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
	textDefaultWorkspaceLoggedIn:"您正在浏览预设工作区",
	textViewingWorkspace:"您正在浏览工作区：",
	textLoggedInAs:"登入用户为：",
	status:" 状态 »",
	textNotLoggedIn:"您尚未登入",
	textAdmin:"您是本工作区的管理者之一"
});
//}}}
/***
!config.macros.ccOptions
***/
//{{{
merge(config.macros.ccOptions, {
	linkManageUsers:"用户管理",
	linkPermissions:"权限管理",
	linkFiles:"文件管理",
	linkCreate:"创建工作区",
	linkOffline:"离线检视",
	linkPassword:"变更口令",
	linkStats:"流量统计",
	options:"偏好设置 »"	
});
//}}}
/***
!config.macros.register
***/
//{{{
merge(config.macros.register,{
	usernameRequest:"用户",
	passwordRequest:"口令",
	passwordConfirmationRequest:"确认口令",
	emailRequest:"电子邮件",
	stepRegisterTitle:"注册用户.",
	stepRegisterIntroText:"嗨，欢迎注册 .... ",
	stepRegisterHtml:"<label>用户</label><input class='input' id='reg_username' name='reg_username' tabindex='1'/><span></span><input type='hidden'  name='username_error'></input><br /><label>电子邮件</label><input class='input' name=reg_mail id='reg_mail' tabindex='2'/><span> </span><input type='hidden' name='mail_error'></input><br/><label>口令</label><input type='password' class='input' id='password1' name='reg_password1' tabindex='3'/><span> </span><input type='hidden'  name='pass1_error'></input><br/><label>确认口令</label><input type='password' class='input' id='password2' name='reg_password2' tabindex='4'/><span> </span><input type='hidden'  name='pass2_error'></input>",
	buttonCancel:"取消",
	buttonCancelToolTip:"取消注册作业",
	buttonRegister:"注册",	
	buttonRegisterToolTip:"点击此处注册",
	msgCreatingAccount:"即将为您创建用户", 
	msgNoUsername:"未输入用户名称", 
	msgEmailOk:"电子邮件有效",
	msgNoPassword:"未输入口令",
	msgDifferentPasswords:"口令不符合",
	msgUsernameTaken:"此用户名称已注册",
	msgUsernameAvailable:"用户名称有效",
	step2Title:"",
	step2Html:"创建用户中，请稍后 ...",
	errorRegisterTitle:"发生错误",
	errorRegister:"未创建用户，请用不同帐号重新注册。"
});
//}}}
/***
!config.macros.ccStats
***/
//{{{
merge(config.macros.ccStats, {
	graph24HourTitle:"最近 24 小时",
	graph24HourDesc:"最近 24 小时内，本工作区的点阅数。",
	graph20MinsTitle:"最近 20 分",
	graph20MinsDesc:"最近 20 分钟内，本工作区的点阅数。",
	graph7DaysTitle:"最近 7 天",
	graph7DaysDesc:"最近 7 天内，本工作区的点阅数。",
	graph5MonthsTitle:"最近 5 个月",
	graph5MonthsDesc:"最近 5 个月内，本工作区的点阅数。",
	errorPermissionDenied:"%0 无权检视，您必须是工区 %1 的管理者",
	stepTitle:"工作区统计"
});
//}}}
/***
!ccTiddlyAdaptor
***/
//{{{

merge(config.commands.revisions,{
	text: "修订版本",
	tooltip: "检视此文之其他修订版本",
	loading: "载入中...",
	done: "已取得修订版本",
	revisionTooltip: "检视此修订版",
	popupNone: "无修订版本",
	revisionTemplate: "%0 - 版本:%1，修订者:%2",
	dateFormat:"YYYY年0mm月0DD日 0hh:0mm"
	});

merge(config.commands.deleteTiddlerHosted,{
	text: "删除",
	tooltip: "删除文章",
	warning: "确定删除 '%0'?",
	hideReadOnly: true,
	done: "已删除："
	});
//}}}