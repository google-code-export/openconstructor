-- phpMyAdmin SQL Dump
-- version 2.9.0
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 17, 2006 at 03:44 PM
-- Server version: 4.1.9
-- PHP Version: 4.3.9
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `catalogdocs`
-- 

DROP TABLE IF EXISTS `catalogdocs`;
CREATE TABLE `catalogdocs` (
  `node` int(11) NOT NULL default '0',
  `doc` int(11) NOT NULL default '0',
  PRIMARY KEY  (`node`,`doc`),
  KEY `doc` (`doc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `catalogdocs`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `catalognode`
-- 

DROP TABLE IF EXISTS `catalognode`;
CREATE TABLE `catalognode` (
  `id` int(10) unsigned NOT NULL default '0',
  `num` int(10) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `num` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `catalognode`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `catalogtree`
-- 

DROP TABLE IF EXISTS `catalogtree`;
CREATE TABLE `catalogtree` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `num` int(10) unsigned NOT NULL default '0',
  `parent` int(10) NOT NULL default '0',
  `next` int(10) NOT NULL default '0',
  `level` int(2) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `header` varchar(64) NOT NULL default '',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  `wcsgroup` int(10) unsigned NOT NULL default '0',
  `oauths` set('edittree','edittree.chmod','managetree','removetree') NOT NULL default '',
  `gauths` set('edittree','edittree.chmod','managetree','removetree') NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `num` (`num`,`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `catalogtree`
-- 

INSERT INTO `catalogtree` (`id`, `num`, `parent`, `next`, `level`, `name`, `header`, `wcsowner`, `wcsgroup`, `oauths`, `gauths`) VALUES 
(1, 0, 0, 0, 0, 'root', 'Root', 1, 1, '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `datasources`
-- 

DROP TABLE IF EXISTS `datasources`;
CREATE TABLE `datasources` (
  `ds_id` int(11) unsigned NOT NULL auto_increment,
  `ds_type` enum('htmltext','publication','event','gallery','article','textpool','guestbook','phpsource','file','hybrid','rating') NOT NULL default 'htmltext',
  `docs` int(6) unsigned NOT NULL default '0',
  `indexed` int(1) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `description` varchar(255) default NULL,
  `date` int(10) unsigned NOT NULL default '0',
  `internal` int(1) unsigned default '0',
  `locks` int(3) unsigned NOT NULL default '0',
  `code` text,
  `wcsowner` int(10) unsigned NOT NULL default '0',
  `wcsgroup` int(10) unsigned NOT NULL default '0',
  `oauths` set('createdoc','editdoc','editds','editds.chmod','publishdoc','removedoc','removeds') NOT NULL default '',
  `gauths` set('createdoc','editdoc','editds','editds.chmod','publishdoc','removedoc','removeds') NOT NULL default '',
  `docauths` set('editdoc','publishdoc','removedoc') NOT NULL default '',
  PRIMARY KEY  (`ds_id`),
  KEY `intl` (`internal`),
  KEY `ds_type` (`ds_type`,`internal`),
  KEY `docs` (`docs`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `datasources`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsarticle`
-- 

DROP TABLE IF EXISTS `dsarticle`;
CREATE TABLE `dsarticle` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `real_id` int(11) unsigned NOT NULL default '0',
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default '0',
  `header` varchar(255) NOT NULL default '',
  `intro` text,
  `related` varchar(255) default NULL,
  `date` int(10) unsigned NOT NULL default '0',
  `img_main` varchar(128) default NULL,
  `img_type` varchar(3) NOT NULL default '',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsarticle`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsarticlepages`
-- 

DROP TABLE IF EXISTS `dsarticlepages`;
CREATE TABLE `dsarticlepages` (
  `p_id` int(11) unsigned NOT NULL auto_increment,
  `id` int(11) unsigned NOT NULL default '0',
  `page` int(2) unsigned default '0',
  `header` varchar(255) NOT NULL default '',
  `content` text,
  PRIMARY KEY  (`p_id`),
  KEY `article` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsarticlepages`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsevent`
-- 

DROP TABLE IF EXISTS `dsevent`;
CREATE TABLE `dsevent` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `real_id` int(11) unsigned NOT NULL default '0',
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default NULL,
  `header` varchar(255) NOT NULL default '',
  `intro` text,
  `content` text,
  `date` int(10) unsigned NOT NULL default '0',
  `end_date` int(10) unsigned NOT NULL default '0',
  `place` text,
  `img_intro` varchar(128) default NULL,
  `img_main` varchar(128) default NULL,
  `img_type` varchar(3) NOT NULL default '',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsevent`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsfile`
-- 

DROP TABLE IF EXISTS `dsfile`;
CREATE TABLE `dsfile` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ds_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `description` varchar(255) default NULL,
  `basename` varchar(128) default NULL,
  `filename` varchar(255) default NULL,
  `type` varchar(5) NOT NULL default '',
  `size` int(11) unsigned NOT NULL default '0',
  `created` int(10) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`date`),
  KEY `created` (`created`),
  KEY `type` (`type`),
  KEY `basename` (`basename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsfile`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsgallery`
-- 

DROP TABLE IF EXISTS `dsgallery`;
CREATE TABLE `dsgallery` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `real_id` int(11) unsigned NOT NULL default '0',
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default NULL,
  `header` varchar(255) NOT NULL default '',
  `content` text,
  `date` int(10) unsigned NOT NULL default '0',
  `img_intro` varchar(128) default NULL,
  `img_main` varchar(128) default NULL,
  `img_type` varchar(3) NOT NULL default '',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsgallery`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsguestbook`
-- 

DROP TABLE IF EXISTS `dsguestbook`;
CREATE TABLE `dsguestbook` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `real_id` int(11) unsigned NOT NULL default '0',
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default NULL,
  `subject` varchar(255) NOT NULL default '',
  `author` varchar(128) default NULL,
  `email` varchar(255) default NULL,
  `html` text,
  `date` int(10) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsguestbook`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dshfields`
-- 

DROP TABLE IF EXISTS `dshfields`;
CREATE TABLE `dshfields` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ds_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `family` enum('primitive','document','array','datasource','tree','enum','file','rating') default NULL,
  `type` varchar(32) NOT NULL default '',
  `header` varchar(255) NOT NULL default '',
  `isreq` int(1) unsigned default NULL,
  `isown` int(1) unsigned default NULL,
  `fromds` int(11) unsigned default '0',
  `tree` int(11) unsigned default NULL,
  `enum_id` int(10) unsigned default NULL,
  `isarray` int(1) unsigned default NULL,
  `length` int(3) unsigned default NULL,
  `min` varchar(24) default NULL,
  `max` varchar(24) default NULL,
  `regex` varchar(128) default NULL,
  `types` varchar(128) default NULL,
  `img_bounds` varchar(32) default NULL,
  `allowedtags` varchar(255) default NULL,
  `default_val` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`),
  KEY `family` (`family`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dshfields`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dshtmltext`
-- 

DROP TABLE IF EXISTS `dshtmltext`;
CREATE TABLE `dshtmltext` (
  `id` int(11) unsigned NOT NULL default '0',
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default NULL,
  `noindex` int(1) unsigned NOT NULL default '0',
  `html` text,
  `intro` text,
  `date` int(10) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`ds_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `dshtmltext`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dshybrid`
-- 

DROP TABLE IF EXISTS `dshybrid`;
CREATE TABLE `dshybrid` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default '0',
  `header` varchar(255) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dshybrid`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsphpsource`
-- 

DROP TABLE IF EXISTS `dsphpsource`;
CREATE TABLE `dsphpsource` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ds_id` int(11) unsigned NOT NULL default '0',
  `header` varchar(255) NOT NULL default '',
  `source` text,
  `date` int(10) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsphpsource`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dspublication`
-- 

DROP TABLE IF EXISTS `dspublication`;
CREATE TABLE `dspublication` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `real_id` int(11) unsigned NOT NULL default '0',
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default '0',
  `main` int(1) unsigned NOT NULL default '0',
  `gallery` int(11) unsigned default NULL,
  `header` varchar(255) NOT NULL default '',
  `intro` text,
  `content` text,
  `date` int(10) unsigned NOT NULL default '0',
  `img_intro` varchar(128) default NULL,
  `img_main` varchar(128) default NULL,
  `img_type` varchar(3) NOT NULL default '',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dspublication`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsrating`
-- 

DROP TABLE IF EXISTS `dsrating`;
CREATE TABLE `dsrating` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ds_id` int(11) unsigned NOT NULL default '0',
  `hdoc` int(11) unsigned NOT NULL default '0',
  `raters` int(6) unsigned NOT NULL default '0',
  `rating` int(3) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `hdoc` (`ds_id`,`hdoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dsrating`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsratinglog`
-- 

DROP TABLE IF EXISTS `dsratinglog`;
CREATE TABLE `dsratinglog` (
  `id` int(11) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `fake` int(1) unsigned NOT NULL default '0',
  `active` int(1) unsigned NOT NULL default '0',
  `rating` int(10) unsigned NOT NULL default '0',
  `votes` int(6) unsigned NOT NULL default '1',
  `comment` text,
  `date` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `dsratinglog`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dstextpool`
-- 

DROP TABLE IF EXISTS `dstextpool`;
CREATE TABLE `dstextpool` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `real_id` int(11) unsigned NOT NULL default '0',
  `ds_id` int(11) unsigned NOT NULL default '0',
  `published` int(1) unsigned default NULL,
  `header` varchar(255) NOT NULL default '',
  `html` text,
  `date` int(10) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `dstextpool`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `enums`
-- 

DROP TABLE IF EXISTS `enums`;
CREATE TABLE `enums` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `header` varchar(255) NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  `wcsgroup` int(10) unsigned NOT NULL default '0',
  `oauths` set('addvalue','editenum','editenum.chmod','editvalue','removeenum','removevalue') NOT NULL default '',
  `gauths` set('addvalue','editenum','editenum.chmod','editvalue','removeenum','removevalue') NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `enums`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `enumvalues`
-- 

DROP TABLE IF EXISTS `enumvalues`;
CREATE TABLE `enumvalues` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `enum_id` int(10) unsigned NOT NULL default '0',
  `value` varchar(32) NOT NULL default '',
  `header` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `enum_id` (`enum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `enumvalues`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `hybriddatasources`
-- 

DROP TABLE IF EXISTS `hybriddatasources`;
CREATE TABLE `hybriddatasources` (
  `ds_id` int(11) unsigned NOT NULL default '0',
  `parent` int(11) unsigned NOT NULL default '0',
  `ds_key` varchar(128) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ds_id`),
  UNIQUE KEY `ds_key` (`ds_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `hybriddatasources`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `index`
-- 

DROP TABLE IF EXISTS `index`;
CREATE TABLE `index` (
  `ds_id` int(11) NOT NULL default '0',
  `ds_type` enum('publication','event','gallery','article','textpool','hybrid','guestbook','htmltext','file') NOT NULL default 'publication',
  `document_id` int(11) NOT NULL default '0',
  `header` varchar(255) NOT NULL default '',
  `content` text,
  `date` int(10) unsigned NOT NULL default '0',
  `rank` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `annotation` varchar(255) default NULL,
  PRIMARY KEY  (`ds_id`,`document_id`),
  FULLTEXT KEY `header` (`header`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `index`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `objects`
-- 

DROP TABLE IF EXISTS `objects`;
CREATE TABLE `objects` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ds_id` int(11) unsigned NOT NULL default '0',
  `ds_type` varchar(32) NOT NULL default '',
  `obj_type` varchar(32) NOT NULL default '',
  `tpl` int(10) unsigned default NULL,
  `cache_by_wc` int(1) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  `wcsgroup` int(10) unsigned NOT NULL default '0',
  `oauths` set('editobj','editobj.chmod','editobj.ds','editobj.tpl','removeobj') NOT NULL default '',
  `gauths` set('editobj','editobj.chmod','editobj.ds','editobj.tpl','removeobj') NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `description` varchar(255) default NULL,
  `date` int(10) unsigned NOT NULL default '0',
  `code` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `objects`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `siteobjects`
-- 

DROP TABLE IF EXISTS `siteobjects`;
CREATE TABLE `siteobjects` (
  `page_id` int(10) unsigned NOT NULL default '0',
  `obj_id` int(10) unsigned NOT NULL default '0',
  `rule` int(1) unsigned NOT NULL default '0',
  `observer` int(1) unsigned NOT NULL default '0',
  `crumbs` int(1) unsigned NOT NULL default '0',
  `block` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`page_id`,`obj_id`),
  KEY `page_block` (`page_id`,`block`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `siteobjects`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sitepages`
-- 

DROP TABLE IF EXISTS `sitepages`;
CREATE TABLE `sitepages` (
  `id` int(10) unsigned NOT NULL default '0',
  `linkto` int(10) unsigned NOT NULL default '0',
  `published` int(1) unsigned NOT NULL default '0',
  `locked` int(1) unsigned NOT NULL default '0',
  `tpl` int(10) unsigned default '0',
  `router` int(1) unsigned NOT NULL default '0',
  `caching` int(1) unsigned NOT NULL default '0',
  `cachelife` int(10) unsigned NOT NULL default '0',
  `cachegz` int(1) unsigned NOT NULL default '0',
  `addtitle` int(1) unsigned NOT NULL default '0',
  `robots` int(4) unsigned NOT NULL default '0',
  `contenttype` varchar(31) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `ctitle` varchar(255) default NULL,
  `css` varchar(127) default NULL,
  `location` varchar(255) default NULL,
  `cachevary` varchar(127) NOT NULL default '',
  `users` varchar(255) NOT NULL default '',
  `profilesinherit` int(1) unsigned NOT NULL default '0',
  `profilesload` int(10) unsigned NOT NULL default '0',
  `profilesdynamic` int(1) unsigned NOT NULL default '0',
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `sitepages`
-- 

INSERT INTO `sitepages` (`id`, `linkto`, `published`, `locked`, `tpl`, `router`, `caching`, `cachelife`, `cachegz`, `addtitle`, `robots`, `contenttype`, `title`, `ctitle`, `css`, `location`, `cachevary`, `users`, `profilesinherit`, `profilesload`, `profilesdynamic`, `meta_keywords`, `meta_description`) VALUES 
(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'text/html', '', NULL, NULL, NULL, '', '2', 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `sitetree`
-- 

DROP TABLE IF EXISTS `sitetree`;
CREATE TABLE `sitetree` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `num` int(10) unsigned NOT NULL default '0',
  `parent` int(10) NOT NULL default '0',
  `next` int(10) NOT NULL default '0',
  `level` int(2) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `header` varchar(128) NOT NULL default '',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  `wcsgroup` int(10) unsigned NOT NULL default '0',
  `oauths` set('editpage','editpage.caching','editpage.chmod','editpage.publish','editpage.security','editpage.uri','managesub','pageblock','pageblock.manage','removepage') NOT NULL default '',
  `gauths` set('editpage','editpage.caching','editpage.chmod','editpage.publish','editpage.security','editpage.uri','managesub','pageblock','pageblock.manage','removepage') NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `num` (`num`,`level`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `sitetree`
-- 

INSERT INTO `sitetree` (`id`, `num`, `parent`, `next`, `level`, `name`, `header`, `wcsowner`, `wcsgroup`, `oauths`, `gauths`) VALUES 
(1, 0, 0, 0, 0, 'root', 'Root', 1, 1, 'editpage,editpage.caching,editpage.chmod,editpage.publish,editpage.security,editpage.uri,managesub,pageblock,pageblock.manage,removepage', 'editpage,editpage.caching,editpage.publish,editpage.uri,managesub,pageblock,pageblock.manage');

-- --------------------------------------------------------

-- 
-- Table structure for table `strongtags`
-- 

DROP TABLE IF EXISTS `strongtags`;
CREATE TABLE `strongtags` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(25) NOT NULL default '',
  `rank` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `strongtags`
-- 

INSERT INTO `strongtags` (`id`, `tag`, `rank`) VALUES 
(1, 'h1', 5),
(2, 'h2', 4),
(3, 'h3', 3),
(4, 'h4', 2),
(5, 'a', 2),
(6, 'b', 2),
(7, 'strong', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `wcsgroups`
-- 

DROP TABLE IF EXISTS `wcsgroups`;
CREATE TABLE `wcsgroups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `auths` set('catalog','catalog.filter','catalog.tree','data','data.dsarticle','data.dsevent','data.dsfile','data.dsgallery','data.dsguestbook','data.dshtmltext','data.dshybrid','data.dsphpsource','data.dspublication','data.dsrating','data.dstextpool','data.enum','inlineedit','objects','objects.dsarticle','objects.dsevent','objects.dsfile','objects.dsgallery','objects.dsguestbook','objects.dshtmltext','objects.dshybrid','objects.dsmiscellany','objects.dsphpsource','objects.dspublication','objects.dsrating','objects.dssearch','objects.dstextpool','objects.dsusers','sitemap','tpls','tpls.dsarticle','tpls.dsevent','tpls.dsfile','tpls.dsgallery','tpls.dsguestbook','tpls.dshtmltext','tpls.dshybrid','tpls.dsmiscellany','tpls.dsphpsource','tpls.dspublication','tpls.dsrating','tpls.dssearch','tpls.dssite','tpls.dstextpool','users','users.manage') NOT NULL default '',
  `title` varchar(128) NOT NULL default '',
  `umask` varchar(64) default 'NULL',
  `builtin` int(1) unsigned NOT NULL default '0',
  `profile` int(11) unsigned NOT NULL default '0',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  `wcsgroup` int(10) unsigned NOT NULL default '0',
  `oauths` set('addmember','createuser','editgroup','editgroup.chmod','editgroup.umask','edituser','removegroup','removemember','removeuser') NOT NULL default '',
  `gauths` set('addmember','createuser','editgroup','editgroup.chmod','editgroup.umask','edituser','removegroup','removemember','removeuser') NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `wcsgroups`
-- 

INSERT INTO `wcsgroups` (`id`, `name`, `auths`, `title`, `umask`, `builtin`, `profile`, `wcsowner`, `wcsgroup`, `oauths`, `gauths`) VALUES 
(1, 'administrators', '', 'Administrators', 'edit,edit.pwd', 1, 0, 1, 1, '', ''),
(2, 'everyone', '', 'Everyone', 'edit,edit.pwd', 1, 0, 1, 1, '', ''),
(3, 'system', '', 'Open Constructor Users', 'edit,edit.pwd', 1, 0, 1, 1, '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `wcsmembership`
-- 

DROP TABLE IF EXISTS `wcsmembership`;
CREATE TABLE `wcsmembership` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `wcsmembership`
-- 

INSERT INTO `wcsmembership` (`group_id`, `user_id`) VALUES 
(1, 1),
(2, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `wcsusers`
-- 

DROP TABLE IF EXISTS `wcsusers`;
CREATE TABLE `wcsusers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `pwd` varchar(32) NOT NULL default '',
  `group_id` int(10) unsigned NOT NULL default '0',
  `autologin` varchar(32) NOT NULL default '',
  `email` varchar(40) NOT NULL default '',
  `name` varchar(128) NOT NULL default '',
  `active` int(1) unsigned NOT NULL default '0',
  `builtin` int(1) unsigned NOT NULL default '0',
  `profile` int(11) unsigned NOT NULL default '0',
  `oauths` set('edit','edit.chmod','edit.email','edit.expiry','edit.group','edit.pwd','edit.status','remove') NOT NULL default '',
  `gauths` set('edit','edit.chmod','edit.email','edit.expiry','edit.group','edit.pwd','edit.status','remove') NOT NULL default '',
  `lastlogin` int(10) unsigned default NULL,
  `expiry` int(10) unsigned NOT NULL default '0',
  `secretquest` varchar(64) NOT NULL default '',
  `secretans` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `wcsusers`
-- 

INSERT INTO `wcsusers` (`id`, `login`, `pwd`, `group_id`, `autologin`, `email`, `name`, `active`, `builtin`, `profile`, `oauths`, `gauths`, `lastlogin`, `expiry`, `secretquest`, `secretans`) VALUES 
(1, 'root', '', 1, 'c667d5ef251190397bd3c42040cc3120', '', 'Administrator', 1, 1, 0, '', '', 1163760203, 0, '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `wctemplate_blocks`
-- 

DROP TABLE IF EXISTS `wctemplate_blocks`;
CREATE TABLE `wctemplate_blocks` (
  `tpl_id` int(10) unsigned NOT NULL default '0',
  `block` varchar(32) NOT NULL default '',
  `run` tinyint(1) NOT NULL default '0',
  `pos` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`tpl_id`,`block`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `wctemplates`
-- 
DROP TABLE IF EXISTS `wctemplates`;
CREATE TABLE `wctemplates` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(32) NOT NULL default '',
  `wcsowner` int(10) unsigned NOT NULL default '0',
  `wcsgroup` int(10) unsigned NOT NULL default '0',
  `oauths` set('edittpl','edittpl.chmod','removetpl') NOT NULL default '',
  `gauths` set('edittpl','edittpl.chmod','removetpl') NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `has_error` tinyint(1) NOT NULL default '0',
  `tpl` text,
  `mockup` text,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
