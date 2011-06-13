//{{{
config.shadowTiddlers['MainMenu'] = "[[開始使用|GettingStarted]][[聯絡資訊|Contacts]]";
config.shadowTiddlers.OptionsPanel = "[[說明|Help]]\n[[設定|AdvancedOptions]]\n<<ccOptions>>";

if (isLoggedIn()){
	merge(config.tasks,{logout:{text: config.macros.ccLogin.buttonLogout, tooltip:config.macros.ccLogin.buttonLogoutToolTip,content: '<<ccLogin>>'}});
	merge(config.tasks,{create: {text: config.macros.ccCreateWorkspace.buttonCreateText, tooltip: config.macros.ccCreateWorkspace.buttonCreateTooltip, content:'<<ccCreateWorkspace>>'}});
}else{
	merge(config.tasks,{login:{text: config.macros.ccLogin.buttonLogin, tooltip:config.macros.ccLogin.buttonLoginToolTip, content: '<<ccLogin>>'}});	
}
//}}}