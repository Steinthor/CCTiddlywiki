<?php 

$tiddlyCfg['GettingStartedText'] = "To get started with this workspace, you'll need to modify the following tiddlers:\n* SiteTitle &amp; SiteSubtitle: The title and subtitle of the site, as shown above (after saving, they will also appear in the browser title bar)\n* MainMenu: The menu (usually on the left)\n* DefaultTiddlers: Contains the names of the tiddlers that you want to appear when the workspace is opened when a user is logged in.\n* AnonDefaultTiddlers: Contains the names of the tiddlers that you want to appear when the worksace is opened when a user who is not logged in.  This should contain  the login tiddler. [[Login]]\n* You can change the permission of this workspace at anytime by opening the [[Manage Users]] and [[Permissions]] tiddlers.<<ccEditWorkspace>>";

$ccT_msg['upload']['blockedConfig'] = "HTTP file uploading is blocked in php configuration file (php.ini). Please, contact to server administrator.";
$ccT_msg['upload']['blockedFunction'] = "PHP function move_uploaded_file is blocked in php configuration file (php.ini). Please, contact to server administrator.";
$ccT_msg['upload']['noFile'] = "No file recieved, please check it was ";
$ccT_msg['upload']['emptyFile'] = "Empty file";
$ccT_msg['upload']['specifyFileorHtml'] = "Please specify a file name or provide HTML";
$ccT_msg['upload']['typeNotSupported'] = "File Type not supported";
$ccT_msg['upload']['maxFileSize'] = "Maximum file size limit: ".$tiddlyCfg['max_file_size']." bytes";
$ccT_msg['upload']['fileExists'] = "file already exists.  Please try again with a different file name.";
$ccT_msg['upload']['unknownError'] = "There were some errors!";
$ccT_msg['upload']['uploadedTitle'] = "Image Uploaded";
$ccT_msg['upload']['includeCode'] = "You can include this image into a tiddlywiki using the code below : ";

// All of the following variables only appear in the apache log file using the debug() function. 
$ccT_msg['debug']['loginRequest'] = "Login request from username : ";
$ccT_msg['debug']['logoutRequest'] = "Logout request received";
$ccT_msg['debug']['usernameAvailable'] = "Username is available ";
$ccT_msg['debug']['countIs'] = "Count is ";
$ccT_msg['debug']['reloadRequired'] = "A reload is required";
$ccT_msg['debug']['logBreaker'] = " << log breaker >>";
$ccT_msg['debug']['queryString'] = "QUERY_STRING: ";
$ccT_msg['debug']['workspaceName'] = "Workspace name is : ";
$ccT_msg['debug']['fileName'] = "Filename : ";
$ccT_msg['debug']['postVars'] = "POST : ";
$ccT_msg['debug']['requestVars'] = "REQUEST : ";
$ccT_msg['debug']['workspaceNotSet'] = "Workspace was not set.";
$ccT_msg['debug']['actionIs'] = "ccT action is : ";
$ccT_msg['debug']['delegatedSessionManagementEnabled'] = "Deligated session management is enabled";
$ccT_msg['debug']['sessionExpired'] = "SESSION has expired";
$ccT_msg['debug']['setSession'] = "User set session ";
$ccT_msg['debug']['userRequiredInDb'] = "User is required in db.";
$ccT_msg['debug']['userExistsInDb'] = "User exists in the database.";
$ccT_msg['debug']['userDoesNotExistInDb'] = "The user does not exist in the database.";
$ccT_msg['debug']['sessionWillExpire'] = "Session will expire : ";
$ccT_msg['debug']['sessionAddedToDb'] = "Session has been added to the database.";
$ccT_msg['debug']['ldapFailNoPassword'] = "LDAP login failed due to no password being present.";
$ccT_msg['debug']['ldapFailNoConnect'] = "LDAP connection could not be established.";
$ccT_msg['debug']['ldapMakingProgress'] = "LDAP making progress"; 
$ccT_msg['debug']['handleFileName'] = "file : handle : ";
?>