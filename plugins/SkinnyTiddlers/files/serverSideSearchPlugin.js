config.macros.search.onKeyPress = function(e)
{
	params = {};
	params.search = this.value;
	var resp = doHttp("POST"," handle/search.php", "?instance=" + encodeURIComponent(workspace)+"&search="+encodeURIComponent(this.value),null,null,null,searchCallback);
}

function searchCallback(status,params,responseText,xhr)
{
	var oldprompt=config.macros.search.label;

	// restore standard search label
	config.macros.search.label=oldprompt;
	var json = eval('(' + responseText + ')');

	// Loop through the objects returned by JSON
	story.closeAllTiddlers();
	for(_obj in json)
	{
		story.displayTiddler(null,json[_obj],1);
	}
}
