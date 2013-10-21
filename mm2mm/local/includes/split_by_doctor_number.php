<?php
	// the first and only argument from the command line is a 	

	require_once('mirrormed_data_map.php');

	$this_server = $battlecreek;	


	$doctor_field_number = 15; //from patfile map


	$patfile = '/home/ftrotter/patfile.dat';

	//var_export($seen_medman_ids);

	$time = filemtime($patfile);

	//echo "file time is $time \n";

	        //echo "opening $file_name in parser";
       	$fp = fopen($patfile, "r");


// running this on the newly changed files
//	$throw_away = fgetcsv($fp, 8000, ","); //we do not count the first line.

        //get the first line of the MedMan file...
       	while($file_line = fgets($fp, 8000)){

		$file_line_array = explode(",",$file_line);

		//var_export($file_line);

		if($file_line_array[0] < 0){ // this is not a deleted record
			$this_doc = str_replace('"','',$file_line_array[$doctor_field_number]);
			if(array_key_exists($this_doc,$doctor2ehr)){
				if($doctor2ehr[$this_doc] == $this_server){
					// then this patientfile belongs on this server	
					echo $file_line;

				}else{

					//then it belongs on the other server	
				}

			}else{
				//then I have no map for it

			}

			
		}else{	
			//this is a blank from a deleted patient.	
		}	
	}


	

?>
