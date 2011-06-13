<?php
$m = new Plugin('seo','0.1','simonmcmanus.com');
$m->addEvent("preSave", dirname(getcwd()).'/plugins/seo/files/createHtml.php');
$m->addEvent("preRename", dirname(getcwd()).'/plugins/seo/files/createHtml.php');
$m->addEvent("returnNotFound", getcwd().'/plugins/seo/files/checkUrl.php');
$m->addTiddler(null, getcwd()."/plugins/seo/files/generate.js");
?>