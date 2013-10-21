--
-- Table structure for table `import_map`
--

CREATE TABLE IF NOT EXISTS `import_map` (
  `old_id` int(11) NOT NULL default '0',
  `new_id` int(11) default NULL,
  `old_table_name` varchar(100) NOT NULL default '',
  `new_object_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`old_id`,`old_table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `mm2mm_import_map`
--

CREATE TABLE IF NOT EXISTS `mm2mm_import_map` (
  `mm2mm_import_map_id` int(10) NOT NULL,
  `medman_id` int(10) NOT NULL,
  `medman_file` varchar(50) NOT NULL,
  `medman_field_group` varchar(50) NOT NULL,
  `mirrormed_id` int(10) NOT NULL,
  `mirrormed_table` varchar(50) NOT NULL,
  `data_crc` int(10) NOT NULL,
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `initial_import_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`mm2mm_import_map_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

