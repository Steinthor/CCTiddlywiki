<?php 
$cct_base = "../";
include_once($cct_base."includes/header.php");
debug($_SERVER['PHP_SELF'], "handle");	
$result =  db_workspace_selectAllPublic();
echo "[";
while ($row = db_fetch_assoc($result)) { 		
	if ($row['name']!=""){
		$a .= "'".$row['name']."'".",";
	} 	
}

echo substr($a,0,strlen($a)-1); 
echo "]";
?>