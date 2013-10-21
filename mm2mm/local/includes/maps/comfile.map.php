<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for comfile.dat

require_once("map.class.php"); 

class comfile_Map extends MedManMap{
	
	var $nick_name = "comfile";
	var $file_name = "comfile.dat";
	var $table_name = "mm2mm_comfile";
	var $id_field = "comfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'comfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'comfile_1',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'comfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'comfile_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'comfile_4',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'comfile_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'comfile_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'comfile_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'comfile_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'comfile_9',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'comfile_10',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'comfile_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'comfile_12',
    'type' => 'varchar(100)',
  ),
  13 => 
  array (
    'name' => 'comfile_13',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'comfile_14',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'comfile_15',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'comfile_16',
    'type' => 'varchar(100)',
  ),
);


}
?>