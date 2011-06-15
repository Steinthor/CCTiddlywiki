

// ccAdaptorCommandsPlugin //
function ccTiddlyAdaptor(){}
merge(ccTiddlyAdaptor,{ 
	errorTitleNotSaved:"<h1>Your changes were NOT saved.</h1>", 
	errorTextSessionExpired:"Your Session has expired. <br /> You will need to log into the new window and then copy your changes from this window into the new window. ", 
	errorTextConfig:"There was a conflict when saving. <br /> Please open the page in a new window to see the changes.",
	errorTextUnknown:"An unknown error occured.",
	errorClose:"close",
	buttonOpenNewWindow:"Open a Window where I can save my changes	.... ",
	buttonHideThisMessage:"Hide this message", 
	msgErrorCode:"Error Code : "
});



//{{{
	
	config.commands.revisions = {};
	merge(config.commands.revisions,{
		text: "revisions",
		tooltip: "View another revision of this tiddler",
		loading: "loading...",
		done: "Revision downloaded",
		revisionTooltip: "View this revision",
		popupNone: "No revisions",
		revisionTemplate: "%1: %2 at %0",
		dateFormat:"YYYY mmm 0DD 0hh:0mm"	
	});

	config.commands.deleteTiddlerHosted = {};
	merge(config.commands.deleteTiddlerHosted,{
		text: "delete",
		tooltip: "Delete this tiddler",
		warning: "Are you sure you want to delete '%0'?",
		hideReadOnly: true,
		done: "Deleted "
	});
	
	
