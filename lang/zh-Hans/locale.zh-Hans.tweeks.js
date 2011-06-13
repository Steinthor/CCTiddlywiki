//{{{
config.shadowTiddlers['MainMenu'] = "[[开始使用|GettingStarted]][[联络资讯|Contacts]]";
config.shadowTiddlers.OptionsPanel = "[[帮助|Help]]\n[[设置|AdvancedOptions]]\n<<ccOptions>>";
if (isLoggedIn()){
	merge(config.tasks,{logout:{text: config.macros.ccLogin.buttonLogout, tooltip:config.macros.ccLogin.buttonLogoutToolTip,content: '<<ccLogin>>'}});
	merge(config.tasks,{create: {text: config.macros.ccCreateWorkspace.buttonCreateText, tooltip: config.macros.ccCreateWorkspace.buttonCreateTooltip, content:'<<ccCreateWorkspace>>'}});
}else{
	merge(config.tasks,{login:{text: config.macros.ccLogin.buttonLogin, tooltip:config.macros.ccLogin.buttonLoginToolTip, content: '<<ccLogin>>'}});	
}
//}}}