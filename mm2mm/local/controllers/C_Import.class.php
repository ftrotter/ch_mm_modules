<?php
$loader->requireOnce('ordo/Mm2mmImportMap.class.php');
$loader->requireOnce('includes/helpers/MedManTime.class.php');

class C_Import extends Controller {

	var $import_map	= array();
	var $note_import_map	= array();
	var $states	= array();
	var $payerTypes		= array();
	var $numberTypes		= array();
	var $addressTypes		= array();
	var $feeSchedules		= array();
	var $genderList		= array();
	var $maritalStatuslist		= array();
	var $default_state		= '';
	var $round		= '';
	var $count		= '';
	var $debug		= '';
	var $force_crc_sync		= true;
	var $week_array;
	var $import_recent_encounters = true;
	var $comment_strin = 'ehr:';


	function C_Import(){

		$address =& Celini::newOrdo('Address');
		$this->states = array_flip($address->getStatelist());

		$ip =& Celini::newOrdo('InsuranceProgram');
		$this->payerTypes = array_flip($ip->getPayerTypeList());

		$number =& Celini::newOrdo('CompanyNumber');
		$this->numberTypes = array_flip($number->getTypeList());

		$address =& Celini::newOrdo('CompanyAddress');
		$this->addressTypes = array_flip($address->getTypeList());

		$feeSchedule =& Celini::newOrdo('FeeSchedule');
		$this->feeSchedules = array_flip($feeSchedule->toArray());	

		$patient =& Celini::newOrdo('Patient');
		$this->genderList = array_flip($patient->getGenderList());
		$this->maritalStatusList = array_flip($patient->getMaritalStatusList());

		$this->default_state = 'MI';

		// WARNING do not set this to true unless you know what this does!!
		$this->force_crc_sync = false; // this will force the update of every crc record
						// forcing the import table to "forget" 
						// the differences between the import and the patfile.dat

		$this->debug = 0;
		$this->import_recent_encounters = false;
	}





	function importPatientArray($mapped_array) {

			$new = false;	
			$update = false;
		

			$this_medman_id = $mapped_array['account_number'];
				// We need a named array from the mm2mm map here...



				// echo $mapped_array['sex'] . " ";
			if($mapped_array['sex'] == 'M'){
				$mapped_array['gender'] = $this->genderList['Male'];
				//	echo "Male";
			}elseif($mapped_array['sex'] == 'F'){
					$mapped_array['gender'] = $this->genderList['Female'];
				//	echo "Female";
			}else{
				//	echo "Unknown";
				$mapped_array['gender'] = $this->genderList['Unknown'];
			}

			$mapped_array['marital_status'];
	
			if($mapped_array['marital_status'] == 'M'){
				$mapped_array['marital_status'] = $this->maritalStatusList['Married'];
				//	echo "Married";
			}elseif($mapped_array['marital_status'] == 'D'){
				$mapped_array['marital_status'] = $this->maritalStatusList['Divorced'];
				//	echo "Divorced";
			}elseif($mapped_array['marital_status'] == 'W'){
				$mapped_array['marital_status'] = $this->maritalStatusList['Widowed'];
				//	echo "Widowed";
			}else{
				//	echo "Unknown";
				$mapped_array['marital_status'] = $this->maritalStatusList['Unknown'];
			}



			$person = array(
				"first_name" => $mapped_array["first_name"],
				"middle_name" => $mapped_array["middle_initial"],
				"last_name" => $mapped_array["last_name"],
				"gender" => $mapped_array["gender"],
				"marital_status" => $mapped_array["marital_status"],
				"line1" => $mapped_array["address"],
				"line2" => $mapped_array["second_address"],
				"city" => $mapped_array["city"],
				"state" => $mapped_array["state"],
				"zip" => $mapped_array["zip"],
				"number" => $mapped_array["phone"],
				"record_number" => $mapped_array["account_number"],
				"old_id" => $mapped_array["account_number"],
				"identifier" => $mapped_array["social_security_number"],
				"date_of_birth" => $mapped_array["date_of_birth"],
				"work_phone" => $mapped_array["work_phone"]
			);
				


			$current_record_crc = crc32(implode($person));

			if(array_key_exists($this_medman_id,$this->import_map)){
				// Then this record has already been imported!!!
				// TODO 
				// we need to test to see if this record has changed. 
				// if it has then we need to update all of the information 
				// in mirrormed based on this information...

				$patient_id = $this->import_map[$this_medman_id]['mirrormed_id'];	
		
				if($this->import_map[$this_medman_id]['data_crc'] == $current_record_crc){ //this record has not changed!!
				//	echo "."; // this record has NOT changed
				}else{
					echo 'x'; // this record HAS changed
					$update = true;
				}
									
				if($this->force_crc_sync){
			
			$force_sync_sql = "UPDATE `mm2mm_import_map` SET `data_crc` = '$current_record_crc' WHERE `mm2mm_import_map`.`medman_id` = $this_medman_id AND `mm2mm_import_map`.`medman_file` = 'patfile.dat' LIMIT 1 ;";

			//	echo "updating $this_medman_id with $force_sync_sql \n";


				$res = $db->execute($force_sync_sql);

			
					$update = false;

				}	

				
			}else{
					// Then this record has not been imported yet!
				$new = true;
				$patient_id = 0; // forces the creation of a new record!!

			}

	

			if($new || $update){

				//echo "Maritial status = " . $mapped_array['marital_status'] . "\n";
				$patient =& Celini::newOrdo('Patient',$patient_id); //$patient_id will be zero for new patients!!
				$patient->populate_array($person);
				$patient->set('identifier_type',1);
				$patient->set('type',1);
				$patient->persist();
				$patient_id = $patient->get('id');

				
				$patient_key=$patient->get('id');
				$mem = memory_get_usage();
				//echo "\nr $this->round c $this->count m $mem mapped medman $this_medman_id to mirrormed $patient_key  \t";

				
				if(strlen($mapped_array['address'])>0 && $mapped_array['address'] != 0){
					$address_key = $this->_save_address($patient,$person);
				}
				
				if(strlen($mapped_array['phone'])>0 && $mapped_array['phone'] != 0 ){
					$number_key = $this->_save_phone($patient,$person,'Home');
				}
				if(strlen($mapped_array['work_phone'])>0 && $mapped_array['work_phone'] != 0){
					$person['number'] = $mapped_array['work_phone'];
					$work_number_key = $this->_save_phone($patient,$person,'Work');
				}




				$time = time();

				$importMap =& Celini::newOrdo('Mm2mmImportMap');
				$import_arguments = array(
					'medman_id' => $this_medman_id,
					'medman_file' => 'patfile.dat',
					'medman_field_group' => 'patient',
					'mirrormed_id' => $patient_key,
					'mirrormed_table' => 'patient',
					'data_crc' => $current_record_crc,
					'update_date' => date ("Y-m-d H:i:s", $time),
					'initial_import_date' => date ("Y-m-d H:i:s", $time),

						);
				$importMap->populate_array($import_arguments);
				$importMap->persist();
				unset($importMap);
				unset($patient);

			//	echo "now I know that the date_last_modified = ".$mapped_array['date_last_modified']."\n";
			}// end if new or update


			$this->_import_encounter($patient_id,$mapped_array);

			



	}

