<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for mapfile.dat

require_once("map.class.php"); 

class mapfile_Map extends MedManMap{
	
	var $nick_name = "mapfile";
	var $file_name = "mapfile.dat";
	var $table_name = "mm2mm_mapfile";
	var $id_field = "mapfile_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'mapfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'mapfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'mapfile_2',
    'type' => 'DATE',
  ),
  3 => 
  array (
    'name' => 'mapfile_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'mapfile_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'mapfile_5',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'mapfile_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'mapfile_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'mapfile_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'mapfile_9',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'mapfile_10',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'mapfile_11',
    'type' => 'int(20)',
  ),
);


}
?>