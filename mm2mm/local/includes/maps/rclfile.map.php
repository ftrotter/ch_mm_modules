<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for rclfile.dat

require_once("map.class.php"); 

class rclfile_Map extends MedManMap{
	
	var $nick_name = "rclfile";
	var $file_name = "rclfile.dat";
	var $table_name = "mm2mm_rclfile";
	var $id_field = "rclfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'rclfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'rclfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'rclfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'rclfile_3',
    'type' => 'int(20)',
  ),
);


}
?>