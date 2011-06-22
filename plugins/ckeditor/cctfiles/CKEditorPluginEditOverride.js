/***
|''Name:''|FCKeditorPluginEditOverride|
|''Description:''|Overrides the tiddlywiki edit handler so that the CKEditor is used by default without having to change the editTemplate|
|''Version:''|1.1.1|
|''Date:''|Jan 01,2001|
|''Source:''|forked from : http://visualtw.ouvaton.org/VisualTW.html to try CKEditor|
|''Author:''|Simon McManus|
|''License:''|[[BSD open source license|License]]|

***/
//{{{

var oldEditHandler = config.macros.edit.handler;
config.macros.edit.handler = function(place,macroName,params,wikifier,paramString,tiddler)
{
	var editHolder = createTiddlyElement(place, "div", "editHolder");
	if(config.macros.editHtml.alwaysOn == true) {
		config.macros.editHtml.handler(editHolder,macroName,params,wikifier,paramString,tiddler);
		return true;
	}
	if(paramString.indexOf("text") === -1)  {// if paramsString does not contain "text" 
		oldEditHandler(editHolder,macroName,params,wikifier,paramString,tiddler);  // use old edit handler 
	}else {  // We are dealing with the main text area 	
		var markupSwitch = function() {
			// attempt to retrieve previous conent and add it to the new textarea
			if(confirm("Unsaved changes to this tiddler will be lost??")===true) {
				removeChildren(this.parentNode);
				oldEditHandler(place,macroName,params,wikifier,paramString,tiddler);  // use old edit handler 
			}
		};
		if(tiddler.text.substring(0, 6)==="<html>" || tiddler.text == config.views.editor.defaultText.format([tiddler.title])) {
			config.macros.editHtml.handler(editHolder,macroName,params,wikifier,paramString,tiddler);
			if(config.macros.editHtml.showButton==true)
				createTiddlyButton(editHolder, 'Revert to TiddlyWiki markup', 'revert to TiddlyWiki Markup', markupSwitch, 'wikiMarkupButton', null, null, {tiddler:tiddler.title})
		} else {
			oldEditHandler(editHolder,macroName,params,wikifier,paramString,tiddler);  // use old edit handler 
		}		
	}
};

config.shadowTiddlers["ckEditorStyles"] = store.getTiddlerText("CKEditorPluginEditOverride##StyleSheet");
store.addNotification("ckEditorStyles", refreshStyles);



/***
!StyleSheet


!(end of StyleSheet)
***/

//}}}