	function importComments($officenotesfile){
	
		
		$debug = true;
		$limit = 0; /// the limit to importset to 0 to import everything



		//echo "finding file...\n";
		if(!file_exists($officenotesfile) || !is_readable($officenotesfile)){
			if($this->debug){echo "ERROR: no such officenotes file: $officenotesfile or file is not readable";}
			return false;
		}



		//echo "loading mm2mm...\n";
		$GLOBALS['loader']->requireOnce('/includes/map.class.php');
		$GLOBALS['loader']->requireOnce('/includes/maps/offnotes.map.php');
		//echo "creating patfile map...\n";
		$offnotes_map = new offnotes_Map();


		if(count($this->import_map) == 0){

			$this->create_import_map();
		}
	
		
		$time = filemtime($officenotesfile);

       		$fp = fopen($officenotesfile, "r");

		$throw_away = fgetcsv($fp, 8000, ","); //we do not count the first line.




        	//get the first line of the MedMan file...
		
		$this->count = 0;
		$this->round = 0;	

       	/*	while($file_line_array = fgetcsv($fp, 8000, ',','"')){
			//echo $count;
*/

		$record_array = array(1 => 1);
		$batch_size = 50000;	
		$inserted = 0;
		while($record_array = $offnotes_map->parse_file($officenotesfile,$batch_size)){
			echo " NB $this->count ";
			$this->round++;
		   foreach($record_array as $key => $mapped_array){

			$this->count++;
			if($this->count > $limit && $limit != 0){
				echo "count $this->count over limit $limit\n";
				return;
			}
		//	echo "I would have imported .... \n\n\n";
		//	var_export($mapped_array);
		//	echo "\n\n";
			$this->importNoteArray($mapped_array);


	    	}// end forloop	
	
		}// end parser while statment




	}




