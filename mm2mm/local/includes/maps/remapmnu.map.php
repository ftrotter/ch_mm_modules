<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for remapmnu.dat

require_once("map.class.php"); 

class remapmnu_Map extends MedManMap{
	
	var $nick_name = "remapmnu";
	var $file_name = "remapmnu.dat";
	var $table_name = "mm2mm_remapmnu";
	var $id_field = "remapmnu_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'remapmnu_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'remapmnu_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'remapmnu_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'remapmnu_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'remapmnu_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'remapmnu_5',
    'type' => 'int(20)',
  ),
);


}
?>