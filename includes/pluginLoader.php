<?php

if ($_REQUEST["standalone"]!=1) 
{
	include('pluginsLoaderClass.php');
	global $pluginsLoader;
	$pluginsLoader = new PluginsLoader();
	$pluginsLoader->includePlugins($cct_base);
	$pluginsLoader->runPlugins();
}
?>