	function importNoteArray($mapped_array) {

			$new = false;	
			$update = false;
		

			$this_medman_id = $mapped_array['patient_id'];
			$this_note_id = $mapped_array['offnotes_id'];
				// We need a named array from the mm2mm map here...

			if(!array_key_exists($this_medman_id,$this->import_map)){
				echo "medman patient $this_medman_id not imported!! \n";
				return;
			}

			$patient_id = $this->import_map[$this_medman_id]['mirrormed_id'];//switch from MedMan to MirrorMEd ids.
			$note_array = array(
				"patient_id" => $patient_id,
				"user_id" => 0, //theortically we could map between Medman and MirrorMed accounts...
				"priority" => 1,
				"note_date" => date('Y-m-d H:i:s'),
				"note" => nl2br($mapped_array['note']). "Note From Medical Manager " . $mapped_array['note_source']."<br>\n",
				"reason" => 0,//move to enum lookup
				"deprecated" => 0, // for now.. not deprecated	 
			);
				
			//var_export($note_array);


			$current_record_crc = crc32($mapped_array['note']);

			if(array_key_exists($this_medman_id,$this->note_import_map)){
				// Then this record has already been imported!!!
				// TODO 
				// we need to test to see if this record has changed. 
				// if it has then we need to update all of the information 
				// in mirrormed based on this information...

				$note_id = $this->note_import_map[$this_note_id]['mirrormed_id'];	
		
				if($this->note_import_map[$this_medman_id]['data_crc'] == $current_record_crc){ //this record has not changed!!
				//	echo "."; // this record has NOT changed
				}else{
					echo 'x'; // this record HAS changed
					$update = true;
				}
									
				if($this->force_crc_sync){
			
			$force_sync_sql = "UPDATE `mm2mm_import_map` SET `data_crc` = '$current_record_crc' WHERE `mm2mm_import_map`.`medman_id` = $this_medman_id AND `mm2mm_import_map`.`medman_file` = 'offnotes.dme'  LIMIT 1 ;";

			//	echo "updating $this_medman_id with $force_sync_sql \n";


				$res = $db->execute($force_sync_sql);

			
					$update = false;

				}	

				
			}else{
					// Then this record has not been imported yet!
				$new = true;
				$note_id = 0; // forces the creation of a new record!!

			}

	

			if($new || $update){

				//echo "Maritial status = " . $mapped_array['marital_status'] . "\n";
				$note =& Celini::newOrdo('PatientNote',$note_id); //$patient_id will be zero for new patients!!
				$note->populate_array($note_array);
				$note->persist();
				$note_id = $note->get('id');

				
				$note_key=$note->get('id');

				$time = time();

				$importMap =& Celini::newOrdo('Mm2mmImportMap');
				$import_arguments = array(
					'medman_id' => $this_note_id,
					'medman_file' => 'offnotes.dme',
					'medman_field_group' => 'notes',
					'mirrormed_id' => $note_key,
					'mirrormed_table' => 'patient_note',
					'data_crc' => $current_record_crc,
					'update_date' => date ("Y-m-d H:i:s", $time),
					'initial_import_date' => date ("Y-m-d H:i:s", $time),

						);
				$importMap->populate_array($import_arguments);
				$importMap->persist();
				unset($importMap);
				unset($patient);

			//	echo "now I know that the date_last_modified = ".$mapped_array['date_last_modified']."\n";
			}// end if new or update



	}











//	What has been imported and what has not? If it has been imported what is the new id in MirrorMed?
// 	To know this we need to read in the entire mm2mm_import_map into an array indexed by medical manager
// 	patient ids. this function does that work. 

	function create_import_map(){

		$seen_list_sql = "	SELECT *
					FROM `mm2mm_import_map`
					WHERE `medman_field_group` = 'patient'
		";


		//echo "getting database instance...\n";
		$db =& Celini::dbInstance();
		$res = $db->execute($seen_list_sql);
		$import_map = array();
		//echo "looping over import map query results...\n";
		while($res && !$res->EOF) {
		//	echo "pulling in ". $res->fields['medman_id'] . "\n";
			$import_map[$res->fields['medman_id']] = $res->fields;
			$res->MoveNext();
		}

		$this->import_map = $import_map;

		$seen_list_sql = "	SELECT *
					FROM `mm2mm_import_map`
					WHERE `medman_field_group` = 'note'
		";


		//echo "getting database instance...\n";
		$db =& Celini::dbInstance();
		$res = $db->execute($seen_list_sql);
		$note_import_map = array();
		//echo "looping over import map query results...\n";
		while($res && !$res->EOF) {
		//	echo "pulling in ". $res->fields['medman_id'] . "\n";
			$note_import_map[$res->fields['medman_id']] = $res->fields;
			$res->MoveNext();
		}

		$this->note_import_map = $note_import_map;

	}


