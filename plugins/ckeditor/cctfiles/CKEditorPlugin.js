/***
|''Name:''|CKeditorPlugin|
|''Description:''|Wysiwyg editor for TiddlyWiki using CKeditor(http://ckeditor.com/).|
|''Version:''|1.1.1|
|''Date:''|Jan 01,2010|
|''Source:''|forked from : http://visualtw.ouvaton.org/VisualTW.html to try CKEditor|
|''Author:''|Simon McManus|
|''License:''|[[BSD open source license|License]]|

***/
//{{{

config.macros.editHtml = {
	alwaysOn:false, 
	showButton:true,
	handler : function(place,macroName,params,wikifier,paramString,tiddler) {
		var field = params[0];
		var height = params[1] ? params[1] : config.options.txtFCKheight;
		if (typeof CKEDITOR=="undefined"){
			displayMessage(config.macros.editHtml.FCKeditorUnavailable);
			config.macros.edit.handler(place,macroName,params,wikifier,paramString,tiddler);
		}
		else if (field) {
			if (config.options.txtFCKCustomConfigScript) CKEDITOR.config.customConfig = config.options.txtFCKCustomConfigScript ;
			var re = /^<html>(.*)<\/html>$/m;
			var fieldValue=store.getValue(tiddler,field);
			var htmlValue = re.exec(fieldValue);
			var value = (htmlValue && (htmlValue.length>0)) ? htmlValue[1] : fieldValue;
			value=value.replace(/\[\[([^|\]]*)\|([^\]]*)]]/g,'<a href="#$2">$1</a>');
			var ta = createTiddlyElement(place, 'textarea', '', 'fckeditor', value);
			var ckName = "CKeditor"+Math.random();
			ta.name = ckName;
			ta.id = ckName;
			ta.setAttribute("editHtml",field);
			ta.setAttribute("ckName",ckName);
			CKEDITOR.replace(ckName);
		}
	},
        gather : function(e) {
            var name = e.getAttribute("ckName");
            var html = CKEDITOR.instances[name].getData();
		if (html!=null) 
           	       return "<html>"+html.replace(/<a href="#([^>]*)">([^<]*)<\/a>/gi,"[[$2|$1]]")+"</html>"; 
           
        },
	FCKvalues : {},
	FCKeditorUnavailable : "FCKeditor was unavailable. Check plugin configuration and reload."
}

Story.prototype.previousGatherSaveEditHtml = Story.prototype.previousGatherSaveEditHtml ? Story.prototype.previousGatherSaveEditHtml : Story.prototype.gatherSaveFields; // to avoid looping if this line is called several times
Story.prototype.gatherSaveFields = function(e,fields){
	if(e && e.getAttribute) {
		var f = e.getAttribute("editHtml");
		if(f){
			var newVal = config.macros.editHtml.gather(e);

			if (newVal) fields[f] = newVal;
		}
		this.previousGatherSaveEditHtml(e, fields);
	}
};




//}}}