<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for errfile.dat

require_once("map.class.php"); 

class errfile_Map extends MedManMap{
	
	var $nick_name = "errfile";
	var $file_name = "errfile.dat";
	var $table_name = "mm2mm_errfile";
	var $id_field = "errfile_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'errfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'errfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'errfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'errfile_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'errfile_4',
    'type' => 'DATE',
  ),
  5 => 
  array (
    'name' => 'errfile_5',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'errfile_6',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'errfile_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'errfile_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'errfile_9',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'errfile_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'errfile_11',
    'type' => 'varchar(100)',
  ),
  12 => 
  array (
    'name' => 'errfile_12',
    'type' => 'int(20)',
  ),
  13 => 
  array (
    'name' => 'errfile_13',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'errfile_14',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'errfile_15',
    'type' => 'varchar(100)',
  ),
);


}
?>