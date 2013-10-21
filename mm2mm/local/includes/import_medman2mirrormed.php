<?php
	// the first and only argument from the command line is a 	

	require_once('mysql.php');
	require_once('mirrormed_data_map.php');

	$mirrormed_dir = "/var/www/html/mirrormed/";


	$patfile = '/home/ftrotter/synseer/visser/031208/patfile.dat';

	$seen_list_sql = "	SELECT *
				FROM `import_map`
				WHERE `medman_field_group` = 'patient' 
			";

	$result = mysql_query($seen_list_sql) or die('Query failed: ' . mysql_error());

	$import_map = array();

        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$import_map[$line['medman_id']] = $line;
        }

	$time = filemtime($patfile);

       	$fp = fopen($patfile, "r");

	$throw_away = fgetcsv($fp, 8000, ","); //we do not count the first line.

        //get the first line of the MedMan file...
       	while($file_line_array = fgetcsv($fp, 8000, ',','"')){

		if($file_line_array[0] < 0){ // this is not a deleted record
			$this_medman_id = $file_line_array[1];

			// We need a named array from the mm2mm map here...

			if(array_key_exists($this_medman_id,$import_map)){
				// we need to test to see if this record has changed. 
				// if it has then we need to update all of the information 
				// in mirrormed based on this information...
				
			}else{
				
				$insert_sql = "INSERT INTO `mm2mm`.`seen_in_medman` (
							`medman_id` ,
							`filetime`
						)
						VALUES (
							'$this_medman_id',
							 '$time'
						)";

				mysql_query($insert_sql) or die('Insert Query failed: ' . mysql_error());
			//	echo "inserted $this_medman_id and time $time \n";

					//we should always insert new 	
					echo $file_line;
			}

			
		}else{	
			//this is a blank from a deleted patient.	
		}	
	}


	

?>
