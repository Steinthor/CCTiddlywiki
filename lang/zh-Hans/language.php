<?php 

$tiddlyCfg['GettingStartedText'] = "在使用本工作区之前，您需要修改以下条目:\n* SiteTitle 及 SiteSubtitle：网站的标题和副标题，显示于页面上方<br />（在保存变更后，将显示于浏览器窗口的标题列）。\n* MainMenu：主菜单（通常在页面左侧）。\n*DefaultTiddlers: 内含一些条目的标题，当用户登入后开启工作区后预设开启的条目。.\n* AnonDefaultTiddlers: 内含一些条目的标题，可于造访者尚未登入而开启此工作区时显示。  此条目理应包含可供登入的条目：[[登入|Login]]\n* 您可以在任何时候修改此工作区权限，只需开启 [[用户管理|Manage Users]] 及 [[权限管理|Permissions]] 等条目。\n<<ccEditWorkspace>>";

$ccT_msg['upload']['blockedConfig'] = "php 的组态文件 (php.ini) 中禁止透过 HTTP 上传文件，请与系统管理者联络。";
$ccT_msg['upload']['blockedFunction'] = "php 的组态文件 (php.ini) 中禁用 PHP function move_uploaded_file，请与系统管理者联络。";
$ccT_msg['upload']['noFile'] = "未接收到文件，请查明原因";
$ccT_msg['upload']['emptyFile'] = "未选择文件";
$ccT_msg['upload']['specifyFileorHtml'] = "请指定文件名或提供 HTML";
$ccT_msg['upload']['typeNotSupported'] = "不支持上传此类型文件，请更改文件名再重传。";
$ccT_msg['upload']['maxFileSize'] = "文件大小上限: ".$tiddlyCfg['max_file_size']." bytes";
$ccT_msg['upload']['fileExists'] = "文件已存在，请更改文件名再重传。";
$ccT_msg['upload']['unknownError'] = "发生错误!";
$ccT_msg['upload']['uploadedTitle'] = "已上传图档";
$ccT_msg['upload']['includeCode'] = "您可使用下列语法于 tiddlywiki 中显示此图档：";

// All of the following variables only appear in the apache log file using the debug() function. 
$ccT_msg['debug']['loginRequest'] = "用户要求登入：";
$ccT_msg['debug']['logoutRequest'] = "要求登出";
$ccT_msg['debug']['usernameAvailable'] = "有效的用户名 ";
$ccT_msg['debug']['countIs'] = "Count is ";
$ccT_msg['debug']['reloadRequired'] = "需重新载入";
$ccT_msg['debug']['logBreaker'] = " << log breaker >>";
$ccT_msg['debug']['queryString'] = "QUERY_STRING: ";
$ccT_msg['debug']['workspaceName'] = "工作区：";
$ccT_msg['debug']['fileName'] = "文件名：";
$ccT_msg['debug']['postVars'] = "POST : ";
$ccT_msg['debug']['requestVars'] = "REQUEST : ";
$ccT_msg['debug']['workspaceNotSet'] = "未设定工作区";
$ccT_msg['debug']['actionIs'] = "ccT 的动作：";
$ccT_msg['debug']['delegatedSessionManagementEnabled'] = "已启用 session 管理";
$ccT_msg['debug']['sessionExpired'] = "Session 已逾期";
$ccT_msg['debug']['setSession'] = "User set session ";
$ccT_msg['debug']['userRequiredInDb'] = "用户须存在于数据中";
$ccT_msg['debug']['userExistsInDb'] = "数据库中有此用户";
$ccT_msg['debug']['userDoesNotExistInDb'] = "数据库中无此用户";
$ccT_msg['debug']['sessionWillExpire'] = "Session 逾期：";
$ccT_msg['debug']['sessionAddedToDb'] = "Session 已加入数据库";
$ccT_msg['debug']['ldapFailNoPassword'] = "没有密码，LDAP 登入失败";
$ccT_msg['debug']['ldapFailNoConnect'] = "未建立 LDAP 连线";
$ccT_msg['debug']['ldapMakingProgress'] = "LDAP 进行处理中"; 
?>