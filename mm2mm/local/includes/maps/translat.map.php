<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for translat.dat

require_once("map.class.php"); 

class translat_Map extends MedManMap{
	
	var $nick_name = "translat";
	var $file_name = "translat.dat";
	var $table_name = "mm2mm_translat";
	var $id_field = "translat_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'translat_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'translat_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'translat_2',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'translat_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'translat_4',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'translat_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'translat_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'translat_7',
    'type' => 'varchar(100)',
  ),
);


}
?>