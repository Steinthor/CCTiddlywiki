<?php 
$u = (isset($_REQUEST['cctuser'])?$_REQUEST['cctuser']:$_POST['cctuser']); 
$p = (isset($_REQUEST['cctpass'])?$_REQUEST['cctpass']:$_POST['cctpass']);
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	
if(isset($u) && isset($p))	
{	
	debug($ccT_msg['debug']['loginRequest'].$u, "login");
	user_login(formatParametersPOST($u),formatParametersPOST($p));
}
if (isset($_POST['logout']) || isset($_REQUEST['logout']))
{
	debug($ccT_msg['debug']['logoutRequest'], "login");
	user_logout();
}
?>