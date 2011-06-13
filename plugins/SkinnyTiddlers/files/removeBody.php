<?php
echo 'AUTO LOAD IS : ';
print_r($autoLoad);
if(isset($autoLoad[$t['title']])) {
 	echo 'LOAD ME : ';
} else {
	echo 'LOAD ME SKINNILY';
	$t['body'] = "";
	
}

/*
if($autoLoad[$t['title']] != '') {
	$t['body'] = "";
}

*/
?>
