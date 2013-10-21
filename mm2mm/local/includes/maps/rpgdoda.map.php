<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for rpgdoda.dat

require_once("map.class.php"); 

class rpgdoda_Map extends MedManMap{
	
	var $nick_name = "rpgdoda";
	var $file_name = "rpgdoda.dat";
	var $table_name = "mm2mm_rpgdoda";
	var $id_field = "rpgdoda_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'rpgdoda_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'rpgdoda_1',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'rpgdoda_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'rpgdoda_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'rpgdoda_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'rpgdoda_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'rpgdoda_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'rpgdoda_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'rpgdoda_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'rpgdoda_9',
    'type' => 'varchar(100)',
  ),
);


}
?>