<?php 

$tiddlyCfg['GettingStartedText'] = "在使用本工作區之前，您需要修改以下條目:\n* SiteTitle 及 SiteSubtitle：網站的標題和副標題，顯示於頁面上方<br />（在儲存變更後，將顯示於瀏覽器視窗的標題列）。\n* MainMenu：主選單（通常在頁面左側）。\n*DefaultTiddlers: 內含一些條目的標題，當用戶登入後開啟工作區後預設開啟的條目。.\n* AnonDefaultTiddlers: 內含一些條目的標題，可於造訪者尚未登入而開啟此工作區時顯示。  此條目理應包含可供登入的條目：[[登入|Login]]\n* 您可以在任何時候修改此工作區權限，只需開啟 [[用戶管理|Manage Users]] 及 [[權限管理|Permissions]] 等條目。\n<<ccEditWorkspace>>";

$ccT_msg['upload']['blockedConfig'] = "php 的組態檔 (php.ini) 中禁止透過 HTTP 上傳檔案，請與系統管理者聯絡。";
$ccT_msg['upload']['blockedFunction'] = "php 的組態檔 (php.ini) 中禁用 PHP function move_uploaded_file，請與系統管理者聯絡。";
$ccT_msg['upload']['noFile'] = "未接收到檔案，請查明原因";
$ccT_msg['upload']['emptyFile'] = "未選擇檔案";
$ccT_msg['upload']['specifyFileorHtml'] = "請指定檔名或提供 HTML";
$ccT_msg['upload']['typeNotSupported'] = "不支援上傳此類型檔案，請更改檔名再重傳。";
$ccT_msg['upload']['maxFileSize'] = "檔案大小上限: ".$tiddlyCfg['max_file_size']." bytes";
$ccT_msg['upload']['fileExists'] = "檔案已存在，請更改檔名再重傳。";
$ccT_msg['upload']['unknownError'] = "發生錯誤!";
$ccT_msg['upload']['uploadedTitle'] = "已上傳圖檔";
$ccT_msg['upload']['includeCode'] = "您可使用下列語法於 tiddlywiki 中顯示此圖檔：";

// All of the following variables only appear in the apache log file using the debug() function. 
$ccT_msg['debug']['loginRequest'] = "用戶要求登入：";
$ccT_msg['debug']['logoutRequest'] = "要求登出";
$ccT_msg['debug']['usernameAvailable'] = "有效的用戶名 ";
$ccT_msg['debug']['countIs'] = "Count is ";
$ccT_msg['debug']['reloadRequired'] = "需重新載入";
$ccT_msg['debug']['logBreaker'] = " << log breaker >>";
$ccT_msg['debug']['queryString'] = "QUERY_STRING: ";
$ccT_msg['debug']['workspaceName'] = "工作區：";
$ccT_msg['debug']['fileName'] = "檔名：";
$ccT_msg['debug']['postVars'] = "POST : ";
$ccT_msg['debug']['requestVars'] = "REQUEST : ";
$ccT_msg['debug']['workspaceNotSet'] = "未設定工作區";
$ccT_msg['debug']['actionIs'] = "ccT 的動作：";
$ccT_msg['debug']['delegatedSessionManagementEnabled'] = "已啟用 session 管理";
$ccT_msg['debug']['sessionExpired'] = "Session 已逾期";
$ccT_msg['debug']['setSession'] = "User set session ";
$ccT_msg['debug']['userRequiredInDb'] = "用戶須存在於資料庫中";
$ccT_msg['debug']['userExistsInDb'] = "資料庫中有此用戶";
$ccT_msg['debug']['userDoesNotExistInDb'] = "資料庫中無此用戶";
$ccT_msg['debug']['sessionWillExpire'] = "Session 逾期：";
$ccT_msg['debug']['sessionAddedToDb'] = "Session 已加入資料庫";
$ccT_msg['debug']['ldapFailNoPassword'] = "沒有密碼，LDAP 登入失敗";
$ccT_msg['debug']['ldapFailNoConnect'] = "未建立 LDAP 連線";
$ccT_msg['debug']['ldapMakingProgress'] = "LDAP 進行處理中"; 
?>