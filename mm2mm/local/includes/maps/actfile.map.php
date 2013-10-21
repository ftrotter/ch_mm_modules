<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for actfile.dat

require_once("map.class.php"); 

class actfile_Map extends MedManMap{
	
	var $nick_name = "actfile";
	var $file_name = "actfile.dat";
	var $table_name = "mm2mm_actfile";
	var $id_field = "actfile_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'actfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'actfile_1',
    'type' => 'varchar(100)',
  ),
  2 => 
  array (
    'name' => 'actfile_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'actfile_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'actfile_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'actfile_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'actfile_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'actfile_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'actfile_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'actfile_9',
    'type' => 'int(20)',
  ),
  10 => 
  array (
    'name' => 'actfile_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'actfile_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'actfile_12',
    'type' => 'varchar(100)',
  ),
  13 => 
  array (
    'name' => 'actfile_13',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'actfile_14',
    'type' => 'varchar(100)',
  ),
  15 => 
  array (
    'name' => 'actfile_15',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'actfile_16',
    'type' => 'int(20)',
  ),
  17 => 
  array (
    'name' => 'actfile_17',
    'type' => 'varchar(100)',
  ),
  18 => 
  array (
    'name' => 'actfile_18',
    'type' => 'varchar(100)',
  ),
  19 => 
  array (
    'name' => 'actfile_19',
    'type' => 'int(20)',
  ),
  20 => 
  array (
    'name' => 'actfile_20',
    'type' => 'int(20)',
  ),
  21 => 
  array (
    'name' => 'actfile_21',
    'type' => 'varchar(100)',
  ),
  22 => 
  array (
    'name' => 'actfile_22',
    'type' => 'int(20)',
  ),
  23 => 
  array (
    'name' => 'actfile_23',
    'type' => 'int(20)',
  ),
  24 => 
  array (
    'name' => 'actfile_24',
    'type' => 'int(20)',
  ),
  25 => 
  array (
    'name' => 'actfile_25',
    'type' => 'int(20)',
  ),
  26 => 
  array (
    'name' => 'actfile_26',
    'type' => 'DATE',
  ),
  27 => 
  array (
    'name' => 'actfile_27',
    'type' => 'DATE',
  ),
  28 => 
  array (
    'name' => 'actfile_28',
    'type' => 'DATE',
  ),
  29 => 
  array (
    'name' => 'actfile_29',
    'type' => 'DATE',
  ),
  30 => 
  array (
    'name' => 'actfile_30',
    'type' => 'int(20)',
  ),
  31 => 
  array (
    'name' => 'actfile_31',
    'type' => 'DATE',
  ),
  32 => 
  array (
    'name' => 'actfile_32',
    'type' => 'varchar(100)',
  ),
  33 => 
  array (
    'name' => 'actfile_33',
    'type' => 'int(20)',
  ),
  34 => 
  array (
    'name' => 'actfile_34',
    'type' => 'int(20)',
  ),
  35 => 
  array (
    'name' => 'actfile_35',
    'type' => 'int(20)',
  ),
  36 => 
  array (
    'name' => 'actfile_36',
    'type' => 'int(20)',
  ),
  37 => 
  array (
    'name' => 'actfile_37',
    'type' => 'int(20)',
  ),
  38 => 
  array (
    'name' => 'actfile_38',
    'type' => 'varchar(100)',
  ),
  39 => 
  array (
    'name' => 'actfile_39',
    'type' => 'varchar(100)',
  ),
  40 => 
  array (
    'name' => 'actfile_40',
    'type' => 'int(20)',
  ),
  41 => 
  array (
    'name' => 'actfile_41',
    'type' => 'varchar(100)',
  ),
  42 => 
  array (
    'name' => 'actfile_42',
    'type' => 'int(20)',
  ),
  43 => 
  array (
    'name' => 'actfile_43',
    'type' => 'int(20)',
  ),
  44 => 
  array (
    'name' => 'actfile_44',
    'type' => 'varchar(100)',
  ),
  45 => 
  array (
    'name' => 'actfile_45',
    'type' => 'varchar(100)',
  ),
  46 => 
  array (
    'name' => 'actfile_46',
    'type' => 'int(20)',
  ),
  47 => 
  array (
    'name' => 'actfile_47',
    'type' => 'int(20)',
  ),
  48 => 
  array (
    'name' => 'actfile_48',
    'type' => 'int(20)',
  ),
  49 => 
  array (
    'name' => 'actfile_49',
    'type' => 'varchar(100)',
  ),
  50 => 
  array (
    'name' => 'actfile_50',
    'type' => 'int(20)',
  ),
  51 => 
  array (
    'name' => 'actfile_51',
    'type' => 'int(20)',
  ),
  52 => 
  array (
    'name' => 'actfile_52',
    'type' => 'int(20)',
  ),
  53 => 
  array (
    'name' => 'actfile_53',
    'type' => 'int(20)',
  ),
  54 => 
  array (
    'name' => 'actfile_54',
    'type' => 'varchar(100)',
  ),
  55 => 
  array (
    'name' => 'actfile_55',
    'type' => 'int(20)',
  ),
  56 => 
  array (
    'name' => 'actfile_56',
    'type' => 'int(20)',
  ),
  57 => 
  array (
    'name' => 'actfile_57',
    'type' => 'int(20)',
  ),
  58 => 
  array (
    'name' => 'actfile_58',
    'type' => 'varchar(100)',
  ),
  59 => 
  array (
    'name' => 'actfile_59',
    'type' => 'varchar(100)',
  ),
);


}
?>