
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET NAMES 'utf8' COLLATE 'utf8_unicode_ci';

CREATE TABLE `admin_of_workspace` (
  `username` varchar(255) NOT NULL,
  `workspace_name` varchar(100) NOT NULL
) ENGINE=MyISAM;

INSERT INTO `admin_of_workspace` (`username`, `workspace_name`) VALUES 
('admin', '');

CREATE TABLE `group` (
  `name` varchar(50) NOT NULL,
  `desc` mediumtext NOT NULL
) ENGINE=MyISAM;

CREATE TABLE `group_membership` (
  `user_id` varchar(255) NOT NULL,
  `group_name` varchar(50) NOT NULL
) ENGINE=MyISAM;

CREATE TABLE `login_session` (
  `user_id` varchar(255) NOT NULL,
  `session_token` varchar(150) NOT NULL COMMENT 'username+password+time',
  `expire` varchar(16) NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=MyISAM;

CREATE TABLE `permissions` (
  `read` int(1) NOT NULL,
  `insert` int(1) NOT NULL,
  `edit` int(1) NOT NULL,
  `delete` int(1) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `workspace_name` varchar(100) NOT NULL
) ENGINE=MyISAM;

CREATE TABLE `tiddler` (
  `id` int(11) NOT NULL auto_increment,
  `workspace_name` varchar(100) NOT NULL,
  `title` text NOT NULL,
  `body` mediumtext NOT NULL,
  `fields` text NOT NULL,
  `tags` text NOT NULL,
  `modifier` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `modified` varchar(12) NOT NULL,
  `created` varchar(12) NOT NULL,
  `revision` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 ;

INSERT INTO `tiddler` (`id`, `workspace_name`, `title`, `body`, `fields`, `tags`, `modifier`, `creator`, `modified`, `created`, `revision`) VALUES 
(6, '', 'GettingStarted', 'To get started with this workspace, you''ll need to modify the following tiddlers:\n* SiteTitle &amp; SiteSubtitle: The title and subtitle of the site, as shown above (after saving, they will also appear in the browser title bar)\n* MainMenu: The menu (usually on the left)\n* DefaultTiddlers: Contains the names of the tiddlers that you want to appear when the workspace is opened when a user is logged in.\n* AnonDefaultTiddlers: Contains the names of the tiddlers that you want to appear when the worksace is opened when a user who is not logged in.  This should contain  the login tiddler. [[Login]]\n* You can change the permission of this workspace at anytime by opening the [[Manage Users]] and [[Permissions]] tiddlers.<<ccEditWorkspace>>', '', '', 'ccTiddly', 'ccTiddly', '200802151654', '200712281715', 11),
(40, '', 'SiteTitle', 'ccTiddly', '', '', 'ccTiddly', 'ccTiddly', '200802151311', '200802151311', 0),
(42, '', 'SiteSubtitle', 'Provided by [[Osmosoft]] using TiddlyWiki', '', '', 'ccTiddly', 'ccTiddly', '200802151311', '200802151311', 0);

CREATE TABLE `tiddler_revisions` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `fields` text NOT NULL,
  `modified` varchar(128) NOT NULL default '',
  `modifier` varchar(255) NOT NULL default '',
  `revision` int(11) NOT NULL default '0',
  `tags` varchar(255) NOT NULL default '',
  `tiddler_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  AUTO_INCREMENT=37 ;

CREATE TABLE `user` (
  `username` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `long_name` varchar(100) NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM;

INSERT INTO `user` (`username`, `password`, `short_name`, `long_name`) VALUES 
('admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '', ''),
('username', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '', '') ;

CREATE TABLE `workspace` (
  `name` varchar(100) NOT NULL,
  `twLanguage` varchar(10) NOT NULL,
  `keep_revision` int(1) NOT NULL,
  `require_login` int(1) NOT NULL,
  `session_expire` int(10) NOT NULL,
  `tag_tiddler_with_modifier` int(1) NOT NULL,
  `char_set` varchar(10) NOT NULL,
  `hashseed` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `tiddlywiki_type` varchar(30) NOT NULL,
  `default_anonymous_perm` varchar(4) NOT NULL,
  `default_user_perm` varchar(4) NOT NULL,
  `rss_group` varchar(50) NOT NULL,
  `markup_group` varchar(50) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM;


INSERT INTO `workspace` (`name`, `twLanguage`, `keep_revision`, `require_login`, `session_expire`, `tag_tiddler_with_modifier`, `char_set`, `hashseed`, `status`, `tiddlywiki_type`, `default_anonymous_perm`, `default_user_perm`, `rss_group`, `markup_group`) VALUES 
('', 'en', 1, 0, 0, 0, 'utf8', '118229952', '', 'tiddlywiki', 'AUUU', 'AAAA', '', '');

CREATE TABLE `workspace_view` (
  `id` int(50) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `workspace` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  AUTO_INCREMENT=82 ;

CREATE TABLE `instance_history` (
	`id` VARCHAR( 20 ) NOT NULL ,
	`date` VARCHAR( 20 ) NOT NULL ,
	`version` VARCHAR( 50 ) NOT NULL ,
	`description` VARCHAR( 500 ) NOT NULL ,
	PRIMARY KEY ( `id`));

INSERT INTO `instance_history` (`id` ,`date` ,`version` ,`description`) VALUES ('', '', '1.7', '1.7 install.');	
