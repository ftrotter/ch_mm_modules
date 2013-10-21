<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for clncode.dat

require_once("map.class.php"); 

class clncode_Map extends MedManMap{
	
	var $nick_name = "clncode";
	var $file_name = "clncode.dat";
	var $table_name = "mm2mm_clncode";
	var $id_field = "clncode_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'clncode_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'clncode_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'clncode_2',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'clncode_3',
    'type' => 'varchar(100)',
  ),
  4 => 
  array (
    'name' => 'clncode_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'clncode_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'clncode_6',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'clncode_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'clncode_8',
    'type' => 'varchar(100)',
  ),
);


}
?>