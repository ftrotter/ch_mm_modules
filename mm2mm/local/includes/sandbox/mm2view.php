<?php


if(array_key_exists('first_name',$_POST)||
	array_key_exists('first_name',$_POST)){//Then the form as been submitted

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	echo display_matching_patients($first_name,$last_name);

}else{// the form has not been displayed
	
	echo display_patient_search_form();

}


function display_matching_patients($first_name,$last_name){

	$dir = '/var/www/html/php4/callpointe_data/';
	$map_dir = '/var/www/html/php4/mm2mm/maps/';

	$patfile = $dir.'patfile.dat';
	$pat_map_file = $map_dir.'patfile.map.php';
	require_once($pat_map_file);

	$aptfile = $dir.'aptfile.dat';
	$apt_map_file = $map_dir.'aptfile.map.php';
	require_once($apt_map_file);

	require_once('helpers/MedManTime.class.php');
	$MedManTime = new MedManTime();


	$patmap = new patfile_Map();
	$patient_last_name_matches = $patmap->parse_file_match($patfile,'last_name',$last_name);
	//$patmap = new patfile_Map();
	//$patient_first_name_matches = $patmap->parse_file_match($patfile,'first_name',$first_name);

	$all_matches = $patient_last_name_matches;

	if(count($all_matches) < 1){
		echo "No matching patients<br>";
		exit();
	}

	echo "<table>\n";
		echo display_patient_header($all_matches[0][0]);
	foreach($all_matches as $key => $patient_record){

		echo display_patient_line($patient_record[0]);
	}
	echo "</table>\n";

}

function display_patient_header($record){

$to_return = "<tr>\n";

	foreach($record as $name => $value)
		$to_return .= "<td>$name</td>\n";

 
$to_return .= "</tr>\n";

return $to_return;
}


function display_patient_line($record){

$to_return = "<tr>\n";

	foreach($record as $name => $value)
		$to_return .= "<td>$value</td>\n";

 
$to_return .= "</tr>\n";

return $to_return;
}

function display_patient_search_form(){


	$html_form = "
	<br><br><br><br>
	<form method='post' action=''>
	<table align='center'><tr><td>
	Enter First Name:</td><td><input type='text' name='first_name'></td>
	</tr><tr>
	<td>Enter Last Name:</td><td><input type='text' name='last_name'></td>
	</tr><tr>
	<td></td><td><input type='submit' name='submit' value='Search'></td>
	</td></tr></table>
	</form>";

return $html_form;
}


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
	$MedManTime = new MedManTime();

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
			$my_time_stamp = $MedManTime->medman_fields2timestamp(
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
