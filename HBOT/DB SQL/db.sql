CREATE TABLE `adminus` (
	`id` int(11) AUTO_INCREMENT,
	`login` varchar(100),
	`pass` varchar(200),
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `adminset` (
	`id` int(11) AUTO_INCREMENT,
	
	`msite` varchar (50),
	`bsite` varchar (50),	
	
	`email` varchar(50),	
	`botname` varchar(50),
	`beg_date` int(11),
	
	`dmin` DECIMAL(13,2),
	`dmax` DECIMAL(13,2),
	
	`wmin` DECIMAL(13,2),
	`wmax` DECIMAL(13,2),
	
	`ref1` DECIMAL(13,2),
	`ref2` DECIMAL(13,2),
	`ref3` DECIMAL(13,2),

	`cBTC` DECIMAL(13,2),
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `users` (
	`id` int(11) AUTO_INCREMENT,
	`chat_id` double UNIQUE,
	`username` varchar(100) DEFAULT '',
	`upline_id` double DEFAULT '0',
	`time` int(11),
	`lang` varchar(5) DEFAULT 'EN',
	`state` varchar(25) DEFAULT 'menu',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `ubalance` (
    `id` int(11) AUTO_INCREMENT,
	`chat_id` double UNIQUE,
	`bal` DECIMAL(13,2) DEFAULT '0',
	`wbal` DECIMAL(13,2) DEFAULT '0',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `wallets` (
	`id` int(11) AUTO_INCREMENT,
	`chat_id` double UNIQUE,
	`PM` varchar(40)  DEFAULT '',
	`AC` varchar(40)  DEFAULT '',
	`PY` varchar(40)  DEFAULT '',
	`BTC` varchar(40) DEFAULT '',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `poten_usd` (
	`id` int(11) AUTO_INCREMENT,
	`chat_id` double,
	`plan_id` int(11),
	`amount` DECIMAL(13,2),
	`paym_sys` varchar(10),	
	`descr` varchar(100),
	`trans_id` varchar(100) DEFAULT '',
    `time` int(11),
	`status` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `poten_BTC` (
	`id` int(11) AUTO_INCREMENT,
	`chat_id` double,
	`plan_id` int(11),
	`amount` DECIMAL(13,8),
	`wallet_to` varchar(40) DEFAULT '',
	`descr` varchar(100),
	`trans_id` varchar(100) DEFAULT '',	
    `time` int(11),
	`status` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `depos` (
	`id` int(11) AUTO_INCREMENT,
	`chat_id` double,
	`pot_id` int(11),
	`plan_id` int(11),
	`amount` DECIMAL(13,2),
	`paym_sys` varchar(10),
	`time` int(11),
	`lpt` int(11),
	`earned` DECIMAL(13,2) DEFAULT '0',
	`p_num` int(11) DEFAULT '0',
	`is_active` tinyint(1) DEFAULT '1',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `plans` (
	`id` int(11) AUTO_INCREMENT,
	`name` varchar(50) DEFAULT '',
	`descr` varchar(255) DEFAULT '',
	`pers` DECIMAL(13,2) DEFAULT '0',
	`period` int(11) DEFAULT '0',
	`term` int(11) DEFAULT '0',
	`numofpaym` int(11) DEFAULT '0',
	`bonus` DECIMAL(13,2) DEFAULT '0',
	`ret_depo` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `ref_paid` (
	`id` int(11) AUTO_INCREMENT,
	`chat_id` double,
	`depo_id` int(11),
	`amount` DECIMAL(13,2),
	`time` int(11) DEFAULT '0',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `with_req` (
	`id` int(11) AUTO_INCREMENT,
	`chat_id` double,
	`amount` DECIMAL(13,2),
	`comm` DECIMAL(13,2),	
	`paym_sys` varchar(10),
	`time` int(11) DEFAULT '0',
	`trans_id` varchar(100) DEFAULT '',
	`status` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

CREATE TABLE `fstat` (
	`id` int(11) AUTO_INCREMENT,	
	`days` int(11),
	`users` int(11),	
	`invest` DECIMAL(13,2),
	`with` DECIMAL(13,2),
	PRIMARY KEY (`id`)
)ENGINE  = InnoDB CHARACTER SET=utf8;

