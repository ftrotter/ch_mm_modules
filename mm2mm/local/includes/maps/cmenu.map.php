<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for cmenu.dat

require_once("map.class.php"); 

class cmenu_Map extends MedManMap{
	
	var $nick_name = "cmenu";
	var $file_name = "cmenu.dat";
	var $table_name = "mm2mm_cmenu";
	var $id_field = "cmenu_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'cmenu_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'cmenu_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'cmenu_2',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'cmenu_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'cmenu_4',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'cmenu_5',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'cmenu_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'cmenu_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'cmenu_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'cmenu_9',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'cmenu_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'cmenu_11',
    'type' => 'varchar(100)',
  ),
  12 => 
  array (
    'name' => 'cmenu_12',
    'type' => 'varchar(100)',
  ),
  13 => 
  array (
    'name' => 'cmenu_13',
    'type' => 'varchar(100)',
  ),
  14 => 
  array (
    'name' => 'cmenu_14',
    'type' => 'varchar(100)',
  ),
  15 => 
  array (
    'name' => 'cmenu_15',
    'type' => 'varchar(100)',
  ),
  16 => 
  array (
    'name' => 'cmenu_16',
    'type' => 'varchar(100)',
  ),
  17 => 
  array (
    'name' => 'cmenu_17',
    'type' => 'varchar(100)',
  ),
  18 => 
  array (
    'name' => 'cmenu_18',
    'type' => 'varchar(100)',
  ),
  19 => 
  array (
    'name' => 'cmenu_19',
    'type' => 'varchar(100)',
  ),
  20 => 
  array (
    'name' => 'cmenu_20',
    'type' => 'varchar(100)',
  ),
  21 => 
  array (
    'name' => 'cmenu_21',
    'type' => 'varchar(100)',
  ),
  22 => 
  array (
    'name' => 'cmenu_22',
    'type' => 'varchar(100)',
  ),
  23 => 
  array (
    'name' => 'cmenu_23',
    'type' => 'varchar(100)',
  ),
  24 => 
  array (
    'name' => 'cmenu_24',
    'type' => 'varchar(100)',
  ),
  25 => 
  array (
    'name' => 'cmenu_25',
    'type' => 'varchar(100)',
  ),
  26 => 
  array (
    'name' => 'cmenu_26',
    'type' => 'varchar(100)',
  ),
  27 => 
  array (
    'name' => 'cmenu_27',
    'type' => 'varchar(100)',
  ),
  28 => 
  array (
    'name' => 'cmenu_28',
    'type' => 'varchar(100)',
  ),
  29 => 
  array (
    'name' => 'cmenu_29',
    'type' => 'varchar(100)',
  ),
  30 => 
  array (
    'name' => 'cmenu_30',
    'type' => 'varchar(100)',
  ),
  31 => 
  array (
    'name' => 'cmenu_31',
    'type' => 'varchar(100)',
  ),
  32 => 
  array (
    'name' => 'cmenu_32',
    'type' => 'varchar(100)',
  ),
  33 => 
  array (
    'name' => 'cmenu_33',
    'type' => 'varchar(100)',
  ),
  34 => 
  array (
    'name' => 'cmenu_34',
    'type' => 'varchar(100)',
  ),
  35 => 
  array (
    'name' => 'cmenu_35',
    'type' => 'varchar(100)',
  ),
  36 => 
  array (
    'name' => 'cmenu_36',
    'type' => 'varchar(100)',
  ),
  37 => 
  array (
    'name' => 'cmenu_37',
    'type' => 'varchar(100)',
  ),
  38 => 
  array (
    'name' => 'cmenu_38',
    'type' => 'varchar(100)',
  ),
  39 => 
  array (
    'name' => 'cmenu_39',
    'type' => 'varchar(100)',
  ),
  40 => 
  array (
    'name' => 'cmenu_40',
    'type' => 'varchar(100)',
  ),
  41 => 
  array (
    'name' => 'cmenu_41',
    'type' => 'varchar(100)',
  ),
  42 => 
  array (
    'name' => 'cmenu_42',
    'type' => 'varchar(100)',
  ),
  43 => 
  array (
    'name' => 'cmenu_43',
    'type' => 'varchar(100)',
  ),
  44 => 
  array (
    'name' => 'cmenu_44',
    'type' => 'varchar(100)',
  ),
  45 => 
  array (
    'name' => 'cmenu_45',
    'type' => 'varchar(100)',
  ),
  46 => 
  array (
    'name' => 'cmenu_46',
    'type' => 'varchar(100)',
  ),
  47 => 
  array (
    'name' => 'cmenu_47',
    'type' => 'varchar(100)',
  ),
  48 => 
  array (
    'name' => 'cmenu_48',
    'type' => 'varchar(100)',
  ),
  49 => 
  array (
    'name' => 'cmenu_49',
    'type' => 'varchar(100)',
  ),
);


}
?>