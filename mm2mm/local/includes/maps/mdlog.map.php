<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for mdlog.dat

require_once("map.class.php"); 

class mdlog_Map extends MedManMap{
	
	var $nick_name = "mdlog";
	var $file_name = "mdlog.dat";
	var $table_name = "mm2mm_mdlog";
	var $id_field = "mdlog_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'mdlog_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'mdlog_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'mdlog_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'mdlog_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'mdlog_4',
    'type' => 'DATE',
  ),
  5 => 
  array (
    'name' => 'mdlog_5',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'mdlog_6',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'mdlog_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'mdlog_8',
    'type' => 'varchar(100)',
  ),
);


}
?>