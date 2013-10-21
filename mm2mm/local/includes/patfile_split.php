<?php
	// the first and only argument from the command line is a 	

/*
CREATE DATABASE mm2mm;

USE mm2mm;

CREATE TABLE `seen_in_medman` (
  `medman_id` int(11) NOT NULL,
  `filetime` int(12) NOT NULL COMMENT 'file timestamp when this id appeared',
  `crc` int(10) NOT NULL
) ENGINE=MyISAM




*/
	require_once('mirrormed_data_map.php');

	foreach($locations as $id => $name){

		$file_name = $name . "_patfile.dat";
		$fh_array[$id] = fopen($file_name,'w');	

	}

	$doctor_field_number = 15; //from patfile map


	$number_changed = 0;
	$number_new = 0;


	if(array_key_exists(1,$argv)){
		$before = $argv[1];
	}else{
		$before = false;
	}

	require_once('mysql.php');

	$patfile = '/home/ftrotter/synseer/visser/patfiles/patfile.dat.3';

	$seen_list_sql = "SELECT * FROM `seen_in_medman` ";

	$result = mysql_query($seen_list_sql) or die('Query failed: ' . mysql_error());

	$seen_medman_ids = array();

        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$seen_medman_ids[$line['medman_id']] = $line;
        }

	//var_export($seen_medman_ids);

	$time = filemtime($patfile);

	//echo "file time is $time \n";

	        //echo "opening $file_name in parser";
       	$fp = fopen($patfile, "r");

	$throw_away = fgetcsv($fp, 8000, ","); //we do not count the first line.

        //get the first line of the MedMan file...
       	while($file_line = fgets($fp, 8000)){

		$file_line_array = explode(",",$file_line);

		//var_export($file_line);

		if($file_line_array[0] < 0){ // this is not a deleted record
			$this_medman_id = str_replace('"','',$file_line_array[1]);
			$this_doc = str_replace('"','',$file_line_array[$doctor_field_number]);
			if(strlen($this_doc)==0){
				$this_doc = -1;
			}
			if(!array_key_exists($this_doc,$doctor2ehr)){// then out mapping is limited... default..

				$doctor2ehr[$this_doc] = 1;

			}

			$this_medman_crc = crc32(implode($file_line_array));


			if(array_key_exists($this_medman_id,$seen_medman_ids)){
				//we have already seen this id
				// normally we do nothing here unless
				// we are printing based on timestamps
				if($before && $before < $seen_medman_ids[$this_medman_id]['filetime']){
					//then we are printing from a timestamp
					// and this line was added after our timestamp argument
					$server_id  =  $doctor2ehr[$this_doc];
					$current_fh = $fh_array[$server_id];
					fwrite($current_fh,$file_line);

				}

				if($seen_medman_ids[$this_medman_id]['crc'] != $this_medman_crc){
					// then this id has been modified.
					// and should be included...
					$number_changed++;
					$server_id  =  $doctor2ehr[$this_doc];
					$current_fh = $fh_array[$server_id];
					fwrite($current_fh,$file_line);

	
				$update_sql = "UPDATE `seen_in_medman` SET 
						`crc` = '$this_medman_crc' 
						WHERE `medman_id` = $this_medman_id ";

				mysql_query($update_sql) or die('Update Query failed: ' . mysql_error());


				}



			}else{
				
				$number_new++;	

				$insert_sql = "INSERT INTO `mm2mm`.`seen_in_medman` (
							`medman_id` ,
							`filetime` ,
							`crc`
						)
						VALUES (
							'$this_medman_id',
							'$time',
							'$this_medman_crc'
						)";

				mysql_query($insert_sql) or die('Insert Query failed: ' . mysql_error());
			//	echo "inserted $this_medman_id and time $time \n";

					//we should always insert new 	
					$server_id  =  $doctor2ehr[$this_doc];
					$current_fh = $fh_array[$server_id];
					fwrite($current_fh,$file_line);
			}

			
		}else{	
			//this is a blank from a deleted patient.	
		}	
	}


	echo "$number_new new lines $number_changed changed lines\n";
	

?>
