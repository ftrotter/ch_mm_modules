<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for pcomfile.dat

require_once("map.class.php"); 

class pcomfile_Map extends MedManMap{
	
	var $nick_name = "pcomfile";
	var $file_name = "pcomfile.dat";
	var $table_name = "mm2mm_pcomfile";
	var $id_field = "pcomfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'pcomfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'pcomfile_1',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'pcomfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'pcomfile_3',
    'type' => 'varchar(100)',
  ),
  4 => 
  array (
    'name' => 'pcomfile_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'pcomfile_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'pcomfile_6',
    'type' => 'int(20)',
  ),
);


}
?>