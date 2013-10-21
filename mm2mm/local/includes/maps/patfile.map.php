<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for patfile.dat
if(file_exists('map.class.php')){
	require_once("map.class.php"); 
}elseif(file_exists('../map.class.php')){
	
	require_once('../map.class.php');
}// if neither of these work, lets hope its already loaded...


class patfile_Map extends MedManMap{
	
	var $nick_name = "patfile";
	var $file_name = "patfile.dat";
	var $table_name = "mm2mm_patfile";
	var $id_field = "account_number";
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
    'name' => 'link_to_actfile_last_billed_item',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'link_to_insfile_via_index',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'date_last_modified',
    'type' => 'DATE',
  ),
  5 => 
  array (
    'name' => 'link_to_actfile_last_item',
    'type' => 'int(20)',
  ),
  6 => 
  array (
    'name' => 'balance',
    'type' => 'int(20)',
  ),
  7 => 
  array (
    'name' => 'balance_forward',
    'type' => 'int(20)',
  ),
  8 => 
  array (
    'name' => 'unapplied_credit',
    'type' => 'int(20)',
  ),
  9 => 
  array (
    'name' => 'date_of_account',
    'type' => 'DATE',
  ),
  10 => 
  array (
    'name' => 'referring_doctor_id',
    'type' => 'int(20)',
  ),
  11 => 
  array (
    'name' => 'patfile_bill_type_enum', //manual says char 1= bill type char 2 =stmt type can be 1-9 for both values. 0 for char 2 = suppress statement.
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'monthly_payment_amount',
    'type' => 'int(100)',
  ),
  13 => 
  array (
    'name' => 'number_of_insurance_plans',
    'type' => 'int(20)',
  ),
  14 => 
  array (
    'name' => 'number_of_dependants',
    'type' => 'int(20)',
  ),
  15 => 
  array (
    'name' => 'doctor_number',
    'type' => 'int(20)',
  ),
  16 => 
  array (
    'name' => 'last_name',
    'type' => 'varchar(100)',
  ),
  17 => 
  array (
    'name' => 'first_name',
    'type' => 'varchar(100)',
  ),
  18 => 
  array (
    'name' => 'middle_initial',
    'type' => 'varchar(100)',
  ),
  19 => 
  array (
    'name' => 'address',
    'type' => 'varchar(100)',
  ),
  20 => 
  array (
    'name' => 'second_address',
    'type' => 'varchar(100)',
  ),
  21 => 
  array (
    'name' => 'city',
    'type' => 'varchar(100)',
  ),
  22 => 
  array (
    'name' => 'state',
    'type' => 'varchar(100)',
  ),
  23 => 
  array (
    'name' => 'zip',
    'type' => 'varchar(100)',
  ),
  24 => 
  array (
    'name' => 'phone',
    'type' => 'int(20)',
  ),
  25 => 
  array (
    'name' => 'work_phone',
    'type' => 'int(20)',
  ),
  26 => 
  array (
    'name' => 'sex',
    'type' => 'varchar(100)',
  ),
  27 => 
  array (
    'name' => 'date_of_birth',
    'type' => 'DATE',
  ),
  28 => 
  array (
    'name' => 'patfile_28',
    'type' => 'int(20)',
  ),
  29 => 
  array (
    'name' => 'social_security_number',
    'type' => 'int(20)',
  ),
  30 => 
  array (
    'name' => 'last_payment',
    'type' => 'DATE',
  ),
  31 => 
  array (
    'name' => 'amount_last_payment',
    'type' => 'int(20)',
  ),
  32 => 
  array (
    'name' => 'last_payment_type_inactive',
    'type' => 'varchar(100)',
  ),
  33 => 
  array (
    'name' => 'last_bill',
    'type' => 'DATE',
  ),
  34 => 
  array (
    'name' => 'amount_last_bill',
    'type' => 'int(20)',
  ),
  35 => 
  array (
    'name' => 'year_to_date_charges',
    'type' => 'int(20)',
  ),
  36 => 
  array (
    'name' => 'patient_due_balance',
    'type' => 'int(20)',
  ),
  37 => 
  array (
    'name' => 'patfile_account_status_enum',// 0=inactive,1=active,2=referred_to_collection,3=temporary,4-99 = user defined
    'type' => 'int(20)',
  ),
  38 => 
  array (
    'name' => 'patfile_extended_info_enum', // 0=none,1=comment,2=full,neg val = supress comment display
    'type' => 'int(20)',
  ),
  39 => 
  array (
    'name' => 'comment_count',
    'type' => 'int(20)',
  ),
  40 => 
  array (
    'name' => 'discount_percentage',
    'type' => 'int(20)',
  ),
  41 => 
  array (
    'name' => 'last_procedure',
    'type' => 'DATE',
  ),
  42 => 
  array (
    'name' => 'last_diagnosis',
    'type' => 'varchar(100)',
  ),
  43 => 
  array (
    'name' => 'last_diagnosis_2',
    'type' => 'varchar(100)',
  ),
  44 => 
  array (
    'name' => 'patient_id',
    'type' => 'varchar(100)',
  ),
  45 => 
  array (
    'name' => 'patient_note_number',
    'type' => 'int(20)',
  ),
  46 => 
  array (
    'name' => 'patient_class',
    'type' => 'varchar(100)',
  ),
  47 => 
  array (
    'name' => 'patient_word_processor_id',
    'type' => 'varchar(100)',
  ),
  48 => 
  array (
    'name' => 'history_balance_at_last_purge',
    'type' => 'int(20)',
  ),
  49 => 
  array (
    'name' => 'patfile_49',
    'type' => 'int(20)',
  ),
  50 => 
  array (
    'name' => 'guarantor_as_patient',
    'type' => 'varchar(100)',
  ),
  51 => 
  array (
    'name' => 'last_diagnosis_3',
    'type' => 'varchar(100)',
  ),
  52 => 
  array (
    'name' => 'last_diagnosis_4',
    'type' => 'varchar(100)',
  ),
  53 => 
  array (
    'name' => 'marital_status',
    'type' => 'int(20)',
  ),
  54 => 
  array (
    'name' => 'employment_status',
    'type' => 'varchar(100)',
  ),
  55 => 
  array (
    'name' => 'employer_name',
    'type' => 'varchar(100)',
  ),
  56 => 
  array (
    'name' => 'work_phone_extension',
    'type' => 'varchar(100)',
  ),
  57 => 
  array (
    'name' => 'days_before_entering_collections',
    'type' => 'int(20)',
  ),
  58 => 
  array (
    'name' => 'collection_priority',
    'type' => 'varchar(100)',
  ),
  59 => 
  array (
    'name' => 'preferred_plan_1',
    'type' => 'int(20)',
  ),
  60 => 
  array (
    'name' => 'plan_1_depfile_id',
    'type' => 'int(20)',
  ),
  61 => 
  array (
    'name' => 'preferred_plan_2',
    'type' => 'int(20)',
  ),
  62 => 
  array (
    'name' => 'plan_2_depfile_id',
    'type' => 'int(20)',
  ),
  63 => 
  array (
    'name' => 'preferred_plan_3',
    'type' => 'int(20)',
  ),
  64 => 
  array (
    'name' => 'plan_3_depfile_id',
    'type' => 'int(20)',
  ),
  65 => 
  array (
    'name' => 'preferred_plan_4',
    'type' => 'int(20)',
  ),
  66 => 
  array (
    'name' => 'plan_4_depfile_id',
    'type' => 'int(20)',
  ),
  67 => 
  array (
    'name' => 'preferred_plan_5',
    'type' => 'int(20)',
  ),
  68 => 
  array (
    'name' => 'plan_5_depfile_id',
    'type' => 'int(20)',
  ),
  69 => 
  array (
    'name' => 'preferred_plan_6',
    'type' => 'int(20)',
  ),
  70 => 
  array (
    'name' => 'plan_6_depfile_id',
    'type' => 'int(20)',
  ),
  71 => 
  array (
    'name' => 'preferred_plan_7',
    'type' => 'int(20)',
  ),
  72 => 
  array (
    'name' => 'plan_7_depfile_id',
    'type' => 'int(20)',
  ),
  73 => 
  array (
    'name' => 'flags_reserved',
    'type' => 'int(20)',
  ),
  74 => 
  array (
    'name' => 'patient_hmo_id',
    'type' => 'int(20)',
  ),
  75 => 
  array (
    'name' => 'taxcode_reserved',
    'type' => 'varchar(100)',
  ),
  76 => 
  array (
    'name' => 'patfile_guarantor_relation_to_ipr_enum', // manual says 'string of 7 relation codes'
    'type' => 'varchar(100)',
  ),
  77 => 
  array (
    'name' => 'patfile_77',
    'type' => 'varchar(100)',
  ),
  78 => 
  array (
    'name' => 'year_to_date_charges_as_patient',
    'type' => 'varchar(100)',
  ),
  79 => 
  array (
    'name' => 'patfile_79',
    'type' => 'int(20)',
  ),
  80 => 
  array (
    'name' => 'patients_race',
    'type' => 'int(20)',
  ),
  81 => 
  array (
    'name' => 'patfile_81',
    'type' => 'int(20)',
  ),
  82 => 
  array (
    'name' => 'patfile_82',
    'type' => 'varchar(100)',
  ),
  83 => 
  array (
    'name' => 'patfile_83',
    'type' => 'varchar(100)'
  ),
);


}
?>
