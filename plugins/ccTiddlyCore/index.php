<?php

$p = new Plugin('ccTiddlyCore','0.1','simonmcmanus.com'); 
$p->addTiddlersFolder(getcwd().'/plugins/ccTiddlyCore/files'); 
$p->addRecipe(getcwd().'/plugins/ccTiddlyCore/files/core.recipe'); 

?>