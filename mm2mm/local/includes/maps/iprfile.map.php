<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for iprfile.dat

require_once("map.class.php"); 

class iprfile_Map extends MedManMap{
	
	var $nick_name = "iprfile";
	var $file_name = "iprfile.dat";
	var $table_name = "mm2mm_iprfile";
	var $id_field = "iprfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'user_number',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'insured_number',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'last_name',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'first_name',
    'type' => 'varchar(100)',
  ),
  4 => 
  array (
    'name' => 'middle_initial',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'address',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'city',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'state',
    'type' => 'varchar(100)',
  ),
  8 => 
  array (
    'name' => 'zip',
    'type' => 'varchar(100)',
  ),
  9 => 
  array (
    'name' => 'social_security_number',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'sex',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'date_of_birth',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'iprfile_12',
    'type' => 'varchar(100)',
  ),
  13 => 
  array (
    'name' => 'iprfile_13',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'use_counter',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'phone',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'employer',
    'type' => 'varchar(100)',
  ),
  17 => 
  array (
    'name' => 'iprfile_17',
    'type' => 'int(20)',
  ),
  18 => 
  array (
    'name' => 'iprfile_18',
    'type' => 'int(20)',
  ),
  19 => 
  array (
    'name' => 'iprfile_19',
    'type' => 'int(20)',
  ),
);


}
?>
