

// ccStats //

//{{{
	
config.macros.ccStats={};	
	
config.macros.ccStats.handler = function(place,macroName,params,wikifier,paramString,tiddler){
	var params;
	params.place = place;
	doHttp('POST',url+'/handle/workspaceAdmin.php','action=LISTWORKSPACES',null,null,null,config.macros.ccStats.listWorkspaces,params);
}

config.macros.ccStats.simpleEncode = function(valueArray,maxValue){
	var simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var chartData = ['s:'];
	  for (var i = 0; i < valueArray.length; i++){
	    var currentValue = valueArray[i];
	    if (!isNaN(currentValue) && currentValue >= 0){
	    	chartData.push(simpleEncoding.charAt(Math.round((simpleEncoding.length-1) * currentValue / maxValue)));
	    }else{
	      chartData.push('_');
	    }
	  }
	return chartData.join('');
}

config.macros.ccStats.max = function(array){
	return Math.max.apply(Math, array);
}

config.macros.ccStats.dataCallback = function(status,params,responseText,uri,xhr){
	me = config.macros.ccStats;
	if(xhr.status==401){
		createTiddlyElement(params.container, "h4", null, null, me.errorPermissionDenied.format([params.title], [workspace]));
		return false;
	}
	var res = eval("[" + responseText + "]");
	var d=[];
	var l="";
	for(var c=0; c<res.length; c++){
		d[c]= res[c].hits;
		l+=res[c].date+"|";
	}
	var maxValue = config.macros.ccStats.max(d);
 	params.gData = config.macros.ccStats.simpleEncode(d,maxValue);
	params.XLabel = l.substring(0, l.length -1);
	params.YLabel = "0|"+maxValue+"|";
	var image = 'http://chart.apis.google.com/chart?cht=lc&chs=100x75&chd='+params.gData+'&chxt=x,y&chxl=0:||1:|';
	var div = createTiddlyElement(params.container, "div", null, "div_button");
	setStylesheet(".div_button:hover{opacity:0.7; cursor: pointer} .div_button{ width:100%; padding:5px;color:#555;background-color:white;} ", "DivButton");
	div.onclick = function(){
		var full = "http://chart.apis.google.com/chart?cht=lc&chs=800x375&chd="+params.gData+"&chxt=x,y&chxl=1:|"+params.YLabel+"0:|"+params.XLabel+"&chf=c,lg,90,EEEEEE,0.5,ffffff,20|bg,s,FFFFFF&&chg=10.0,10.0&";
		setStylesheet(
		"#errorBox .button{padding:0.5em 1em; border:1px solid #222; background-color:#ccc; color:black; margin-right:1em;}\n"+
		"html > body > #backstageCloak{height:"+window.innerHeight*2+"px;}"+
		"#errorBox{border:1px solid #ccc;background-color: #fff; color:#111;padding:1em 2em; z-index:9999;}",'errorBoxStyles');
		var box = document.getElementById('errorBox') || createTiddlyElement(document.body,'div','errorBox');
		box.innerHTML =  "<a style='float:right' href='javascript:onclick=ccTiddlyAdaptor.hideError()'>"+ccTiddlyAdaptor.errorClose+"</a><h3>"+params.title+"</h3><br />";
		box.style.position = 'absolute';
		box.style.height= "460px";
		box.style.width= "800px";
		var img = createTiddlyElement(box, "img");
		img.src = full;
		ccTiddlyAdaptor.center(box);
		ccTiddlyAdaptor.showCloak();
	}
	var img = createTiddlyElement(div, "h2", null, null, params.title);
	var img = createTiddlyElement(div, "img");
	img.src = image;
	var span = createTiddlyElement(div, "div", null, "graph_label", params.desc);
	setStylesheet(".graph_label{  position:relative; width:300px; top:-80px; left:130px;}");
}

config.macros.ccStats.switchWorkspace = function(params){
	removeChildren(params.container);
	config.macros.ccStats.refresh(params);	
}

config.macros.ccStats.refresh = function(params){
	var me = config.macros.ccStats;
	var select = params.w.formElem.workspaces;
	if(select[select.selectedIndex].value!="" && select.selectedIndex.value!=undefined )
		workspace = select[select.selectedIndex].value;
	params ={ container: params.container, url: window.url+"/handle/stats.php?graph=minute&workspace="+workspace,title:me.graph20MinsTitle, desc:me.graph20MinsDesc};
	doHttp('GET',params.url,null, null, null, null, config.macros.ccStats.dataCallback,params);
	params ={ container:params.container, url:  window.url+"/handle/stats.php?graph=hour&workspace="+workspace,title:me.graph24HourTitle, desc:me.graph24HourDesc};
	doHttp('GET',params.url,null, null, null, null, config.macros.ccStats.dataCallback,params);
	params ={ container: params.container, url:  window.url+"/handle/stats.php?graph=day&workspace="+workspace,title:me.graph7DaysTitle, desc:me.graph7DaysDesc};
	doHttp('GET',params.url,null, null, null, null, config.macros.ccStats.dataCallback,params);
	params ={ container: params.container, url:  window.url+"/handle/stats.php?graph=month&workspace="+workspace,title:me.graph5MonthsTitle, desc:me.graph5MonthsDesc};
	doHttp('GET',params.url,null, null, null, null, config.macros.ccStats.dataCallback,params);	
}

config.macros.ccStats.listWorkspaces = function(status,params,responseText,uri,xhr){
	params.container=createTiddlyElement(null, "div", "container");
	var me = config.macros.ccStats;
	var w = new Wizard();
	w.createWizard(params.place,me.stepTitle);
	w.addStep(null, "<select name='workspaces'></select><input name='stats_hol' type='hidden'></input>");
	var s = w.formElem.workspaces;	
	s.onchange = function(){config.macros.ccStats.switchWorkspace(params) ;};
	var workspaces = eval('[ '+responseText+' ]');
	for(var d=0; d < workspaces.length; d++){
		var i = createTiddlyElement(s,"option",null,null,workspaces[d]);
		i.value = workspaces[d];
		if (workspace == workspaces[d]){
			i.selected = true;
		}
	}
	params.w = w; 
	w.formElem.stats_hol.parentNode.appendChild(params.container);
	config.macros.ccStats.refresh(params);
}
//}}}
