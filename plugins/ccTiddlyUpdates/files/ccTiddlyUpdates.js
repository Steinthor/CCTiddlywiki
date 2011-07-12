/***
|Name|ccTiddlyUpdates for CCTW|
|Source|http://sthor.tiddlyspot.com/#AjaxPluginForCCTW|
|Version|0.0.6|
|Author|[[Steinþór Jasonarson]]|
|Code borrowed/copied from|Akash Mehta - http://articles.sitepoint.com/article/ajax-jquery/3|
|License|unknown|
|~CoreVersion|2.6.2|
|Type|plugin|
|Description|When another user updates/creates a tiddler, the tiddler is automatically added to the wiki.|
NOTE: This isn't truly finished, only basic info is grabbed from the server, still in need of development.

!!!Todo:
*Add Modified time to the data grabbed from the server (server already posts it).
*encode the displayMessage so that it links to the tiddler.
*remove the alert from CCTW that you can't edit tiddlers anymore and allow people to save.
*remove the need to manually inputting you database information, must be some php script I can call..
*See if it's possible to keep more accurate time for tiddlers.

!!!Revisions
<<<
2010.08.05 [0.0.1] After getting the code to kind of working, posted it as a plugin on the internetz, yay.
2010.08.06 [0.0.2] Added fields to data grabbed from the server.
2010.08.06 [0.0.3] Added revisions to data grabbed from the server.
2010.08.09 [0.0.4] Added ability to send to displayCommentPlugin if it's installed.
2011.07.11 [0.0.6] changed it to a CCTW update plugin.
<<<
!!!Javascript Code
***/
//{{{
version.extensions.ccTiddlyUpdates= { major: 0, minor: 0, revision: 6, date: new Date(2010,8,9)};
//}}}
//{{{
Story.prototype.onTiddlerMouseOver_orig = Story.prototype.onTiddlerMouseOver;
Story.prototype.onTiddlerMouseOver = function(e)
{
  config.extensions.ccTiddlyUpdates();
  return Story.prototype.onTiddlerMouseOver_orig.apply(this);
}
timeStamp01 = new Date()
ccLastUpdated = "";
config.extensions.ccTiddlyUpdates = function ()
{
  updateMsg = function () 
  {
    jQuery.post(window.url+"plugins/ccTiddlyUpdates/ccTiddlyUpdates.php",{ time: timeStamp01.convertToYYYYMMDDHHMM() }, function(xml) 
    {
		if(jQuery("status",xml).text() == "2") {
			clearMessage();
			return;
		}
		jQuery("message",xml).each(function(id) {
			message = jQuery("message",xml).get(id);
			if(jQuery("modifier",message).text() == config.options.txtUserName) return; //you don't need to see your own changes..
			ccdirty = store.isDirty();
			if(story.isDirty(jQuery("title",message).text())) {
				displayMessage("Someone has updated this tiddler:"+jQuery("title",message).text()+".  Close it, then re-edit it");
				return;
			}
			var newT = store.createTiddler(jQuery("title",message).text());
			newT.text=jQuery("content",message).text();
			newT.modifier=jQuery("modifier",message).text();
			newT.tags=jQuery("tags",message).text().readBracketedList();
			fieldArray = jQuery("fields",message).text().split("' ");

			for (var i=0; i<fieldArray.length; i++) {
				if(fieldArray[i]) {
					prefield = fieldArray[i].split("=");
					newT.fields[prefield[0]] = prefield[1].substring(1,prefield[1].length);
				}
			}
			store.addTiddler(newT);
			store.notify(newT.title, false);
			story.refreshTiddler(newT.title,1,true);
			if (!ccdirty)
				store.setDirty(false);
			if(ccLastUpdated != newT.title) {
				if (!version.extensions.displayCommentsPlugin) 
				{
					displayMessage(newT.modifier+" has modified: "+newT.title);
				} else 
					displayComment(newT.modifier+" has modified: "+newT.title);
					ccLastUpdated = newT.title;
			}
		});
		store.notifyAll();
	});
  }
  timeStamp02 = new Date();
  if(timeStamp02-timeStamp01 > 60000)
  {
	updateMsg();
    timeStamp01 = timeStamp02;
  }
}
//}}}