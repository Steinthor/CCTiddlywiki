<?php

$p = new Plugin('OpenID','0.1','simonmcmanus.com');
$p->addEvent("postSetLoginPerm", getcwd().'/plugins/OpenID/files/openid/common.php');
$data['tags'] = 'systemConfig';
$p->addTiddler($data, getcwd().'/plugins/OpenID/files/OpenIDPlugin.js');
$data1['tags'] = 'loginBox';
$p->addTiddler($data1, getcwd().'/plugins/OpenID/files/OpenID.tid');


?>