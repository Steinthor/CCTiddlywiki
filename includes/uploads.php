<?php
function check_vals(){
	global $upload_dirs, $err;
	if (!ini_get("file_uploads")){
			sendHeader("405");
		$err .= "HTTP file uploading is blocked in php configuration file (php.ini). Please, contact to server administrator."; return 0; 
	}
	$pos = strpos(ini_get("disable_functions"), "move_uploaded_file");
	if ($pos !== false){
			sendHeader("405");
	$err .= "PHP function move_uploaded_file is blocked in php configuration file (php.ini). Please, contact to server administrator."; return 0; 
	}

  	if (!isset($_FILES["userFile"])) {
  		$err .= "No file recieved, please check it was "; return 0;
  	}
  	elseif (!is_uploaded_file($_FILES['userFile']['tmp_name'])) {
  		$err .= "Empty file"; return 0; 
  	}
	return 1;
}

?>

