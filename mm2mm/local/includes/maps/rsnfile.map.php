<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for rsnfile.dat

require_once("map.class.php"); 

class rsnfile_Map extends MedManMap{
	
	var $nick_name = "rsnfile";
	var $file_name = "rsnfile.dat";
	var $table_name = "mm2mm_rsnfile";
	var $id_field = "rsnfile_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'user_number',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'reason_number',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'description',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'length_in_slots',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'encounter_form_number',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'clinical_group',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'class',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'recall_format_number',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'associated_provider_number',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'restricted_by_speciality',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'rsnfile_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'rsnfile_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'rsnfile_12',
    'type' => 'int(20)',
  ),
  13 => 
  array (
    'name' => 'rsnfile_13',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'rsnfile_14',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'rsnfile_15',
    'type' => 'varchar(100)',
  ),
);


}
?>