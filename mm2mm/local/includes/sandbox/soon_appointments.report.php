<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for aptfile.dat

require_once("report_lite.class.php"); 

class soon_appointments_Report extends MedManReportsLite{
	
	var $nick_name = "aptfile";
	var $main_map_file = "aptfile.map.php";
	var $match_contraints = array ( 
		0 => array (
			'field_name' => 'aptfile_3', // the name of the field to perform the function on
			'matches' = 'tomorrow' // the name of the function that determines the match
		),

		1 => array (
			'field_name' => 'aptfile_12',
			'matches' = 'between_tomorrow_and_next_week'
		)

	);

	var $linked_map_files = array ( 
		0 => array (
			'map_file_name' => 'patfile.map.php', // the map file to use
			'linking_on' = 'aptfile_50', // the field name that maps to the other file
			'key' = array(), // the list of keys to search that file for 
			'matching_array' = array() //multi array using the keys above pointing to the matching lines
		),

		1 => array (
			'map_file_name' => 'insfile.map.php', // the map file to use
			'linking_on' = 'aptfile_51', // the field name that maps to the other file
			'key' = array(), // the list of keys to search that file for 
			'matching_array' = array() //multi array using the keys above pointing to the matching lines
		)


	);



	function tomorrow($date_value){
		return true; // return true if the date_value = tomorrow 

	}

	function between_tomorrow_and_next_week($date_value){
		return true; // return true if the date_value = tomorrow up to a week from now..

	}



	function test(){

		echo "working fine";

	}

/*
	var $fields = array (
  0 => 
  array (
    'name' => 'aptfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'aptfile_1',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'aptfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'aptfile_3',
    'type' => 'DATE',
  ),
  4 => 
  array (
    'name' => 'aptfile_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'aptfile_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'aptfile_6',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'aptfile_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'aptfile_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'aptfile_9',
    'type' => 'int(20)',
  ),
  10 => 
  array (
    'name' => 'aptfile_10',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'aptfile_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'aptfile_12',
    'type' => 'DATE',
  ),
  13 => 
  array (
    'name' => 'aptfile_13',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'aptfile_14',
    'type' => 'varchar(100)',
  ),
  15 => 
  array (
    'name' => 'aptfile_15',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'aptfile_16',
    'type' => 'int(20)',
  ),
  17 => 
  array (
    'name' => 'aptfile_17',
    'type' => 'varchar(100)',
  ),
  18 => 
  array (
    'name' => 'aptfile_18',
    'type' => 'int(20)',
  ),
  19 => 
  array (
    'name' => 'aptfile_19',
    'type' => 'varchar(100)',
  ),
  20 => 
  array (
    'name' => 'aptfile_20',
    'type' => 'int(20)',
  ),
  21 => 
  array (
    'name' => 'aptfile_21',
    'type' => 'varchar(100)',
  ),
  22 => 
  array (
    'name' => 'aptfile_22',
    'type' => 'int(20)',
  ),
  23 => 
  array (
    'name' => 'aptfile_23',
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


*/

}
?>
