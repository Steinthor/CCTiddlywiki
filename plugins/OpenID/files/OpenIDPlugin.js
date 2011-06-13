config.macros.OpenID={};
merge(config.macros.OpenID,{
	titleOpenID:"",
	buttonOpenIDText:"Login",
	buttonOpenIDToolTip:"Click to use OpenID Login" 
});

config.macros.OpenID.handler=function(place,macroName,params,wikifier,paramString,tiddler,errorMsg){
	var w = new Wizard();
	var me = config.macros.OpenID;
	w.createWizard(place,me.titleOpenID);
	w.addStep(null,"<!--<img width='150px' src='http://openid.net/wp-content/uploads/2007/10/openid_big_logo_text.png'/><br />--><input name='open_id_login' value='%0' size=40 style='background: rgb(255, 255, 255) url(http://www.openid.net/login-bg.gif) no-repeat scroll 0pt 50%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(0, 0, 0); padding-left: 18px;'/>".format([decodeURIComponent(cookieString(document.cookie).txtUserName)]));
	w.setButtons([
		{caption: me.buttonOpenIDText, tooltip: me.buttonOpenIDToolTip, onClick: function(){config.macros.OpenID.login(w);  } }
	]);
};

config.macros.OpenID.login = function (w) {
	var iframe = document.createElement("iframe");
	iframe.style.display = "none";
	iframe.src = url+"plugins/OpenID/files/openid/try_auth.php?action=verify&openid_identifier="+w.formElem.open_id_login.value;
	document.body.appendChild(iframe);
	iframe.onload = function() {
		// this is not working properly.
		if(iframe.src.indexOf("finish_auth.php")){
			window.location = iframe.src;	
		}
	};
	document.body.appendChild(iframe);
}

//}}}


