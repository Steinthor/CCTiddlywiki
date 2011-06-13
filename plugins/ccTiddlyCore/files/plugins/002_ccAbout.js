//{{{
	
//  ccAbout //
config.macros.ccAbout={};

config.macros.ccAbout.handler=function(place,macroName,params,wikifier,paramString,tiddler,errorMsg){
	var w = new Wizard();
	var me = config.macros.ccAbout;
	w.createWizard(place,me.stepAboutTitle);
	w.addStep(null, me.stepAboutTextStart + window.ccTiddlyVersion + "<br /><br />" + me.stepAboutTextEnd);
};
//}}}
