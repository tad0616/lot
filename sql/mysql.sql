CREATE TABLE `lot` (
  `lot_sn` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `lot_title` varchar(255) NOT NULL default '' COMMENT '主題',
  `lot_content` text NOT NULL COMMENT '說明',
  `lot_teacher` varchar(255) NOT NULL default '' COMMENT '指導者',
  `lot_uid` mediumint(9) unsigned NOT NULL default '0' COMMENT '開設者',
  `lot_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '開設日期',
  `lot_col` text NOT NULL COMMENT '欄位設定',
PRIMARY KEY  (`lot_sn`)
) ENGINE=MyISAM;

CREATE TABLE `lot_data` (
  `lot_data_sn` mediumint(9) unsigned NOT NULL auto_increment COMMENT '資料編號',
  `lot_sn` smallint(6) unsigned NOT NULL COMMENT '編號',
  `user` varchar(255) NOT NULL default '' COMMENT '用戶',
  `log_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '紀錄時間',
  `from_ip` varchar(255) NOT NULL default '' COMMENT 'IP',
PRIMARY KEY  (`lot_data_sn`)
) ENGINE=MyISAM;

CREATE TABLE `lot_data_center` (
  `mid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '模組編號',
  `col_name` varchar(100) NOT NULL default '' COMMENT '欄位名稱',
  `col_sn` mediumint(9) unsigned NOT NULL default 0 COMMENT '欄位編號',
  `data_name` varchar(100) NOT NULL default '' COMMENT '資料名稱',
  `data_value` text NOT NULL COMMENT '儲存值',
  `data_sort` mediumint(9) unsigned NOT NULL default 0 COMMENT '排序',
  PRIMARY KEY (`mid`,`col_name`,`col_sn`,`data_name`,`data_sort`)
) ENGINE=MyISAM;

CREATE TABLE `lot_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `col_name` varchar(255) NOT NULL default '' COMMENT '欄位名稱',
  `col_sn` smallint(5) unsigned NOT NULL default 0 COMMENT '欄位編號',
  `sort` smallint(5) unsigned NOT NULL default 0 COMMENT '排序',
  `kind` enum('img','file') NOT NULL default 'img' COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL default '' COMMENT '檔案類型',
  `file_size` int(10) unsigned NOT NULL default 0 COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint(8) unsigned NOT NULL default 0 COMMENT '下載人次',
  `original_filename` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
  `hash_filename` varchar(255) NOT NULL default '' COMMENT '加密檔案名稱',
  `sub_dir` varchar(255) NOT NULL default '' COMMENT '檔案子路徑',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM;

