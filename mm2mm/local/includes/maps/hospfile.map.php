<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for hospfile.dat

require_once("map.class.php"); 

class hospfile_Map extends MedManMap{
	
	var $nick_name = "hospfile";
	var $file_name = "hospfile.dat";
	var $table_name = "mm2mm_hospfile";
	var $id_field = "hospfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'hospfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'hospfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'hospfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'hospfile_3',
    'type' => 'int(20)',
  ),
);


}
?>