

// Import Override - ensures imported tiddlers have cctiddly server type. 
config.macros.importTiddlers.onGetTiddler = function(context,wizard)
{
	if(!context.status)
		displayMessage("Error in importTiddlers.onGetTiddler: " + context.statusText);
	var tiddler = context.tiddler;
	if(store.tiddlerExists(tiddler.title)) { 
		var t = store.getTiddler(tiddler.title); 
		tiddler.fields = t.fields; 
	}
	store.suspendNotifications();
	tiddler.fields['server.id'] = ""; // remove the original id (if one exists)
	tiddler.fields['server.type'] = 'cctiddly';
	tiddler.fields['server.host'] = window.url;
	tiddler.fields['workspace']= window.workspace;
	store.saveTiddler(tiddler.title, tiddler.title, tiddler.text, tiddler.modifier, tiddler.modified, tiddler.tags, tiddler.fields, false, tiddler.created);// local 
//	config.extensions.ServerSideSavingPlugin.saveTiddler(tiddler); // remote save. 
/*	if(!wizard.getValue("sync")) {
		store.setValue(tiddler.title,'server',null);
	}
*/
	store.resumeNotifications();
	if(!context.isSynchronous)
		store.notify(tiddler.title,true);
	var remainingImports = wizard.getValue("remainingImports")-1;
	wizard.setValue("remainingImports",remainingImports);
	if(remainingImports == 0) {
		if(context.isSynchronous) {
			store.notifyAll();
			refreshDisplay();
		}
		wizard.setButtons([
				{caption: config.macros.importTiddlers.doneLabel, tooltip: config.macros.importTiddlers.donePrompt, onClick: config.macros.importTiddlers.onClose}
			],config.macros.importTiddlers.statusDoneImport);
		autoSaveChanges();
	}
};