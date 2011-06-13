if(!config.extensions) { config.extensions = {}; } //# obsolete from v2.4.2

config.extensions.lazyLoading = {};

(function(plugin) { //# set up alias

Story.prototype.loadMissingTiddlerContents = function(tiddler) {
	var title = tiddler.title;
	var serverType = tiddler.getServerType();
	var host = tiddler.fields["server.host"];
	var workspace = tiddler.fields["server.workspace"]; // XXX: bag?
	if(!serverType || !host) {
		return null;
	}
	var sm = new SyncMachine(serverType, {
		start: function() {
			return this.openHost(host,"openWorkspace");
		},

		openWorkspace: function() {
			return this.openWorkspace(workspace,"getTiddler");
		},

		getTiddler: function() {
			return this.getTiddler(title,"onGetTiddler");
		},

		onGetTiddler: function(context) {
			var tiddler = context.tiddler;
			console.log("skinny tiddler is ", tiddler);
			if(tiddler && tiddler.text) {

				console.log('here 0.1');

				if(!tiddler.created) {
					tiddler.created = new Date();
					console.log('here 0.2');
				}
				if(!tiddler.modified) {
					console.log('here 0.3');
					
					tiddler.modified = tiddler.created;
				}
				console.log('here 0.4');
				store.saveTiddler(tiddler.title, tiddler.title,
					tiddler.text, tiddler.modifier, tiddler.modified,
					tiddler.tags, tiddler.fields, true, tiddler.created);
				autoSaveChanges();
			}
			console.log('here 0.5');
			
			delete this;
			return true;
		},

		error: function(message) {
			displayMessage("Error loading missing tiddler contents from %0: %1".format([host, message]));
		}
	});
	sm.go();
	return config.messages.loadingMissingTiddler.format([title, serverType, host,workspace]);
};

// override createTiddler to trigger lazy loading of tiddler contents
Story.prototype.createTiddler = function(place, before, title, template, customFields) {
	var tiddlerElem = createTiddlyElement(null, "div", this.tiddlerId(title), "tiddler");
	tiddlerElem.setAttribute("refresh", "tiddler");
	if(customFields) {
		tiddlerElem.setAttribute("tiddlyFields", customFields);
	}
	place.insertBefore(tiddlerElem, before);
	var defaultText = null;
	var tiddler = store.getTiddler(title);
	if(!store.tiddlerExists(title) && !store.isShadowTiddler(title)) {
		defaultText = this.loadMissingTiddler(title, customFields, tiddlerElem);
	} else if (store.isShadowTiddler(title)) {
		// its already a shadow tiddler so we don't need to do anything
	} 
	else if(!tiddler.text) { // XXX: faulty check!?
		defaultText = this.loadMissingTiddlerContents(tiddler);
	}
	this.refreshTiddler(title, template, false, customFields, defaultText);
	return tiddlerElem;
};

})(config.extensions.lazyLoading); //# end of alias
