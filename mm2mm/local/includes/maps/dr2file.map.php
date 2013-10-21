<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for dr2file.dat

require_once("map.class.php"); 

class dr2file_Map extends MedManMap{
	
	var $nick_name = "dr2file";
	var $file_name = "dr2file.dat";
	var $table_name = "mm2mm_dr2file";
	var $id_field = "dr2file_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'dr2file_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'dr2file_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'dr2file_2',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'dr2file_3',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'dr2file_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'dr2file_5',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'dr2file_6',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'dr2file_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'dr2file_8',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'dr2file_9',
    'type' => 'int(20)',
  ),
  10 => 
  array (
    'name' => 'dr2file_10',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'dr2file_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'dr2file_12',
    'type' => 'int(20)',
  ),
  13 => 
  array (
    'name' => 'dr2file_13',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'dr2file_14',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'dr2file_15',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'dr2file_16',
    'type' => 'int(20)',
  ),
  17 => 
  array (
    'name' => 'dr2file_17',
    'type' => 'int(20)',
  ),
  18 => 
  array (
    'name' => 'dr2file_18',
    'type' => 'int(20)',
  ),
  19 => 
  array (
    'name' => 'dr2file_19',
    'type' => 'int(20)',
  ),
  20 => 
  array (
    'name' => 'dr2file_20',
    'type' => 'int(20)',
  ),
  21 => 
  array (
    'name' => 'dr2file_21',
    'type' => 'int(20)',
  ),
  22 => 
  array (
    'name' => 'dr2file_22',
    'type' => 'int(20)',
  ),
  23 => 
  array (
    'name' => 'dr2file_23',
    'type' => 'int(20)',
  ),
  24 => 
  array (
    'name' => 'dr2file_24',
    'type' => 'int(20)',
  ),
  25 => 
  array (
    'name' => 'dr2file_25',
    'type' => 'int(20)',
  ),
  26 => 
  array (
    'name' => 'dr2file_26',
    'type' => 'int(20)',
  ),
  27 => 
  array (
    'name' => 'dr2file_27',
    'type' => 'int(20)',
  ),
  28 => 
  array (
    'name' => 'dr2file_28',
    'type' => 'int(20)',
  ),
  29 => 
  array (
    'name' => 'dr2file_29',
    'type' => 'int(20)',
  ),
  30 => 
  array (
    'name' => 'dr2file_30',
    'type' => 'int(20)',
  ),
  31 => 
  array (
    'name' => 'dr2file_31',
    'type' => 'int(20)',
  ),
  32 => 
  array (
    'name' => 'dr2file_32',
    'type' => 'int(20)',
  ),
  33 => 
  array (
    'name' => 'dr2file_33',
    'type' => 'int(20)',
  ),
  34 => 
  array (
    'name' => 'dr2file_34',
    'type' => 'int(20)',
  ),
  35 => 
  array (
    'name' => 'dr2file_35',
    'type' => 'int(20)',
  ),
  36 => 
  array (
    'name' => 'dr2file_36',
    'type' => 'int(20)',
  ),
  37 => 
  array (
    'name' => 'dr2file_37',
    'type' => 'int(20)',
  ),
  38 => 
  array (
    'name' => 'dr2file_38',
    'type' => 'int(20)',
  ),
  39 => 
  array (
    'name' => 'dr2file_39',
    'type' => 'int(20)',
  ),
  40 => 
  array (
    'name' => 'dr2file_40',
    'type' => 'int(20)',
  ),
  41 => 
  array (
    'name' => 'dr2file_41',
    'type' => 'int(20)',
  ),
  42 => 
  array (
    'name' => 'dr2file_42',
    'type' => 'int(20)',
  ),
  43 => 
  array (
    'name' => 'dr2file_43',
    'type' => 'int(20)',
  ),
  44 => 
  array (
    'name' => 'dr2file_44',
    'type' => 'int(20)',
  ),
  45 => 
  array (
    'name' => 'dr2file_45',
    'type' => 'int(20)',
  ),
  46 => 
  array (
    'name' => 'dr2file_46',
    'type' => 'int(20)',
  ),
  47 => 
  array (
    'name' => 'dr2file_47',
    'type' => 'int(20)',
  ),
  48 => 
  array (
    'name' => 'dr2file_48',
    'type' => 'int(20)',
  ),
  49 => 
  array (
    'name' => 'dr2file_49',
    'type' => 'int(20)',
  ),
  50 => 
  array (
    'name' => 'dr2file_50',
    'type' => 'int(20)',
  ),
  51 => 
  array (
    'name' => 'dr2file_51',
    'type' => 'int(20)',
  ),
  52 => 
  array (
    'name' => 'dr2file_52',
    'type' => 'int(20)',
  ),
  53 => 
  array (
    'name' => 'dr2file_53',
    'type' => 'int(20)',
  ),
  54 => 
  array (
    'name' => 'dr2file_54',
    'type' => 'int(20)',
  ),
  55 => 
  array (
    'name' => 'dr2file_55',
    'type' => 'int(20)',
  ),
  56 => 
  array (
    'name' => 'dr2file_56',
    'type' => 'int(20)',
  ),
  57 => 
  array (
    'name' => 'dr2file_57',
    'type' => 'int(20)',
  ),
  58 => 
  array (
    'name' => 'dr2file_58',
    'type' => 'int(20)',
  ),
  59 => 
  array (
    'name' => 'dr2file_59',
    'type' => 'int(20)',
  ),
  60 => 
  array (
    'name' => 'dr2file_60',
    'type' => 'int(20)',
  ),
  61 => 
  array (
    'name' => 'dr2file_61',
    'type' => 'int(20)',
  ),
  62 => 
  array (
    'name' => 'dr2file_62',
    'type' => 'int(20)',
  ),
  63 => 
  array (
    'name' => 'dr2file_63',
    'type' => 'int(20)',
  ),
  64 => 
  array (
    'name' => 'dr2file_64',
    'type' => 'int(20)',
  ),
  65 => 
  array (
    'name' => 'dr2file_65',
    'type' => 'int(20)',
  ),
  66 => 
  array (
    'name' => 'dr2file_66',
    'type' => 'int(20)',
  ),
  67 => 
  array (
    'name' => 'dr2file_67',
    'type' => 'int(20)',
  ),
  68 => 
  array (
    'name' => 'dr2file_68',
    'type' => 'int(20)',
  ),
  69 => 
  array (
    'name' => 'dr2file_69',
    'type' => 'int(20)',
  ),
  70 => 
  array (
    'name' => 'dr2file_70',
    'type' => 'int(20)',
  ),
  71 => 
  array (
    'name' => 'dr2file_71',
    'type' => 'int(20)',
  ),
  72 => 
  array (
    'name' => 'dr2file_72',
    'type' => 'int(20)',
  ),
  73 => 
  array (
    'name' => 'dr2file_73',
    'type' => 'int(20)',
  ),
  74 => 
  array (
    'name' => 'dr2file_74',
    'type' => 'int(20)',
  ),
  75 => 
  array (
    'name' => 'dr2file_75',
    'type' => 'int(20)',
  ),
  76 => 
  array (
    'name' => 'dr2file_76',
    'type' => 'int(20)',
  ),
  77 => 
  array (
    'name' => 'dr2file_77',
    'type' => 'int(20)',
  ),
  78 => 
  array (
    'name' => 'dr2file_78',
    'type' => 'int(20)',
  ),
  79 => 
  array (
    'name' => 'dr2file_79',
    'type' => 'int(20)',
  ),
  80 => 
  array (
    'name' => 'dr2file_80',
    'type' => 'int(20)',
  ),
  81 => 
  array (
    'name' => 'dr2file_81',
    'type' => 'int(20)',
  ),
);


}
?>