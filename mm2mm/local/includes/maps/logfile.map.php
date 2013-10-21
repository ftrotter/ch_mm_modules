<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for logfile.dat

require_once("map.class.php"); 

class logfile_Map extends MedManMap{
	
	var $nick_name = "logfile";
	var $file_name = "logfile.dat";
	var $table_name = "mm2mm_logfile";
	var $id_field = "logfile_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'logfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'logfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'logfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'logfile_3',
    'type' => 'varchar(100)',
  ),
  4 => 
  array (
    'name' => 'logfile_4',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'logfile_5',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'logfile_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'logfile_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'logfile_8',
    'type' => 'varchar(100)',
  ),
  9 => 
  array (
    'name' => 'logfile_9',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'logfile_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'logfile_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'logfile_12',
    'type' => 'varchar(100)',
  ),
  13 => 
  array (
    'name' => 'logfile_13',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'logfile_14',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'logfile_15',
    'type' => 'varchar(100)',
  ),
);


}
?>