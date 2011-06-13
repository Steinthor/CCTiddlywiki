// ccLoginStatus //

//{{{


config.macros.ccLoginStatus={};
	
config.macros.ccLoginStatus.handler=function(place,macroName,params,wikifier,paramString,tiddler){
	var loginDiv=createTiddlyElement(place,"div",null,"loginDiv",null);
	this.refresh(loginDiv);
};
	
config.macros.ccLoginStatus.refresh=function(place,errorMsg){
       var me = config.macros.ccLoginStatus;
       var loginDivRef=document.getElementById ("LoginDiv");
       removeChildren(loginDivRef);
       var wrapper=createTiddlyElement(place,"div");
       var str = (workspace == "" ? me.textDefaultWorkspaceLoggedIn :(me.textViewingWorkspace+workspace))+"\r\n\r\n";
       if (isLoggedIn()){
			name = cookieString(document.cookie).txtUserName;
			str += me.textLoggedInAs+decodeURIComponent(name)+".\r\n\r\n";
			if (workspacePermission.owner==1){
				str += me.textAdmin;
			}
       }else{
               str += me.textNotLoggedIn;
       }
       wikify(str,wrapper);
};
//}}}

// ccOptions //
//{{{
config.macros.ccOptions={};		
config.macros.ccOptions.handler=function(place,macroName,params,wikifier,paramString,tiddler){
	var me = config.macros.ccOptions;
	if(workspacePermission.owner==1)
		wikify("[["+me.linkManageUsers+"|Manage Users]]<br />[["+me.linkPermissions+"|Permissions]]<br />[["+me.linkStats+"|Statistics]]<br />", place);
	if (isLoggedIn())
		wikify("[["+me.linkFiles+"|files]]<br />", place);
		if (isLoggedIn()){
			if (workspacePermission.canCreateWorkspace==1)
				wikify("[["+me.linkCreate+"|CreateWorkspace]]<br />", place);
			// append url function required 
			wikify("[["+me.linkPassword+"|Password]]<br />", place);
			if (window.fullUrl.indexOf("?") >0)
				wikify("[["+me.linkOffline+"|"+fullUrl+"&standalone=1]]<br />", place);
			else 
				wikify("[["+me.linkOffline+"|"+fullUrl+"?standalone=1]]<br />", place);	
		}
};

//}}}
