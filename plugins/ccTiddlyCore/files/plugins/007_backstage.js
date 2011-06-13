
config.backstageTasks.push("about");
merge(config.tasks,{about:{text: config.macros.ccAbout.buttonBackstageText,tooltip: config.macros.ccAbout.buttonBackstageTooltip,content: '<<ccAbout>>'}});

if (isLoggedIn()){
	config.backstageTasks.push("logout");
	merge(config.tasks,{logout:{text: config.macros.ccLogin.buttonLogout,tooltip: config.macros.ccLogin.buttonLogoutToolTip,content: '<<ccLogin>>'}});

//	config.backstageTasks.push("create");
//	merge(config.tasks,{create: {text: config.macros.ccCreateWorkspace.buttonCreateText, tooltip: config.macros.ccCreateWorkspace.buttonCreateTooltip, content:'<<ccCreateWorkspace>>'}});

}else{
	config.backstageTasks.push("login");
	merge(config.tasks,{login:{text: config.macros.ccLogin.buttonlogin,tooltip: config.macros.ccLogin.buttonLoginToolTip,content: '\r\n\r\n<<tiddler Login>>'}});	
}

