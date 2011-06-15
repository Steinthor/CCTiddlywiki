<?php
if(!isset($_REQUEST["standalone"]))
{
	include('pluginsLoaderClass.php');
	global $pluginsLoader;
	$pluginsLoader = new PluginsLoader();
	$pluginsLoader->includePlugins($cct_base);
	$pluginsLoader->runPlugins();
}
?>