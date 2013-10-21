<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for emcmgr.dat

require_once("map.class.php"); 

class emcmgr_Map extends MedManMap{
	
	var $nick_name = "emcmgr";
	var $file_name = "emcmgr.dat";
	var $table_name = "mm2mm_emcmgr";
	var $id_field = "emcmgr_1";
	var $default_type = "varchar(100)";
	var $fields = array (
  0 => 
  array (
    'name' => 'emcmgr_0',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'emcmgr_1',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'emcmgr_2',
    'type' => 'varchar(100)',
  ),
  3 => 
  array (
    'name' => 'emcmgr_3',
    'type' => 'varchar(100)',
  ),
  4 => 
  array (
    'name' => 'emcmgr_4',
    'type' => 'int(20)',
  ),
  5 => 
  array (
    'name' => 'emcmgr_5',
    'type' => 'DATE',
  ),
  6 => 
  array (
    'name' => 'emcmgr_6',
    'type' => 'DATE',
  ),
  7 => 
  array (
    'name' => 'emcmgr_7',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'emcmgr_8',
    'type' => 'DATE',
  ),
  9 => 
  array (
    'name' => 'emcmgr_9',
    'type' => 'DATE',
  ),
  10 => 
  array (
    'name' => 'emcmgr_10',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'emcmgr_11',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'emcmgr_12',
    'type' => 'DATE',
  ),
  13 => 
  array (
    'name' => 'emcmgr_13',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'emcmgr_14',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'emcmgr_15',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'emcmgr_16',
    'type' => 'int(20)',
  ),
  17 => 
  array (
    'name' => 'emcmgr_17',
    'type' => 'DATE',
  ),
  18 => 
  array (
    'name' => 'emcmgr_18',
    'type' => 'int(20)',
  ),
  19 => 
  array (
    'name' => 'emcmgr_19',
    'type' => 'int(20)',
  ),
  20 => 
  array (
    'name' => 'emcmgr_20',
    'type' => 'int(20)',
  ),
  21 => 
  array (
    'name' => 'emcmgr_21',
    'type' => 'int(20)',
  ),
  22 => 
  array (
    'name' => 'emcmgr_22',
    'type' => 'int(20)',
  ),
  23 => 
  array (
    'name' => 'emcmgr_23',
    'type' => 'int(20)',
  ),
  24 => 
  array (
    'name' => 'emcmgr_24',
    'type' => 'int(20)',
  ),
  25 => 
  array (
    'name' => 'emcmgr_25',
    'type' => 'int(20)',
  ),
  26 => 
  array (
    'name' => 'emcmgr_26',
    'type' => 'int(20)',
  ),
  27 => 
  array (
    'name' => 'emcmgr_27',
    'type' => 'int(20)',
  ),
  28 => 
  array (
    'name' => 'emcmgr_28',
    'type' => 'int(20)',
  ),
  29 => 
  array (
    'name' => 'emcmgr_29',
    'type' => 'int(20)',
  ),
  30 => 
  array (
    'name' => 'emcmgr_30',
    'type' => 'int(20)',
  ),
  31 => 
  array (
    'name' => 'emcmgr_31',
    'type' => 'int(20)',
  ),
  32 => 
  array (
    'name' => 'emcmgr_32',
    'type' => 'int(20)',
  ),
  33 => 
  array (
    'name' => 'emcmgr_33',
    'type' => 'int(20)',
  ),
  34 => 
  array (
    'name' => 'emcmgr_34',
    'type' => 'int(20)',
  ),
  35 => 
  array (
    'name' => 'emcmgr_35',
    'type' => 'int(20)',
  ),
  36 => 
  array (
    'name' => 'emcmgr_36',
    'type' => 'int(20)',
  ),
  37 => 
  array (
    'name' => 'emcmgr_37',
    'type' => 'int(20)',
  ),
  38 => 
  array (
    'name' => 'emcmgr_38',
    'type' => 'int(20)',
  ),
  39 => 
  array (
    'name' => 'emcmgr_39',
    'type' => 'int(20)',
  ),
  40 => 
  array (
    'name' => 'emcmgr_40',
    'type' => 'int(20)',
  ),
  41 => 
  array (
    'name' => 'emcmgr_41',
    'type' => 'int(20)',
  ),
  42 => 
  array (
    'name' => 'emcmgr_42',
    'type' => 'int(20)',
  ),
  43 => 
  array (
    'name' => 'emcmgr_43',
    'type' => 'int(20)',
  ),
  44 => 
  array (
    'name' => 'emcmgr_44',
    'type' => 'int(20)',
  ),
  45 => 
  array (
    'name' => 'emcmgr_45',
    'type' => 'int(20)',
  ),
  46 => 
  array (
    'name' => 'emcmgr_46',
    'type' => 'int(20)',
  ),
  47 => 
  array (
    'name' => 'emcmgr_47',
    'type' => 'int(20)',
  ),
  48 => 
  array (
    'name' => 'emcmgr_48',
    'type' => 'int(20)',
  ),
  49 => 
  array (
    'name' => 'emcmgr_49',
    'type' => 'int(20)',
  ),
  50 => 
  array (
    'name' => 'emcmgr_50',
    'type' => 'int(20)',
  ),
  51 => 
  array (
    'name' => 'emcmgr_51',
    'type' => 'int(20)',
  ),
  52 => 
  array (
    'name' => 'emcmgr_52',
    'type' => 'int(20)',
  ),
  53 => 
  array (
    'name' => 'emcmgr_53',
    'type' => 'int(20)',
  ),
  54 => 
  array (
    'name' => 'emcmgr_54',
    'type' => 'int(20)',
  ),
  55 => 
  array (
    'name' => 'emcmgr_55',
    'type' => 'int(20)',
  ),
  56 => 
  array (
    'name' => 'emcmgr_56',
    'type' => 'int(20)',
  ),
  57 => 
  array (
    'name' => 'emcmgr_57',
    'type' => 'int(20)',
  ),
  58 => 
  array (
    'name' => 'emcmgr_58',
    'type' => 'int(20)',
  ),
  59 => 
  array (
    'name' => 'emcmgr_59',
    'type' => 'int(20)',
  ),
  60 => 
  array (
    'name' => 'emcmgr_60',
    'type' => 'int(20)',
  ),
  61 => 
  array (
    'name' => 'emcmgr_61',
    'type' => 'int(20)',
  ),
  62 => 
  array (
    'name' => 'emcmgr_62',
    'type' => 'int(20)',
  ),
  63 => 
  array (
    'name' => 'emcmgr_63',
    'type' => 'int(20)',
  ),
  64 => 
  array (
    'name' => 'emcmgr_64',
    'type' => 'int(20)',
  ),
  65 => 
  array (
    'name' => 'emcmgr_65',
    'type' => 'int(20)',
  ),
  66 => 
  array (
    'name' => 'emcmgr_66',
    'type' => 'int(20)',
  ),
  67 => 
  array (
    'name' => 'emcmgr_67',
    'type' => 'int(20)',
  ),
  68 => 
  array (
    'name' => 'emcmgr_68',
    'type' => 'int(20)',
  ),
  69 => 
  array (
    'name' => 'emcmgr_69',
    'type' => 'int(20)',
  ),
  70 => 
  array (
    'name' => 'emcmgr_70',
    'type' => 'int(20)',
  ),
  71 => 
  array (
    'name' => 'emcmgr_71',
    'type' => 'int(20)',
  ),
  72 => 
  array (
    'name' => 'emcmgr_72',
    'type' => 'int(20)',
  ),
  73 => 
  array (
    'name' => 'emcmgr_73',
    'type' => 'int(20)',
  ),
  74 => 
  array (
    'name' => 'emcmgr_74',
    'type' => 'int(20)',
  ),
  75 => 
  array (
    'name' => 'emcmgr_75',
    'type' => 'int(20)',
  ),
  76 => 
  array (
    'name' => 'emcmgr_76',
    'type' => 'int(20)',
  ),
  77 => 
  array (
    'name' => 'emcmgr_77',
    'type' => 'int(20)',
  ),
  78 => 
  array (
    'name' => 'emcmgr_78',
    'type' => 'int(20)',
  ),
  79 => 
  array (
    'name' => 'emcmgr_79',
    'type' => 'int(20)',
  ),
  80 => 
  array (
    'name' => 'emcmgr_80',
    'type' => 'int(20)',
  ),
  81 => 
  array (
    'name' => 'emcmgr_81',
    'type' => 'int(20)',
  ),
  82 => 
  array (
    'name' => 'emcmgr_82',
    'type' => 'int(20)',
  ),
  83 => 
  array (
    'name' => 'emcmgr_83',
    'type' => 'int(20)',
  ),
  84 => 
  array (
    'name' => 'emcmgr_84',
    'type' => 'int(20)',
  ),
  85 => 
  array (
    'name' => 'emcmgr_85',
    'type' => 'int(20)',
  ),
  86 => 
  array (
    'name' => 'emcmgr_86',
    'type' => 'int(20)',
  ),
  87 => 
  array (
    'name' => 'emcmgr_87',
    'type' => 'int(20)',
  ),
  88 => 
  array (
    'name' => 'emcmgr_88',
    'type' => 'int(20)',
  ),
  89 => 
  array (
    'name' => 'emcmgr_89',
    'type' => 'int(20)',
  ),
  90 => 
  array (
    'name' => 'emcmgr_90',
    'type' => 'int(20)',
  ),
  91 => 
  array (
    'name' => 'emcmgr_91',
    'type' => 'int(20)',
  ),
  92 => 
  array (
    'name' => 'emcmgr_92',
    'type' => 'int(20)',
  ),
  93 => 
  array (
    'name' => 'emcmgr_93',
    'type' => 'int(20)',
  ),
  94 => 
  array (
    'name' => 'emcmgr_94',
    'type' => 'int(20)',
  ),
  95 => 
  array (
    'name' => 'emcmgr_95',
    'type' => 'int(20)',
  ),
  96 => 
  array (
    'name' => 'emcmgr_96',
    'type' => 'int(20)',
  ),
  97 => 
  array (
    'name' => 'emcmgr_97',
    'type' => 'int(20)',
  ),
  98 => 
  array (
    'name' => 'emcmgr_98',
    'type' => 'int(20)',
  ),
  99 => 
  array (
    'name' => 'emcmgr_99',
    'type' => 'int(20)',
  ),
  100 => 
  array (
    'name' => 'emcmgr_100',
    'type' => 'int(20)',
  ),
  101 => 
  array (
    'name' => 'emcmgr_101',
    'type' => 'int(20)',
  ),
  102 => 
  array (
    'name' => 'emcmgr_102',
    'type' => 'int(20)',
  ),
  103 => 
  array (
    'name' => 'emcmgr_103',
    'type' => 'int(20)',
  ),
  104 => 
  array (
    'name' => 'emcmgr_104',
    'type' => 'int(20)',
  ),
  105 => 
  array (
    'name' => 'emcmgr_105',
    'type' => 'int(20)',
  ),
  106 => 
  array (
    'name' => 'emcmgr_106',
    'type' => 'int(20)',
  ),
  107 => 
  array (
    'name' => 'emcmgr_107',
    'type' => 'int(20)',
  ),
  108 => 
  array (
    'name' => 'emcmgr_108',
    'type' => 'int(20)',
  ),
  109 => 
  array (
    'name' => 'emcmgr_109',
    'type' => 'int(20)',
  ),
  110 => 
  array (
    'name' => 'emcmgr_110',
    'type' => 'int(20)',
  ),
  111 => 
  array (
    'name' => 'emcmgr_111',
    'type' => 'int(20)',
  ),
  112 => 
  array (
    'name' => 'emcmgr_112',
    'type' => 'int(20)',
  ),
  113 => 
  array (
    'name' => 'emcmgr_113',
    'type' => 'int(20)',
  ),
  114 => 
  array (
    'name' => 'emcmgr_114',
    'type' => 'int(20)',
  ),
  115 => 
  array (
    'name' => 'emcmgr_115',
    'type' => 'int(20)',
  ),
  116 => 
  array (
    'name' => 'emcmgr_116',
    'type' => 'int(20)',
  ),
  117 => 
  array (
    'name' => 'emcmgr_117',
    'type' => 'int(20)',
  ),
  118 => 
  array (
    'name' => 'emcmgr_118',
    'type' => 'int(20)',
  ),
  119 => 
  array (
    'name' => 'emcmgr_119',
    'type' => 'int(20)',
  ),
  120 => 
  array (
    'name' => 'emcmgr_120',
    'type' => 'int(20)',
  ),
  121 => 
  array (
    'name' => 'emcmgr_121',
    'type' => 'int(20)',
  ),
  122 => 
  array (
    'name' => 'emcmgr_122',
    'type' => 'int(20)',
  ),
  123 => 
  array (
    'name' => 'emcmgr_123',
    'type' => 'int(20)',
  ),
  124 => 
  array (
    'name' => 'emcmgr_124',
    'type' => 'int(20)',
  ),
  125 => 
  array (
    'name' => 'emcmgr_125',
    'type' => 'int(20)',
  ),
  126 => 
  array (
    'name' => 'emcmgr_126',
    'type' => 'int(20)',
  ),
  127 => 
  array (
    'name' => 'emcmgr_127',
    'type' => 'int(20)',
  ),
  128 => 
  array (
    'name' => 'emcmgr_128',
    'type' => 'int(20)',
  ),
  129 => 
  array (
    'name' => 'emcmgr_129',
    'type' => 'int(20)',
  ),
  130 => 
  array (
    'name' => 'emcmgr_130',
    'type' => 'int(20)',
  ),
  131 => 
  array (
    'name' => 'emcmgr_131',
    'type' => 'int(20)',
  ),
  132 => 
  array (
    'name' => 'emcmgr_132',
    'type' => 'int(20)',
  ),
  133 => 
  array (
    'name' => 'emcmgr_133',
    'type' => 'int(20)',
  ),
  134 => 
  array (
    'name' => 'emcmgr_134',
    'type' => 'int(20)',
  ),
  135 => 
  array (
    'name' => 'emcmgr_135',
    'type' => 'int(20)',
  ),
  136 => 
  array (
    'name' => 'emcmgr_136',
    'type' => 'int(20)',
  ),
  137 => 
  array (
    'name' => 'emcmgr_137',
    'type' => 'int(20)',
  ),
  138 => 
  array (
    'name' => 'emcmgr_138',
    'type' => 'int(20)',
  ),
  139 => 
  array (
    'name' => 'emcmgr_139',
    'type' => 'int(20)',
  ),
  140 => 
  array (
    'name' => 'emcmgr_140',
    'type' => 'int(20)',
  ),
  141 => 
  array (
    'name' => 'emcmgr_141',
    'type' => 'int(20)',
  ),
  142 => 
  array (
    'name' => 'emcmgr_142',
    'type' => 'int(20)',
  ),
  143 => 
  array (
    'name' => 'emcmgr_143',
    'type' => 'int(20)',
  ),
  144 => 
  array (
    'name' => 'emcmgr_144',
    'type' => 'int(20)',
  ),
  145 => 
  array (
    'name' => 'emcmgr_145',
    'type' => 'int(20)',
  ),
  146 => 
  array (
    'name' => 'emcmgr_146',
    'type' => 'varchar(100)',
  ),
  147 => 
  array (
    'name' => 'emcmgr_147',
    'type' => 'varchar(100)',
  ),
  148 => 
  array (
    'name' => 'emcmgr_148',
    'type' => 'varchar(100)',
  ),
  149 => 
  array (
    'name' => 'emcmgr_149',
    'type' => 'int(20)',
  ),
  150 => 
  array (
    'name' => 'emcmgr_150',
    'type' => 'varchar(100)',
  ),
  151 => 
  array (
    'name' => 'emcmgr_151',
    'type' => 'int(20)',
  ),
  152 => 
  array (
    'name' => 'emcmgr_152',
    'type' => 'int(20)',
  ),
  153 => 
  array (
    'name' => 'emcmgr_153',
    'type' => 'int(20)',
  ),
  154 => 
  array (
    'name' => 'emcmgr_154',
    'type' => 'int(20)',
  ),
  155 => 
  array (
    'name' => 'emcmgr_155',
    'type' => 'int(20)',
  ),
  156 => 
  array (
    'name' => 'emcmgr_156',
    'type' => 'int(20)',
  ),
  157 => 
  array (
    'name' => 'emcmgr_157',
    'type' => 'DATE',
  ),
  158 => 
  array (
    'name' => 'emcmgr_158',
    'type' => 'DATE',
  ),
  159 => 
  array (
    'name' => 'emcmgr_159',
    'type' => 'DATE',
  ),
  160 => 
  array (
    'name' => 'emcmgr_160',
    'type' => 'DATE',
  ),
  161 => 
  array (
    'name' => 'emcmgr_161',
    'type' => 'DATE',
  ),
  162 => 
  array (
    'name' => 'emcmgr_162',
    'type' => 'DATE',
  ),
  163 => 
  array (
    'name' => 'emcmgr_163',
    'type' => 'varchar(100)',
  ),
  164 => 
  array (
    'name' => 'emcmgr_164',
    'type' => 'varchar(100)',
  ),
  165 => 
  array (
    'name' => 'emcmgr_165',
    'type' => 'varchar(100)',
  ),
  166 => 
  array (
    'name' => 'emcmgr_166',
    'type' => 'varchar(100)',
  ),
  167 => 
  array (
    'name' => 'emcmgr_167',
    'type' => 'varchar(100)',
  ),
  168 => 
  array (
    'name' => 'emcmgr_168',
    'type' => 'varchar(100)',
  ),
  169 => 
  array (
    'name' => 'emcmgr_169',
    'type' => 'int(20)',
  ),
  170 => 
  array (
    'name' => 'emcmgr_170',
    'type' => 'int(20)',
  ),
  171 => 
  array (
    'name' => 'emcmgr_171',
    'type' => 'int(20)',
  ),
  172 => 
  array (
    'name' => 'emcmgr_172',
    'type' => 'int(20)',
  ),
  173 => 
  array (
    'name' => 'emcmgr_173',
    'type' => 'int(20)',
  ),
  174 => 
  array (
    'name' => 'emcmgr_174',
    'type' => 'int(20)',
  ),
  175 => 
  array (
    'name' => 'emcmgr_175',
    'type' => 'varchar(100)',
  ),
  176 => 
  array (
    'name' => 'emcmgr_176',
    'type' => 'varchar(100)',
  ),
  177 => 
  array (
    'name' => 'emcmgr_177',
    'type' => 'varchar(100)',
  ),
  178 => 
  array (
    'name' => 'emcmgr_178',
    'type' => 'varchar(100)',
  ),
  179 => 
  array (
    'name' => 'emcmgr_179',
    'type' => 'varchar(100)',
  ),
  180 => 
  array (
    'name' => 'emcmgr_180',
    'type' => 'varchar(100)',
  ),
);


}
?>