<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for dlyfile.dat

require_once("map.class.php"); 

class dlyfile_Map extends MedManMap{
	
	var $nick_name = "dlyfile";
	var $file_name = "dlyfile.dat";
	var $table_name = "mm2mm_dlyfile";
	var $id_field = "dlyfile_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'dlyfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'dlyfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'dlyfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'dlyfile_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'dlyfile_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'dlyfile_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'dlyfile_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'dlyfile_7',
    'type' => 'varchar(100)',
  ),
  8 => 
  array (
    'name' => 'dlyfile_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'dlyfile_9',
    'type' => 'int(20)',
  ),
  10 => 
  array (
    'name' => 'dlyfile_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'dlyfile_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'dlyfile_12',
    'type' => 'int(20)',
  ),
  13 => 
  array (
    'name' => 'dlyfile_13',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'dlyfile_14',
    'type' => 'DATE',
  ),
  15 => 
  array (
    'name' => 'dlyfile_15',
    'type' => 'varchar(100)',
  ),
);


}
?>