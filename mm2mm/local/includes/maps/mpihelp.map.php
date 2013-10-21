<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for mpihelp.dat

require_once("map.class.php"); 

class mpihelp_Map extends MedManMap{
	
	var $nick_name = "mpihelp";
	var $file_name = "mpihelp.dat";
	var $table_name = "mm2mm_mpihelp";
	var $id_field = "mpihelp_38";
	var $default_type = "varchar(100)";
	var $fields = array(
		0 => array( "name" => "mpihelp_0"
				//,'type' => 'varchar(100)'
				//,'type' => 'int(20)'
				//,'type' => 'DATE'
		),

		1 => array( "name" => "mpihelp_1"
				//,'type' => 'int(20)'
				//,'type' => 'varchar(100)'
				//,'type' => 'DATE'
		),

		2 => array( "name" => "mpihelp_2"
				//,'type' => 'int(20)'
				//,'type' => 'varchar(100)'
				//,'type' => 'DATE'
		),

		3 => array( "name" => "mpihelp_3"
				//,'type' => 'int(20)'
				//,'type' => 'varchar(100)'
				//,'type' => 'DATE'
		));


}
?>