// Ensure that the plugin is only installed once.
if(!version.extensions.AdaptorCommandsPlugin) {
	version.extensions.AdaptorCommandsPlugin = {installed:true};



// implementing closeTiddler without the clearMessage();
Story.prototype.closeTiddler = function(title,animate,unused)
{
	var tiddlerElem = this.getTiddler(title);
	if(tiddlerElem) {
		this.scrubTiddler(tiddlerElem);
		if(config.options.chkAnimate && animate && anim && typeof Slider == "function")
			anim.startAnimating(new Slider(tiddlerElem,false,null,"all"));
		else {
			removeNode(tiddlerElem);
			forceReflow();
		}
	}
};

function getServerType(fields)
{
	if(!fields)
		return null;
	var serverType = fields['server.type'];
	if(!serverType)
		serverType = fields['wikiformat'];
	if(!serverType)
		serverType = config.defaultCustomFields['server.type'];
	if(!serverType && typeof RevisionAdaptor != 'undefined' && fields.uuid)
		serverType = RevisionAdaptor.serverType;
	return serverType;
}

function invokeAdaptor(fnName,param1,param2,context,userParams,callback,fields)
{
	var serverType = getServerType(fields);
	if(!serverType)
		return null;
	var adaptor = new config.adaptors[serverType];
	if(!adaptor)
		return false;
	if(!config.adaptors[serverType].prototype[fnName])
		return false;
	adaptor.openHost(fields['server.host']);
	adaptor.openWorkspace(fields['server.workspace']);
	var ret = false;
	if(param1)
		ret = param2 ? adaptor[fnName](param1,param2,context,userParams,callback) : adaptor[fnName](param1,context,userParams,callback);
	else
		ret = adaptor[fnName](context,userParams,callback);
	return ret;
}

//# Returns true if function fnName is available for the serverType specified in fields
//# Used by (eg): config.commands.download.isEnabled
function isAdaptorFunctionSupported(fnName,fields)
{
	var serverType = getServerType(fields);
	if(!serverType || !config.adaptors[serverType])
		return false;
	if(!config.adaptors[serverType].isLocal && !fields['server.host'])
		return false;
	var fn = config.adaptors[serverType].prototype[fnName];
	return fn ? true : false;
}

config.commands.revisions.isEnabled = function(tiddler)
{
	return isAdaptorFunctionSupported('getTiddlerRevisionList',tiddler.fields);
};

config.commands.revisions.handler = function(event,src,title)
{
	var tiddler = store.fetchTiddler(title);
	userParams = {};
	userParams.tiddler = tiddler;
	userParams.src = src;
	userParams.dateFormat = config.commands.revisions.dateFormat;
	var revisionLimit = 10;
	if(!invokeAdaptor('getTiddlerRevisionList',title,revisionLimit,null,userParams,config.commands.revisions.callback,tiddler.fields))
		return false;
	event.cancelBubble = true;
	if(event.stopPropagation)
		event.stopPropagation();
	return true;
};

config.commands.revisions.callback = function(context,userParams)
// The revisions are returned as tiddlers in the context.revisions array
{
	var revisions = context.revisions;
	popup = Popup.create(userParams.src);
	Popup.show(popup,false);
	if(revisions.length==0) {
		createTiddlyText(createTiddlyElement(popup,'li',null,'disabled'),config.commands.revisions.popupNone);
	} else {
		revisions.sort(function(a,b) {return a.modified < b.modified ? +1 : -1;});
		for(var i=0; i<revisions.length; i++) {
			var tiddler = revisions[i];
			var modified = tiddler.modified.formatString(context.dateFormat||config.commands.revisions.dateFormat);
			var revision = tiddler.fields['server.page.revision'];
			var btn = createTiddlyButton(createTiddlyElement(popup,'li'),
					config.commands.revisions.revisionTemplate.format([modified,revision,tiddler.modifier]),
					tiddler.text||config.commands.revisions.revisionTooltip,
					function() {
						config.commands.revisions.getTiddlerRevision(this.getAttribute('tiddlerTitle'),this.getAttribute('tiddlerModified'),this.getAttribute('tiddlerRevision'),this);
						return false;
						},
					'tiddlyLinkExisting tiddlyLink');
			btn.setAttribute('tiddlerTitle',userParams.tiddler.title);
			btn.setAttribute('tiddlerRevision',revision);
			btn.setAttribute('tiddlerModified',tiddler.modified.convertToYYYYMMDDHHMM());
			if(userParams.tiddler.fields['server.page.revision'] == revision || (!userParams.tiddler.fields['server.page.revision'] && i==0))
				btn.className = 'revisionCurrent';
		}
	}
};

config.commands.revisions.getTiddlerRevision = function(title,modified,revision)
{
	var tiddler = store.fetchTiddler(title);
	var context = {modified:modified};
	return invokeAdaptor('getTiddlerRevision',title,revision,context,null,config.commands.revisions.getTiddlerRevisionCallback,tiddler.fields);
 };

config.commands.revisions.getTiddlerRevisionCallback = function(context,userParams)
{
	if(context.status) {
		var tiddler = context.tiddler;
		store.addTiddler(tiddler);
		store.notify(tiddler.title, true);
		story.refreshTiddler(tiddler.title,1,true);
	} else {
		displayMessage(context.statusText);
	}
};

config.commands.deleteTiddlerHosted.handler = function(event,src,title)
{
	var tiddler = store.fetchTiddler(title);
		if(!tiddler)
			return false;
		var deleteIt = true;
		if(config.options.chkConfirmDelete)
		        deleteIt = confirm(this.warning.format([title]));
		if(deleteIt) {
			var ret = invokeAdaptor('deleteTiddler',title,null,null,null,config.commands.deleteTiddlerHosted.callback,tiddler.fields);
			if(ret){
				store.removeTiddler(title);
				story.closeTiddler(title,true);
			}
		}
		return false;

};

config.commands.deleteTiddlerHosted.callback = function(context,userParams)
{
	if(context.status) {
		displayMessage(config.commands.deleteTiddlerHosted.done + context.title);
	} else {
		if (context.statusText.indexOf("Not Found") == -1)
			displayMessage(context.statusText);
	}
};

}//# end of 'install only once'
//}}}


// ccAdaptor //

