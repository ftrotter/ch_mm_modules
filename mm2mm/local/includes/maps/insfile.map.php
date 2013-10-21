<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for insfile.dat

require_once("map.class.php"); 

class insfile_Map extends MedManMap{
	
	var $nick_name = "insfile";
	var $file_name = "insfile.dat";
	var $table_name = "mm2mm_insfile";
	var $id_field = "insfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'user_number',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'record_link',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'insured_party_number', // 0 = Guarantor anything else is a link
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'insurance_plan_number', //link to clmfile.dat
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'patient_account_number',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'group_name',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'accepts_assignment',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'insfile_7',
    'type' => 'varchar(100)',
  ),
  8 => 
  array (
    'name' => 'social_security_number',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'dependant_number',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'insfile_10',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'comment',
    'type' => 'varchar(100)',
  ),
  12 => 
  array (
    'name' => 'employer_health_plan',
    'type' => 'varchar(100)',
  ),
  13 => 
  array (
    'name' => 'effective_date',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'end_date',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'flags',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'insfile_16',
    'type' => 'varchar(100)',
  ),
  17 => 
  array (
    'name' => 'insfile_17',
    'type' => 'int(20)',
  ),
  18 => 
  array (
    'name' => 'reporting_group',
    'type' => 'int(20)',
  ),
  19 => 
  array (
    'name' => 'insfile_19',
    'type' => 'varchar(100)',
  ),
  20 => 
  array (
    'name' => 'insfile_20',
    'type' => 'varchar(100)',
  ),
);


}
?>
