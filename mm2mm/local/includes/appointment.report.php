<?php

	$company_id = "123456789";

	//var_export($provider_array);

	$testing=true;

	$tomorrow = date('Ymd');
	$tomorrow_time = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
	$tomorrow = date('Ymd',$tomorrow_time);
	for($i=2;$i<8;$i++){//start day after tomorrow and go six days after that
		$next_day_time = mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"));
		$next_week[] = date('Ymd',$next_day_time);
	}

//	echo "would be using $tomorrow\n";
//	var_export($next_week);

	if($testing){
	$tomorrow = "20060831";
	$next_week = array (
				"20060901",
				"20060902",
				"20060903",
				"20060904",
				"20060905",
				"20060907",
				"20060908");
	}


	$tomorrow_lines = appointments($tomorrow);
//	$nextweek_lines = appointments($next_week);

	$apt_report = "$company_id"."01.apt";
//	$brf_report = "$company_id"."01.brf";

//	echo $tomorrow_lines."\n";
//	echo $nextweek_lines."\n";
	write_report($apt_report,$tomorrow_lines);
//	write_report($brf_report,$nextweek_lines);

function write_report($file_name,$lines){

	if (!$file_handle = fopen($file_name,"w")) { echo "Cannot open file $file_name\n"; }  
	if (!fwrite($file_handle,$lines)) { echo "Cannot write to file $file_name\n"; } 
	else{ 
		echo "Created $file_name \n";   
	}
	fclose($file_handle); 

}


function appointments($day_array){

	$dir = '/var/www/html/php4/callpointe_data/';
	$map_dir = '/var/www/html/php4/mm2mm/maps/';
	
	$debug = true;


	if(!is_array($day_array)){
		$day_array = array($day_array);
	}

	$today_string = date('m-d-Y');



	$aptfile = $dir.'aptfile.dat';
	$apt_map_file = $map_dir.'aptfile.map.php';
	require_once($apt_map_file);
	$aptmap = new aptfile_Map();

	$patfile = $dir.'patfile.dat';
	$pat_map_file = $map_dir.'patfile.map.php';
	require_once($pat_map_file);


	$drfile = $dir.'drfile.dat';
	$dr_map_file = $map_dir.'drfile.map.php';
	require_once($dr_map_file);

	require_once('helpers/MedManTime.class.php');

	$drmap = new drfile_Map();

	$provider_array = array();
	while($doctor_array = $drmap->parse_file($drfile,5000)){

		foreach($doctor_array as $key => $doctor){
		 	
		if($debug){
			echo "found provider ".$doctor['provider_last_name']."\n";
		}
			// recreate the array based on provider number...
			$provider_array[$doctor['provider_number']] = $doctor; 
				
		}
	}



	foreach($day_array as $day){
		$aptmap = new aptfile_Map();
		if($debug){
			echo "preparing to parse aptfile \n";
		}
		$matches = $aptmap->parse_file_match($aptfile,'appointment_date',$day);
			$appointment_array = $matches[$day];
			foreach($appointment_array as $key => $appointment){
				if($debug){
					echo "found appointment ".$appointment['appointment_unique_id']." on $day\n";
				}
	
				$patient_id_array[] = $appointment['account_number'];		
			}
		$patmap = new patfile_Map();
		$patient_matches = $patmap->parse_file_match($patfile,'account_number',$patient_id_array);

		foreach($appointment_array as $key => $appointment){


			// Add provider information to the appointment record
			$my_provider = $provider_array[$appointment['provider_number']];
			$appointment['provider_name'] = $my_provider['provider_last_name'].", ".
								$my_provider['provider_first_name'];			
			// Add date information to the appointment record
			$my_time_stamp = MedManTime::medman_fields2timestamp(
									$appointment['appointment_date'],
									$appointment['appointment_time']);

			$appointment['today'] = $today_string;		
			$appointment['appt_time_and_date'] =	date('m-d-Y h:i a',$my_time_stamp);


			if(array_key_exists($appointment['account_number'],$patient_matches)){
					// do we have a matching patient record?

				$patient = $patient_matches[$appointment['account_number']][0];

				if($debug){
					echo "appointment ".$appointment['appointment_unique_id']." matches patient ".
					$patient['last_name']."\n";
				}

				//Add patient phone information to appointment record
	 			if(strlen($appointment['special_phone_number'])==10){//then we have a number specified in the aptfile
					$appointment['phone']=$appointment['special_phone_number'];// use it
				}else{// we need to parse the patfile to get the number...

					$appointment['phone']=$patient['phone'];

				}

			}//if check for matching patient record

			$lines .= make_line($appointment);	

		}//foreach appointment



	}//foreach day in day_array

	return($lines);

}



function make_line($appointment){

				$line = "Y~".
					$appointment['account_number']."~".
					$appointment['appointment_for']."~".
					$appointment['appt_time_and_date']."~".
					$appointment['phone']."~".
					$appointment['provider_name']."~".
					$appointment['today']."~".
					$appointment['provider_number']."~".
					"0"."~".//hardcoded english see if we can get from pat or dep file
					"N"."~".//hardcoded do_not_call = N
					$appointment['appointment_reason']."~".		
					$appointment['appointment_location']."~".		
					$appointment['appointment_reason']."~".		
					"\n";

	return $line;

}


?>
