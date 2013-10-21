<?php


require_once('mysql.php');


$mm2mm_sequences = "
CREATE TABLE `mm2mm_sequences` (
  `id` int(50) NOT NULL default '0')";

if(!mysql_query($mm2mm_sequences)){

	echo "could not build the mm2mm sequence tables";
	
}


$mm2mm_sequences = "
INSERT INTO `mm2mm_sequences` VALUES (500);
";

if(!mysql_query($mm2mm_sequences)){

	echo "could not set the mm2mm sequence tables";
	
}

$mm2mm_sequences = "
CREATE TABLE `mm2mm_import_map` (
  `old_id` int(11) NOT NULL default '0',
  `new_id` int(11) default NULL,
  `old_table_name` varchar(100) NOT NULL default '',
  `new_object_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`old_id`,`old_table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
";

if(!mysql_query($mm2mm_sequences)){

	echo "could not create the mm2mm import map";
	
}





?>

