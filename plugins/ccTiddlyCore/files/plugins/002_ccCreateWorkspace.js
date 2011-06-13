
//{{{

config.macros.ccCreateWorkspace = {};

config.macros.ccCreateWorkspace.setStatus=function(w,element,text){
	var label_var = w.getElement(element);
	removeChildren(label_var.previousSibling);
	var label = document.createTextNode(text);
	label_var.previousSibling.insertBefore(label,null);
}

config.macros.ccCreateWorkspace.workspaceNameKeyPress=function(w){
	params={};
	params.w = w;
	doHttp('POST',url+'/handle/lookupWorkspaceName.php',"ccWorkspaceLookup="+w.formElem["workspace_name"].value+"&free=1",null,null,null,config.macros.ccCreateWorkspace.workspaceNameCallback,params);	
	return false;
};
 	
config.macros.ccCreateWorkspace.workspaceNameCallback=function(status,params,responseText,uri,xhr){
	var me = config.macros.ccCreateWorkspace;
	if(responseText > 0){{
			config.macros.register.setStatus(params.w, "workspace_error", me.errorWorkspaceNameInUse);
			config.macros.register.setStatus(params.w, "workspace_url", "");
	}}else{
		config.macros.register.setStatus(params.w, "workspace_error", me.msgWorkspaceAvailable);
		if (window.useModRewrite == 1)
			config.macros.register.setStatus(params.w, "workspace_url", url+''+params.w.formElem["workspace_name"].value);			 
		else
			config.macros.register.setStatus(params.w, "workspace_url", url+'?workspace='+params.w.formElem["workspace_name"].value);
	}
};

config.macros.ccCreateWorkspace.handler =  function(place,macroName,params,wikifier,paramString,tiddler, errorMsg){
	if (window.workspacePermission.canCreateWorkspace!=1) {
		createTiddlyElement(place,'div', null, "annotation",  config.macros.ccCreateWorkspace.errorPermissions);
		return null;
	}
	var me = config.macros.ccCreateWorkspace;
	var w = new Wizard();
	w.createWizard(place,me.wizardTitle);
	if(config.macros.ccCreateWorkspace.createWorkspaceAdvanced)
		me.stepCreateHtml += config.macros.ccCreateWorkspace.createWorkspaceAdvanced();

	w.addStep(me.stepTitle, me.stepCreateHtml);
	w.formElem["workspace_name"].onkeyup=function() {me.workspaceNameKeyPress(w);};
	w.formElem.onsubmit = function() { config.macros.ccCreateWorkspace.createWorkspaceOnSubmit(w);  return false;};
	w.setButtons([
		{caption: me.buttonCreateWorkspaceText, tooltip: me.buttonCreateWorkspaceTooltip, onClick:function(){config.macros.ccCreateWorkspace.createWorkspaceOnSubmit(w);}
	}]);
};

config.macros.ccCreateWorkspace.createWorkspaceOnSubmit = function(w){
	var params = {}; 
	params.w = w;	
	if(window.useModRewrite == 1)
		params.url = url+w.formElem["workspace_name"].value; 
	else
		params.url = url+'?workspace='+w.formElem["workspace_name"].value;
	var loginResp = doHttp('POST',url+'?&workspace='+w.formElem["workspace_name"].value+"/",'&ccCreateWorkspace=' + encodeURIComponent(w.formElem["workspace_name"].value)+'&amp;ccAnonPerm='+encodeURIComponent("AADD"),null,null,null,config.macros.ccCreateWorkspace.createWorkspaceCallback,params);
	return false; 
};

config.macros.ccCreateWorkspace.createWorkspaceCallback = function(status,params,responseText,uri,xhr) {
	if(xhr.status==201){
		params.w.addStep("Please wait", "This could take afew minutes depending on your internet connection.<img src='http://www.ajaxload.info/cache/FF/FF/FF/00/00/00/37-0.gif'/>"+"<br/><br/><input width='300' name='statusMarker'/>");
		params.w.setButtons([]);
		if(params.selectedPackage) {
	   		var url = store.getTiddlerSlice(params.selectedPackage,'URL');
			loadRemoteFile(url,config.macros.ccCreateWorkspace.fetchFileCallback ,params);
		} else {
			window.location = params.url;
		}
	}else if(xhr.status == 200){
		displayMessage(config.macros.ccCreateWorkspace.errorWorkspaceNameInUse);
	}else if(xhr.status == 403){
		displayMessage(config.macros.ccCreateWorkspace.errorPermissions);	
	}else{
			displayMessage("sd"+responseText);	
	}
};

//}}}
