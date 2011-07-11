/*
This is a small update on the image handler, 
so that you can link to the image with just the name, and it checks the upload folder for the workspace.
*/
//{{{
version.extensions.ccImagePathPlugin= {major: 0, minor: 0, revision: 1, date: new Date(2011,7,11)};
//}}}

config.formatters[config.formatters.findByField("name","image")].handler=function(w) {
	this.lookaheadRegExp.lastIndex = w.matchStart;
	var lookaheadMatch = this.lookaheadRegExp.exec(w.source);
	if(lookaheadMatch && lookaheadMatch.index == w.matchStart) {
		var e = w.output;
		if(lookaheadMatch[5]) {
			var link = lookaheadMatch[5];
			e = config.formatterHelpers.isExternalLink(link) ? createExternalLink(w.output,link) : createTiddlyLink(w.output,link,false,null,w.isStatic,w.tiddler);
			addClass(e,"imageLink");
		}
		var img = createTiddlyElement(e,"img");
		if(lookaheadMatch[1])
			img.align = "left";
		else if(lookaheadMatch[2])
				img.align = "right";
		if(lookaheadMatch[3]) {
			img.title = lookaheadMatch[3];
			img.setAttribute("alt",lookaheadMatch[3]);
		}
		//img.src = lookaheadMatch[4];
				if(lookaheadMatch[4].search(/http/)<0)
        {
		    img.src = "uploads/"+ window.workspace+"/" + lookaheadMatch[4];
		}
        else
		{
			img.src = lookaheadMatch[4];
		}
		w.nextMatch = this.lookaheadRegExp.lastIndex;
	}
}