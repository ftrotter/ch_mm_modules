<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for clihist.dat

require_once("map.class.php"); 

class clihist_Map extends MedManMap{
	
	var $nick_name = "clihist";
	var $file_name = "clihist.dat";
	var $table_name = "mm2mm_clihist";
	var $id_field = "clihist_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'clihist_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'clihist_1',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'clihist_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'clihist_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'clihist_4',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'clihist_5',
    'type' => 'DATE',
  ),
  6 => 
  array (
    'name' => 'clihist_6',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'clihist_7',
    'type' => 'DATE',
  ),
  8 => 
  array (
    'name' => 'clihist_8',
    'type' => 'varchar(100)',
  ),
  9 => 
  array (
    'name' => 'clihist_9',
    'type' => 'int(20)',
  ),
  10 => 
  array (
    'name' => 'clihist_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'clihist_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'clihist_12',
    'type' => 'varchar(100)',
  ),
);


}
?>