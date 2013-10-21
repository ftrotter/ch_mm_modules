<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for encfile.dat

require_once("map.class.php"); 

class encfile_Map extends MedManMap{
	
	var $nick_name = "encfile";
	var $file_name = "encfile.dat";
	var $table_name = "mm2mm_encfile";
	var $id_field = "encfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'encfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'encfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'encfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'encfile_3',
    'type' => 'int(20)',
  ),
);


}
?>