	function import($patfile){
	
		$debug = true;
		$limit = 0; /// the limit to importset to 0 to import everything



		//echo "finding file...\n";
		if(!file_exists($patfile) || !is_readable($patfile)){
			if($this->debug){echo "ERROR: no such patfile: $patfile or file is not readable";}
			return false;
		}



		//echo "loading mm2mm...\n";
		$GLOBALS['loader']->requireOnce('/includes/mirrormed_data_map.php');
		$GLOBALS['loader']->requireOnce('/includes/map.class.php');
		$GLOBALS['loader']->requireOnce('/includes/maps/patfile.map.php');
		
		//echo "creating patfile map...\n";
		$patfile_map = new patfile_Map();
		
		if(count($this->import_map) == 0){

			$this->create_import_map();
		}


		//echo "taking timestamp...\n";
		$time = filemtime($patfile);

       		$fp = fopen($patfile, "r");

		$throw_away = fgetcsv($fp, 8000, ","); //we do not count the first line.




        	//get the first line of the MedMan file...
		
		$this->count = 0;
		$this->round = 0;	

       	/*	while($file_line_array = fgetcsv($fp, 8000, ',','"')){
			//echo $count;
*/

		$record_array = array(1 => 1);
		$batch_size = 500;	
		$inserted = 0;
		while($record_array = $patfile_map->parse_file($patfile,$batch_size)){
			echo " NB $this->count ";
			$this->round++;
		   foreach($record_array as $key => $mapped_array){

			$this->count++;
			if($this->count > $limit && $limit != 0){
				echo "count $this->count over limit $limit\n";
				return;
			}

			$this->importPatientArray($mapped_array);


	    	}// end forloop	
	
		}// end parser while statment


		echo "\n";
	} // end function

	function _import_encounter($patient_id,$person){

		echo "checking encounter... \n";

		if(!isset($this->week_array)){
			$this->week_array = array(
				//'today' => '20010214'
				'today' => date('Ymd'),
				'yesterday' => date('Ymd',mktime(0, 0, 0, date("m")  , date("d") -1 , date("Y"))),
				'todayminus2' => date('Ymd',mktime(0, 0, 0, date("m")  , date("d") -2 , date("Y"))),
				'todayminus3' => date('Ymd',mktime(0, 0, 0, date("m")  , date("d") -3 , date("Y"))),
				'todayminus4' => date('Ymd',mktime(0, 0, 0, date("m")  , date("d") -4 , date("Y"))),
				'todayminus5' => date('Ymd',mktime(0, 0, 0, date("m")  , date("d") -5 , date("Y"))),
				'todayminus6' => date('Ymd',mktime(0, 0, 0, date("m")  , date("d") -6 , date("Y"))),
			
			);


		}

		$import = false;

		if(isset($person['last_procedure'])){
			if($this->import_recent_encounters){ //then we are only importing as of a week ago.
				foreach($this->week_array as $day => $date){

					if($person['last_procedure'] == $date){
						$import = true;
					}

				}
			}else{
				$import = true;
			}
		}
		
		$last_procedure = $person['last_procedure'];
		$last_procedure_iso = date('Y-m-d',MedManTime::medman_fields2timestamp($last_procedure));

		if(!$this->didPatientHaveEncounterOn($patient_id,$last_procedure_iso)){

			echo "NO encounter on $last_procedure_iso from $last_procedure for $patient_id making one \n";

		$encounter =& Celini::newORDO('Encounter',array( 0 ,$patient_id));
		$encounter->set('date_of_treatment',$last_procedure_iso);
		$encounter->set('status','open');
		$encounter->persist();


		}else{
		//	echo "There IS an encounter on $last_procedure_iso from $last_procedure for $patient_id \n";

		}



	}

	function didPatientHaveEncounterOn($patient_id,$iso_date_string) {

		$id = EnforceType::int($patient_id);

		$sql = "SELECT *
			FROM `encounter`
			WHERE `date_of_treatment` = '$iso_date_string'
			AND patient_id = $id ";

		$db = new clniDb();;
		$res = $db->execute($sql);
		if($res->EOF) {
			return false;
		}else{
			return true;
		}

	}



	function _save_phone(&$patient,$person,$this_phone_type){
		$number_key = 0;
		if(strlen($person['number'])>0){
			$number =& $patient->numberByType($this_phone_type);
			$number->set('number',$person['number']);
			$number->set('notes','From MedMan');
			$number->persist();
			$number_key = $number->get('id');
			unset($number);
		}
		unset($person);
		return($number_key);

	}




	function _save_address(&$patient,$person){
		$address_key = 0;
		if(strlen($person['line1'])>0){
			$address =& $patient->address();
			$address->populate_array($person);
		//	if($person["state"]==""){$person["state"]=$this->default_state;}
		//	if(strlen($person["state"])>2){$person["state"]=$this->default_state;}
			if(isset($this->states[strtoupper($person['state'])])){
				$address->set('state',$this->states[strtoupper($person['state'])]);
			}else{
				$address->set('state',$this->states[strtoupper($this->default_state)]);
			}
			$address->set('type',1);
			$address->set('notes','From MedMan');
			$address->set('name','From MedMan');
			$address->persist();
			$address_key = $address->get('id');
			unset($address);
		}
		unset($person);
		return($address_key);
	}


	
}
?>
