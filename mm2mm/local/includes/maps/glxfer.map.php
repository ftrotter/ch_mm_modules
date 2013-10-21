<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for glxfer.dat

require_once("map.class.php"); 

class glxfer_Map extends MedManMap{
	
	var $nick_name = "glxfer";
	var $file_name = "glxfer.dat";
	var $table_name = "mm2mm_glxfer";
	var $id_field = "glxfer_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'glxfer_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'glxfer_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'glxfer_2',
    'type' => 'DATE',
  ),
  3 => 
  array (
    'name' => 'glxfer_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'glxfer_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'glxfer_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'glxfer_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'glxfer_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'glxfer_8',
    'type' => 'int(20)',
  ),
);


}
?>