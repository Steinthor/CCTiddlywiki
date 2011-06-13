config.macros.seo = {};
config.macros.seo.handler = function(place,macroName,params,wikifier,paramString,tiddler) {
	createTiddlyButton(place, "Generate HTML files", "Click to generate HTML files.", config.macros.seo.click);
};

config.macros.seo.click = function() {
	var tiddlers=store.getTiddlers("modified","excludeLists").reverse();
	for(var i=0; i<tiddlers.length; i++){
	       store.saveTiddler(tiddlers[i].title);
	}
	
};