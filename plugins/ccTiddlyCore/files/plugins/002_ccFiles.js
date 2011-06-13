// ccFile //


//{{{
	
config.macros.ccFile = {};
var iFrameLoad=function(w){
	var uploadIframe = document.getElementById('uploadIframe');
	var a = createTiddlyElement(null, "div");
	a.innerHTML = uploadIframe.contentDocument.body.innerHTML;
	removeChildren(w.formElem.placeholder);
	w.formElem.placeholder.parentNode.appendChild(a);
	var statusArea = w.formElem.placeholder;
	document.getElementById("ccfile").value=""; 
};

config.macros.ccFile.handler=function(place,macroName,params,wikifier,paramString,tiddler, errorMsg){
	var w = new Wizard();
	w.createWizard(place,config.macros.ccFile.wizardTitleText);
	config.macros.ccFile.refresh(w);
};

config.macros.ccFile.refresh=function(w){
	params = {};
	params.w = w;
	params.e = this;
	var me = config.macros.ccFile;
	doHttp('GET',url+'/handle/listFiles.php?workspace='+workspace,'',null,null,null,config.macros.ccFile.listAllCallback,params);
	w.setButtons([
		{caption: me.buttonDeleteText, tooltip: me.buttonDeleteTooltip, onClick: function(w){ 
			config.macros.ccFile.delFileSubmit(null, params);
			 return false;
		}}, 
		{caption: me.buttonUploadText, tooltip: me.buttonUploadTooltip, onClick: function(e){ 
			config.macros.ccFile.addFileDisplay(null, params); return false 
			} }
	]);
};

config.macros.ccFile.delFileSubmit=function(e, params) {
	var listView = params.w.getValue("listView");
	var rowNames = ListView.getSelectedRows(listView);
	for(var e=0; e < rowNames.length; e++) 
	doHttp('POST',url+'/handle/listFiles.php','action=DELETEFILE&file='+rowNames[e]+'&workspace='+workspace,null,null,null,config.macros.ccFile.delFileCallback,params);
	return false; 
};

config.macros.ccFile.delFileCallback=function(status,params,responseText,uri,xhr){
	config.macros.ccFile.refresh(params.w);
};

config.macros.ccFile.addFileDisplay = function(e, params){
	var frm = params.w.formElem;
	if(navigator.appName=="Microsoft Internet Explorer"){
		encType = frm.getAttributeNode("enctype");
	    encType.value = "multipart/form-data";
	}
	frm.setAttribute("enctype","multipart/form-data");
	frm.setAttribute("method","POST");
	frm.action=window.url+"/handle/upload.php"; 
	frm.id="ccUpload";
	frm.target="uploadIframe";
	frm.name = "uploadForm";
	frm.parentNode.appendChild(frm);
	params.w.addStep("ss", "<input id='ccfile' class='input' type='file' name='userFile'/>"+"<input type='hidden' name='placeholder'/>");
	var workspaceName=createTiddlyElement(null,'input','workspaceName','workspaceName');				
	workspaceName .setAttribute('name','workspace');
	workspaceName.type="HIDDEN";
	workspaceName.value=workspace;
	frm.appendChild(workspaceName);
	createTiddlyElement(frm,'br');
	var saveTo=createTiddlyElement(null,"input","saveTo","saveTo");	
	var iframe=document.createElement("iframe");
	iframe.style.display="none";
	iframe.id='uploadIframe';
	iframe.name='uploadIframe';
	iframe.onload = function() {
		iFrameLoad(params.w);
	}	
	frm.appendChild(iframe);
	createTiddlyElement(frm,"div",'uploadStatus');
	params.w.setButtons([
	{caption: config.macros.ccFile.buttonCancelText, tooltip: config.macros.ccFile.buttonCancelTooltip, onClick: function(){config.macros.ccFile.refresh(params.w);}
	},
	{caption: config.macros.ccFile.buttonUploadText, tooltip: config.macros.ccFile.buttonUploadTooltip, onClick: function(){params.w.formElem.submit();}
	}]);
};

function addOption(selectbox,text,value ){
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;
	selectbox.options.add(optn);
}

config.macros.ccFileImageBox = function(image){
	var full = image.src;
	setStylesheet(
	"#errorBox .button {padding:0.5em 1em; border:1px solid #222; background-color:#ccc; color:black; margin-right:1em;}\n"+
	"html > body > #backstageCloak {height:"+window.innerHeight*2+"px;}"+
	"#errorBox {border:1px solid #ccc;background-color: #fff; color:#111;padding:1em 2em; z-index:9999;}",'errorBoxStyles');
	var box = document.getElementById('errorBox') || createTiddlyElement(document.body,'div','errorBox');
	box.innerHTML =  "<a style='float:right' href='javascript:onclick=ccTiddlyAdaptor.hideError()'>"+ccTiddlyAdaptor.errorClose+"</a><h3>"+image.src+"</h3><br />";
	box.style.position = 'absolute';
	box.style.width= "800px";
	var img = createTiddlyElement(box, "img");
	img.src = full;
	ccTiddlyAdaptor.center(box);
	ccTiddlyAdaptor.showCloak();
}

config.macros.ccFile.listAllCallback = function(status,params,responseText,uri,xhr){
	var me = config.macros.ccFile;
	var out = "";
	var adminUsers = [];
	if(xhr.status!=200){
		params.w.addStep(me.errorPermissionDeniedTitle, me.errorPermissionDeniedView);
		return true;
	}
	try{
		var a = eval(responseText);
		for(var e=0; e < a.length; e++){ 		
		out += a[e].username;	
			adminUsers.push({
				htmlName: "<html><a href='"+a[e].url+"' target='new'>"+a[e].filename+"</a></html>",
				name: a[e].filename,
				wikiText:'<html><img onclick="config.macros.ccFileImageBox(this)"; src="'+a[e].url+'" style="width: 70px; "/></html>',
				URI:a[e].url,
				lastVisit:a[e].lastVisit,
				fileSize:a[e].fileSize
			});
		}
	}catch (ex){
		params.w.setButtons([
			{caption: me.buttonUploadText, tooltip: me.buttonUploadTooltip, onClick: function(w){				
				config.macros.ccFile.addFileDisplay(e, params);
			} }]);
	}
	params.w.addStep(me.wizardStepText+workspace, "<input type='hidden' name='markList'></input>");
	var markList = params.w.getElement("markList");
	var listWrapper = document.createElement("div");
	markList.parentNode.insertBefore(listWrapper,markList);
	var listView = ListView.create(listWrapper,adminUsers,config.macros.ccFile.listAdminTemplate);
	//params.w.setValue("listAdminView",listAdminView);
	params.w.setValue("listView",listView);
};

config.macros.ccFile.addFileCallback = function(status,params,responseText,uri,xhr){	
	config.macros.ccFile.refresh(params.w);
};

//}}}