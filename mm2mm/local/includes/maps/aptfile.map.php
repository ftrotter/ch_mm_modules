<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for aptfile.dat

require_once("map.class.php"); 

class aptfile_Map extends MedManMap{


function test_record($record){

	if(!$this->return_placeholders & strcmp($record['account_number'],'******')==0){ //this is a place holder appointment
		return false;		
	}else{
		return true;
	}

}
	var $return_placeholders = false;
	var $nick_name = "aptfile";
	var $file_name = "aptfile.dat";
	var $table_name = "mm2mm_aptfile";
	var $id_field = "appointment_unique_id";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'user_number',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'account_number',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'dependant_number',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'appointment_date',
    'type' => 'DATE',
  ),
  4 => 
  array (
    'name' => 'slot_number',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'slot_room_number',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'appointment_time',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'appointment_reason',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'provider_number',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'length_of_appointment_in_slots',
    'type' => 'int(20)',
  ),
  10 => 
  array (
    'name' => 'special_phone_number',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'room_number',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'appointment_created',
    'type' => 'DATE',
  ),
  13 => 
  array (
    'name' => 'aptfile_status_enum',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'appointment_for',
    'type' => 'varchar(100)',
  ),
  15 => 
  array (
    'name' => 'appointment_unique_id',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'encounter_form',
    'type' => 'int(20)',
  ),
  17 => 
  array (
    'name' => 'user_defined_code',
    'type' => 'varchar(100)',
  ),
  18 => 
  array (
    'name' => 'operator_number',
    'type' => 'int(20)',
  ),
  19 => 
  array (
    'name' => 'aptfile_appointment_flag_enum',
    'type' => 'varchar(100)',
  ),
  20 => 
  array (
    'name' => 'segment_number',
    'type' => 'int(20)',
  ),
  21 => 
  array (
    'name' => 'appointment_location',
    'type' => 'varchar(100)',
  ),
  22 => 
  array (
    'name' => 'aptfile_22',
    'type' => 'int(20)',
  ),
  23 => 
  array (
    'name' => 'case_number',
    'type' => 'varchar(100)',
  ),
  24 => 
  array (
    'name' => 'aptfile_24',
    'type' => 'int(20)',
  ),
  25 => 
  array (
    'name' => 'aptfile_25',
    'type' => 'varchar(100)',
  ),
);


}
?>
