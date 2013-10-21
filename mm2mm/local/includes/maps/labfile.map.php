<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for labfile.dat

require_once("map.class.php"); 

class labfile_Map extends MedManMap{
	
	var $nick_name = "labfile";
	var $file_name = "labfile.dat";
	var $table_name = "mm2mm_labfile";
	var $id_field = "labfile_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'labfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'labfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'labfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'labfile_3',
    'type' => 'varchar(100)',
  ),
  4 => 
  array (
    'name' => 'labfile_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'labfile_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'labfile_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'labfile_7',
    'type' => 'varchar(100)',
  ),
);


}
?>