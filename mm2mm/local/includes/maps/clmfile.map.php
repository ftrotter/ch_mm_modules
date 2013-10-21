<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for clmfile.dat

require_once("map.class.php"); 

class clmfile_Map extends MedManMap{
	
	var $nick_name = "clmfile";
	var $file_name = "clmfile.dat";
	var $table_name = "mm2mm_clmfile";
	var $id_field = "clmfile_2";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'clmfile_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'clmfile_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'clmfile_2',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'clmfile_3',
    'type' => 'varchar(100)',
  ),
  4 => 
  array (
    'name' => 'clmfile_4',
    'type' => 'varchar(100)',
  ),
  5 => 
  array (
    'name' => 'clmfile_5',
    'type' => 'varchar(100)',
  ),
  6 => 
  array (
    'name' => 'clmfile_6',
    'type' => 'varchar(100)',
  ),
  7 => 
  array (
    'name' => 'clmfile_7',
    'type' => 'varchar(100)',
  ),
  8 => 
  array (
    'name' => 'clmfile_8',
    'type' => 'varchar(100)',
  ),
  9 => 
  array (
    'name' => 'clmfile_9',
    'type' => 'varchar(100)',
  ),
  10 => 
  array (
    'name' => 'clmfile_10',
    'type' => 'varchar(100)',
  ),
  11 => 
  array (
    'name' => 'clmfile_11',
    'type' => 'varchar(100)',
  ),
  12 => 
  array (
    'name' => 'clmfile_12',
    'type' => 'int(20)',
  ),
  13 => 
  array (
    'name' => 'clmfile_13',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'clmfile_14',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'clmfile_15',
    'type' => 'varchar(100)',
  ),
  16 => 
  array (
    'name' => 'clmfile_16',
    'type' => 'varchar(100)',
  ),
  17 => 
  array (
    'name' => 'clmfile_17',
    'type' => 'varchar(100)',
  ),
  18 => 
  array (
    'name' => 'clmfile_18',
    'type' => 'int(20)',
  ),
  19 => 
  array (
    'name' => 'clmfile_19',
    'type' => 'int(20)',
  ),
  20 => 
  array (
    'name' => 'clmfile_20',
    'type' => 'int(20)',
  ),
  21 => 
  array (
    'name' => 'clmfile_21',
    'type' => 'int(20)',
  ),
  22 => 
  array (
    'name' => 'clmfile_22',
    'type' => 'varchar(100)',
  ),
  23 => 
  array (
    'name' => 'clmfile_23',
    'type' => 'varchar(100)',
  ),
  24 => 
  array (
    'name' => 'clmfile_24',
    'type' => 'int(20)',
  ),
  25 => 
  array (
    'name' => 'clmfile_25',
    'type' => 'int(20)',
  ),
  26 => 
  array (
    'name' => 'clmfile_26',
    'type' => 'int(20)',
  ),
  27 => 
  array (
    'name' => 'clmfile_27',
    'type' => 'int(20)',
  ),
  28 => 
  array (
    'name' => 'clmfile_28',
    'type' => 'int(20)',
  ),
  29 => 
  array (
    'name' => 'clmfile_29',
    'type' => 'int(20)',
  ),
  30 => 
  array (
    'name' => 'clmfile_30',
    'type' => 'int(20)',
  ),
  31 => 
  array (
    'name' => 'clmfile_31',
    'type' => 'int(20)',
  ),
  32 => 
  array (
    'name' => 'clmfile_32',
    'type' => 'varchar(100)',
  ),
  33 => 
  array (
    'name' => 'clmfile_33',
    'type' => 'int(20)',
  ),
  34 => 
  array (
    'name' => 'clmfile_34',
    'type' => 'int(20)',
  ),
  35 => 
  array (
    'name' => 'clmfile_35',
    'type' => 'int(20)',
  ),
  36 => 
  array (
    'name' => 'clmfile_36',
    'type' => 'int(20)',
  ),
  37 => 
  array (
    'name' => 'clmfile_37',
    'type' => 'varchar(100)',
  ),
  38 => 
  array (
    'name' => 'clmfile_38',
    'type' => 'int(20)',
  ),
  39 => 
  array (
    'name' => 'clmfile_39',
    'type' => 'int(20)',
  ),
  40 => 
  array (
    'name' => 'clmfile_40',
    'type' => 'int(20)',
  ),
  41 => 
  array (
    'name' => 'clmfile_41',
    'type' => 'int(20)',
  ),
  42 => 
  array (
    'name' => 'clmfile_42',
    'type' => 'int(20)',
  ),
  43 => 
  array (
    'name' => 'clmfile_43',
    'type' => 'int(20)',
  ),
  44 => 
  array (
    'name' => 'clmfile_44',
    'type' => 'int(20)',
  ),
  45 => 
  array (
    'name' => 'clmfile_45',
    'type' => 'int(20)',
  ),
  46 => 
  array (
    'name' => 'clmfile_46',
    'type' => 'int(20)',
  ),
  47 => 
  array (
    'name' => 'clmfile_47',
    'type' => 'varchar(100)',
  ),
  48 => 
  array (
    'name' => 'clmfile_48',
    'type' => 'varchar(100)',
  ),
  49 => 
  array (
    'name' => 'clmfile_49',
    'type' => 'int(20)',
  ),
  50 => 
  array (
    'name' => 'clmfile_50',
    'type' => 'int(20)',
  ),
  51 => 
  array (
    'name' => 'clmfile_51',
    'type' => 'int(20)',
  ),
  52 => 
  array (
    'name' => 'clmfile_52',
    'type' => 'int(20)',
  ),
  53 => 
  array (
    'name' => 'clmfile_53',
    'type' => 'int(20)',
  ),
  54 => 
  array (
    'name' => 'clmfile_54',
    'type' => 'varchar(100)',
  ),
  55 => 
  array (
    'name' => 'clmfile_55',
    'type' => 'varchar(100)',
  ),
  56 => 
  array (
    'name' => 'clmfile_56',
    'type' => 'varchar(100)',
  ),
  57 => 
  array (
    'name' => 'clmfile_57',
    'type' => 'varchar(100)',
  ),
  58 => 
  array (
    'name' => 'clmfile_58',
    'type' => 'varchar(100)',
  ),
  59 => 
  array (
    'name' => 'clmfile_59',
    'type' => 'int(20)',
  ),
  60 => 
  array (
    'name' => 'clmfile_60',
    'type' => 'int(20)',
  ),
  61 => 
  array (
    'name' => 'clmfile_61',
    'type' => 'int(20)',
  ),
  62 => 
  array (
    'name' => 'clmfile_62',
    'type' => 'int(20)',
  ),
  63 => 
  array (
    'name' => 'clmfile_63',
    'type' => 'int(20)',
  ),
  64 => 
  array (
    'name' => 'clmfile_64',
    'type' => 'int(20)',
  ),
  65 => 
  array (
    'name' => 'clmfile_65',
    'type' => 'int(20)',
  ),
  66 => 
  array (
    'name' => 'clmfile_66',
    'type' => 'varchar(100)',
  ),
  67 => 
  array (
    'name' => 'clmfile_67',
    'type' => 'varchar(100)',
  ),
  68 => 
  array (
    'name' => 'clmfile_68',
    'type' => 'varchar(100)',
  ),
  69 => 
  array (
    'name' => 'clmfile_69',
    'type' => 'varchar(100)',
  ),
  70 => 
  array (
    'name' => 'clmfile_70',
    'type' => 'int(20)',
  ),
  71 => 
  array (
    'name' => 'clmfile_71',
    'type' => 'int(20)',
  ),
  72 => 
  array (
    'name' => 'clmfile_72',
    'type' => 'varchar(100)',
  ),
  73 => 
  array (
    'name' => 'clmfile_73',
    'type' => 'varchar(100)',
  ),
  74 => 
  array (
    'name' => 'clmfile_74',
    'type' => 'varchar(100)',
  ),
  75 => 
  array (
    'name' => 'clmfile_75',
    'type' => 'varchar(100)',
  ),
  76 => 
  array (
    'name' => 'clmfile_76',
    'type' => 'varchar(100)',
  ),
  77 => 
  array (
    'name' => 'clmfile_77',
    'type' => 'varchar(100)',
  ),
  78 => 
  array (
    'name' => 'clmfile_78',
    'type' => 'int(20)',
  ),
  79 => 
  array (
    'name' => 'clmfile_79',
    'type' => 'int(20)',
  ),
  80 => 
  array (
    'name' => 'clmfile_80',
    'type' => 'int(20)',
  ),
  81 => 
  array (
    'name' => 'clmfile_81',
    'type' => 'int(20)',
  ),
  82 => 
  array (
    'name' => 'clmfile_82',
    'type' => 'int(20)',
  ),
  83 => 
  array (
    'name' => 'clmfile_83',
    'type' => 'int(20)',
  ),
  84 => 
  array (
    'name' => 'clmfile_84',
    'type' => 'int(20)',
  ),
  85 => 
  array (
    'name' => 'clmfile_85',
    'type' => 'int(20)',
  ),
  86 => 
  array (
    'name' => 'clmfile_86',
    'type' => 'int(20)',
  ),
  87 => 
  array (
    'name' => 'clmfile_87',
    'type' => 'int(20)',
  ),
);


}
?>