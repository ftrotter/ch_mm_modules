<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for diagcode.dat

require_once("map.class.php"); 

class diagcode_Map extends MedManMap{
	
	var $nick_name = "diagcode";
	var $file_name = "diagcode.dat";
	var $table_name = "mm2mm_diagcode";
	var $id_field = "diagcode_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'diagcode_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'diagcode_1',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'diagcode_2',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'diagcode_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'diagcode_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'diagcode_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'diagcode_6',
    'type' => 'varchar(100)',
  ),
);


}
?>