//{{{

	window.isLoggedIn = function(){
		return (window.loggedIn == '1') 
	}

	ccTiddlyAdaptor.prototype = new AdaptorBase();

	ccTiddlyAdaptor.mimeType = 'application/json';
	ccTiddlyAdaptor.serverType = 'cctiddly'; // MUST BE LOWER CASE
	ccTiddlyAdaptor.serverParsingErrorMessage = "Error parsing result from server";
	ccTiddlyAdaptor.errorInFunctionMessage = "Error in function ccTiddlyAdaptor.%0";

	ccTiddlyAdaptor.minHostName = function(host){
		return host ? host.replace(/^http:\/\//,'').replace(/\/$/,'') : '';
	};

	// Convert a page title to the normalized form used in uris
	ccTiddlyAdaptor.normalizedTitle = function(title){
		return title;
	};

	// Convert a date in YYYY-MM-DD hh:mm format into a JavaScript Date object
	ccTiddlyAdaptor.dateFromEditTime = function(editTime){
		var dt = editTime;
		return new Date(Date.UTC(dt.substr(0,4),dt.substr(5,2)-1,dt.substr(8,2),dt.substr(11,2),dt.substr(14,2)));
	};

	ccTiddlyAdaptor.prototype.login = function(context,userParams,callback){
		if(window.location.search.substring(1))
			var uriParams = window.location.search.substring(1);
		else
			var uriParams = "";
		context = this.setContext(context,userParams,callback);
		var uriTemplate = '%0/handle/loginFile.php?'+uriParams;
		var uri = uriTemplate.format([context.host,context.username,context.password]);
		httpReq('POST',uri,ccTiddlyAdaptor.loginCallback,context,null,"cctuser="+context.username+"&cctpass="+context.password); 
		return typeof req == 'string' ? req : true;
	};

	ccTiddlyAdaptor.loginCallback = function(status,context,responseText,uri,xhr){
		if(xhr.status==401){
			context.status = false;
		}else{
			context.status = true;
		}
		if(context.callback)
			context.callback(context,context.userParams);
	};

	ccTiddlyAdaptor.prototype.register = function(context,userParams,callback){
		context = this.setContext(context,userParams,callback);
		var uriTemplate = '%0/handle/register.php';
		var uri = uriTemplate.format([context.host,context.username,Crypto.hexSha1Str(context.password)]);
		var dataTemplate = 'username=&0&reg_mail=%1&password=%2&password2=%3';
		var data = dataTemplate.format([context.username,context.password1,context.password2]);
		var req = httpReq('POST', uri,ccTiddlyAdaptor.registerCallback,context,null,data);
		return typeof req == 'string' ? req : true;
	};

	ccTiddlyAdaptor.prototype.rename = function(context, userParams, callback){
		if(window.location.search.substring(1))
			var postParams = "&"+window.location.search.substring(1);
		else
			var postParams = "";
		context = this.setContext(context,userParams,callback);
		var uri = window.url+"handle/renameTiddler.php?otitle="+context.title+"&ntitle="+context.newTitle+"&workspace="+window.workspace+postParams;;
		httpReq('POST', uri,ccTiddlyAdaptor.renameCallback,context,null,null);
	};

	ccTiddlyAdaptor.renameCallback = function(status,context,responseText,uri,xhr){
		if(context.callback)
			context.callback(context,context.userParams);
	};

	ccTiddlyAdaptor.registerCallback = function(status,context,responseText,uri,xhr){
		if(status){
			context.status = true;
		}else{
			context.status = false;
		}
		if(context.callback)
			context.callback(context,context.userParams);
	};

	ccTiddlyAdaptor.prototype.getWorkspaceList = function(context,userParams,callback){
	 	context = this.setContext(context,userParams,callback);
		var uriTemplate = '%0/handle/listWorkspaces.php';
		var uri = uriTemplate.format([context.host]);
		var req = httpReq('GET', uri,ccTiddlyAdaptor.getWorkspaceListCallback,context,{'accept':'application/json'});
		return typeof req == 'string' ? req : true;
	};

	ccTiddlyAdaptor.getWorkspaceListCallback = function(status,context,responseText,uri,xhr){
		context.status = false;
		context.workspaces = [];
		context.statusText = ccTiddlyAdaptor.errorInFunctionMessage.format(['getWorkspaceListCallback']);
		if(status){
		try{
			eval('var workspaces=' + responseText);
		}catch (ex){
			context.statusText = exceptionText(ex,ccTiddlyAdaptor.serverParsingErrorMessage);
			if(context.callback)
				context.callback(context,context.userParams);
				return;
			}
			for (var i=0; i < workspaces.length; i++){
				context.workspaces.push({title:workspaces[i]})
			}
			context.status = true;
		}else{
				context.statusText = xhr.statusText;
		}
		if(context.callback)
			context.callback(context,context.userParams);
	};

	ccTiddlyAdaptor.prototype.getTiddlerList = function(context,userParams,callback){
		context = this.setContext(context,userParams,callback);
		var uriTemplate = '%0/handle/listTiddlers.php?workspace=%1';
		var uri = uriTemplate.format([context.host,context.workspace]);
		var req = httpReq('GET', uri,ccTiddlyAdaptor.getTiddlerListCallback,context,{'accept':'application/json'});
		return typeof req == 'string' ? req : true;
	};

	ccTiddlyAdaptor.getTiddlerListCallback = function(status,context,responseText,uri,xhr){
		context.status = false;
		context.statusText = ccTiddlyAdaptor.errorInFunctionMessage.format(['getTiddlerListCallback']);
		if(status){
			try{
				eval('var tiddlers=' + responseText);
			}catch (ex){
				context.statusText = exceptionText(ex,ccTiddlyAdaptor.serverParsingErrorMessage);
				if(context.callback)
					context.callback(context,context.userParams);
				return;
			}
			var list = [];
			for(var i=0; i < tiddlers.length; i++){
				var tiddler = new Tiddler(tiddlers[i]['title']);
				tiddler.fields['server.page.revision'] = tiddlers[i]['revision'];
				list.push(tiddler);
			}
			context.tiddlers = list;
			context.status = true;
		}else{
			context.statusText = xhr.statusText;
		}
		if(context.callback)
			context.callback(context,context.userParams);
	};

	ccTiddlyAdaptor.prototype.generateTiddlerInfo = function(tiddler){
		var info ={};
		var host = this && this.host ? this.host : this.fullHostName(tiddler.fields['server.host']);
		var bag = tiddler.fields['server.bag']
		var workspace = tiddler.fields['server.workspace']
		var uriTemplate = '%0/%1/#%2';
		info.uri = uriTemplate.format([host,workspace,tiddler.title]);
		return info;
	};

	ccTiddlyAdaptor.prototype.getTiddlerRevision = function(title,revision,context,userParams,callback){
		context = this.setContext(context,userParams,callback);
		if(revision)
			context.revision = revision;
		return this.getTiddler(title,context,userParams,callback);
	};

	ccTiddlyAdaptor.prototype.getTiddler = function(title,context,userParams,callback){
		context = this.setContext(context,userParams,callback);
		if(title)
			context.title = title;
		   if(context.revision){
		         var uriTemplate = '%0/handle/revisionDisplay.php?title=%2&workspace=%1&revision=%3';
		  }else{
				var uriTemplate = '%0/handle/getTiddler.php?title=%2&workspace=%1';
		  }

		uri = uriTemplate.format([context.host,context.workspace,ccTiddlyAdaptor.normalizedTitle(title),context.revision]);
		context.tiddler = new Tiddler(title);
		context.tiddler.fields['server.type'] = ccTiddlyAdaptor.serverType;
		context.tiddler.fields['server.host'] = ccTiddlyAdaptor.minHostName(context.host);
		context.tiddler.fields['server.workspace'] = context.workspace;
		var req = httpReq('GET', uri,ccTiddlyAdaptor.getTiddlerCallback,context,{'accept':'application/json'});
		return typeof req == 'string' ? req : true;
	};

	ccTiddlyAdaptor.getTiddlerCallback = function(status,context,responseText,uri,xhr){
	        context.status = false;
	        context.statusText = ccTiddlyAdaptor.errorInFunctionMessage.format(['getTiddlerCallback']);
	        if(status){
	                var info=[]
	                try{
	                    eval('info=' + responseText);
	                }catch (ex){
	                        context.statusText = exceptionText(ex,ccTiddlyAdaptor.serverParsingErrorMessage);
	                        if(context.callback)
	                                context.callback(context,context.userParams);
	                        return;
	                }
	                context.tiddler.text = info['text'];
					context.tiddler.tags = info['tags'].split(" ");
	                context.tiddler.fields['server.page.revision'] = info['server.page.revision'];
					context.tiddler.fields['server.id'] = info['id'];
					if(info['fields'])
						context.tiddler.fields = merge(info['fields'], context.tiddler.fields);
					else
						context.tiddler.fields = context.tiddler.fields;
				    context.tiddler.modifier = info['modifier'];
	                context.tiddler.modified = Date.convertFromYYYYMMDDHHMM(info['modified']);
	                context.tiddler.created = Date.convertFromYYYYMMDDHHMM(info['created']);
	                context.status = true;
	
	        }else{
	                context.statusText = xhr.statusText;
	                if(context.callback)
	                        context.callback(context,context.userParams);
	                return;
	        }
	        if(context.callback)
			context.callback(context,context.userParams);
	};

	ccTiddlyAdaptor.prototype.getTiddlerRevisionList = function(title,limit,context,userParams,callback){
		context = this.setContext(context,userParams,callback);
		context.title = title;
		context.revisions = [];
		var tiddler = store.fetchTiddler(title);
		var encodedTitle = encodeURIComponent(title);
		var uriTemplate = '%0/handle/revisionList.php?workspace=%1&title=%2';
		var host = this.fullHostName(this.host);
		var workspace = context.workspace ? context.workspace : tiddler.fields['server.workspace'];
		var uri = uriTemplate.format([host,workspace,encodedTitle]);
		var req = httpReq('GET', uri,ccTiddlyAdaptor.getTiddlerRevisionListCallback,context);
	};

	ccTiddlyAdaptor.getTiddlerRevisionListCallback = function(status,context,responseText,uri,xhr){
		if(responseText.indexOf('<!DOCTYPE html')==1)
			status = false;
		if(xhr.status=="204")
			status = false;
		context.status = false;
		if(status){
			var r =  responseText;
			if(r != '-' && r.trim() != 'revision not found'){
				var revs = r.split('\n');
				for(var i=0; i<revs.length; i++){
					var parts = revs[i].split(' ');
					if(parts.length>1){
						var tiddler = new Tiddler(context.title);
						tiddler.modified = Date.convertFromYYYYMMDDHHMM(parts[0]);
						tiddler.fields['server.page.revision'] = String(parts[1]);
						tiddler.modifier = String(parts[2]);
						tiddler.fields['server.host'] = ccTiddlyAdaptor.minHostName(context.host);
						tiddler.fields['server.type'] = ccTiddlyAdaptor.serverType;
						context.revisions.push(tiddler);
					}
				}
			}
			context.revisions.sort(function(a,b){return a.modified<b.modified?+1:-1;});
			context.status = true;
		}else{
			context.statusText = xhr.statusText;
		}
		if(context.callback)
			context.callback(context,context.userParams);
	};

	ccTiddlyAdaptor.prototype.putTiddler = function(tiddler,context,userParams,callback){
		context = this.setContext(context,userParams,callback);
		context.title = tiddler.title;
		if(window.location.search.substring(1))
			var postParams = window.location.search.substring(1);
		else
			var postParams = "";
		var recipeuriTemplate = '%0/handle/save.php';
		var host = context.host ? context.host : this.fullHostName(tiddler.fields['server.host']);
		var uri = recipeuriTemplate.format([host,context.workspace,tiddler.title]);
		var d = new Date();
		d.setTime(Date.parse(tiddler['modified']));
		d = d.convertToYYYYMMDDHHMM();

		//  SEO Code

		if(workspace)
		 	var breaker = "/";
		else
			var breaker = "";
		var el = createTiddlyElement(document.body, "div", "ccTiddlyTMP", null, null, { "style.display": "none" });
		el.style.display = "none";  // Just in case the above command is ignored
		var formatter = new Formatter(config.formatters);
		var wikifier = new Wikifier(tiddler.text,formatter,null,tiddler);
			wikifier.isStatic = true;
			wikifier.subWikify(el);
		delete formatter;
		var links = el.getElementsByTagName("a");
		for(var i = 0; i < links.length; i++) {
			var tiddlyLink = links[i].getAttribute("tiddlyLink");
		    if(tiddlyLink) {
		        if(hasClass(links[i], "tiddlyLinkNonExisting")) { // target tiddler does not exist
		            links[i].href = "#";
		        } else {
		            links[i].href = url+ workspace + breaker +tiddlyLink + ".html";
		        }
		    }
		}	
		// End SEO Code 

		var fieldString = "";
		for (var name in tiddler.fields){
			if (String(tiddler.fields[name]) && name != "server.page.revision" && name != "changecount")
				fieldString += name +"='"+tiddler.fields[name]+"' ";
		}
		if(!tiddler.fields['server.page.revision'])
			tiddler.fields['server.page.revision'] = 0;		
		else
			tiddler.fields['server.page.revision'] = parseInt(tiddler.fields['server.page.revision'],10);
		context.revision = tiddler.fields['server.page.revision'];
		
		if(!context.otitle)
			var otitle = tiddler.title;
		else
			var otitle = context.otitle;
			
		var payload = "workspace="+window.workspace+"&otitle="+encodeURIComponent(otitle)+"&title="+encodeURIComponent(tiddler.title) + "&modified="+tiddler.modified.convertToYYYYMMDDHHMM()+"&modifier="+tiddler.modifier + "&tags="+encodeURIComponent(tiddler.getTags())+"&revision="+encodeURIComponent(tiddler.fields['server.page.revision']) + "&fields="+encodeURIComponent(fieldString)+
	"&body="+encodeURIComponent(tiddler.text)+"&wikifiedBody="+encodeURIComponent(el.innerHTML)+"&id="+tiddler.fields['server.id']+"&"+postParams;
		var req = httpReq('POST', uri,ccTiddlyAdaptor.putTiddlerCallback,context,{'Content-type':'application/x-www-form-urlencoded', "Content-length": payload.length},payload,"application/x-www-form-urlencoded");
		return typeof req == 'string' ? req : true;
	};

	ccTiddlyAdaptor.putTiddlerCallback = function(status,context,responseText,uri,xhr){
		if(xhr.status == 201){
			context.status = true;
			if(responseText!="") {
				context.tiddler.fields['server.id'] = responseText;
			}
			context.tiddler.fields['server.page.revision'] = context.revision + 1;
		}
		if(context.callback){
			context.callback(context,context.userParams);
		}
	};
	
	

	ccTiddlyAdaptor.prototype.deleteTiddler = function(title,context,userParams,callback){	
		context = this.setContext(context,userParams,callback);
		context.title = title;
		title = encodeURIComponent(title);
		var uri = tiddler.fields['server.host']+'/handle/delete.php'
		var data = "workspace="+workspace+"&title="+title;
		
		var req = httpReq('POST', uri,ccTiddlyAdaptor.deleteTiddlerCallback,context, null, data);
		return typeof req == 'string' ? req : true;
	};

	ccTiddlyAdaptor.deleteTiddlerCallback = function(status,context,responseText,uri,xhr){
		if(status){
			context.status = true;
		}else{
			context.status = false;
			context.statusText = xhr.statusText;
		}
		if(context.callback)
			context.callback(context,context.userParams);
	};

	
	
//}}}


//}}}

