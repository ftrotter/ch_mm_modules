<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for depfile.dat

require_once("map.class.php"); 

class depfile_Map extends MedManMap{
	
	var $nick_name = "depfile";
	var $file_name = "depfile.dat";
	var $table_name = "mm2mm_depfile";
	var $id_field = "depfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'user_number',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'dependant_number',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'patient_account_number',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'name',
    'type' => 'varchar(100)',
  ),

  4 => 
  array (
    'name' => 'date_of_birth',
    'type' => 'DATE',
  ),
  5 => 
  array (
    'name' => 'sex',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'relationship_to_guarantor', // husband, wife, parent, child, special child (d), other
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'last_name',
    'type' => 'varchar(100)',
  ),
  8 => 
  array (
    'name' => 'id',
    'type' => 'varchar(100)',
  ),
  9 => 
  array (
    'name' => 'last_diagnosis',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => '2nd_to_last_diagnosis',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'extended_information_level', // 0=none, 1=comment, 2=full
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'comment_counter',
    'type' => 'int(20)',
  ),
  13 => 
  array (
    'name' => 'word_procedure_id',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'middle_initial',
    'type' => 'varchar(100)',
  ),
  15 => 
  array (
    'name' => 'social_security_number',
    'type' => 'varchar(100)',
  ),
  16 => 
  array (
    'name' => 'flags', // 1= use guarantors coverage list, 0 = use dependants coverage list
    'type' => 'int(20)',
  ),
  17 => 
  array (
    'name' => 'previous_century_date_of_birth', 
    'type' => 'int(20)',
  ),
  18 => 
  array (
    'name' => 'third_to_last_diagnosis',
    'type' => 'varchar(100)',
  ),
  19 => 
  array (
    'name' => 'fourth_to_last_diagnosis',
    'type' => 'varchar(100)',
  ),
  20 => 
  array (
    'name' => 'marital_status',
    'type' => 'varchar(100)',
  ),
  21 => 
  array (
    'name' => 'employment_status',
    'type' => 'varchar(100)',
  ),
  22 => 
  array (
    'name' => 'preferred_plan_1',
    'type' => 'int(20)',
  ),
  23 => 
  array (
    'name' => 'preferred_plan_1_number',
    'type' => 'int(20)',
  ),
  24 => 
  array (
    'name' => 'preferred_plan_2',
    'type' => 'int(20)',
  ),
  25 => 
  array (
    'name' => 'preferred_plan_2_number',
    'type' => 'int(20)',
  ),
  26 => 
  array (
    'name' => 'preferred_plan_3',
    'type' => 'int(20)',
  ),
  27 => 
  array (
    'name' => 'preferred_plan_3_number',
    'type' => 'int(20)',
  ),
  28 => 
  array (
    'name' => 'preferred_plan_4',
    'type' => 'int(20)',
  ),
  29 => 
  array (
    'name' => 'preferred_plan_4_number',
    'type' => 'int(20)',
  ),
  30 => 
  array (
    'name' => 'preferred_plan_5',
    'type' => 'int(20)',
  ),
  31 => 
  array (
    'name' => 'preferred_plan_5_number',
    'type' => 'int(20)',
  ),
  32 => 
  array (
    'name' => 'preferred_plan_6',
    'type' => 'int(20)',
  ),
  33 => 
  array (
    'name' => 'preferred_plan_6_number',
    'type' => 'int(20)',
  ),
  34 => 
  array (
    'name' => 'preferred_plan_7',
    'type' => 'int(20)',
  ),
  35 => 
  array (
    'name' => 'preferred_plan_7_number',
    'type' => 'int(20)',
  ),
  36 => 
  array (
    'name' => 'default_doctor',
    'type' => 'int(20)',
  ),
  37 => 
  array (
    'name' => 'default_referring_doctor',
    'type' => 'int(20)',
  ),
  38 => 
  array (
    'name' => 'patient_id_2',
    'type' => 'varchar(100)',
  ),
  39 => 
  array (
    'name' => 'patient_relation_to_ipr', // manual says 'string of seven relation codes' no idea what that means.
    'type' => 'varchar(100)',
  ),
  40 => 
  array (
    'name' => 'depfile40',
    'type' => 'varchar(100)',
  ),
  41 => 
  array (
    'name' => 'depfile41',
    'type' => 'varchar(100)',
  ),
  42 => 
  array (
    'name' => 'depfile42',
    'type' => 'varchar(100)',
  ),

);


}
?>
