<?php

	$starting_dir = '/var/www/html/preview/user/documents/mmbarcode_uploads/';
	$working_dir = '/var/www/html/preview/user/documents/mmbarcode_working/';
	$zxing_dir = '/root/zxing/javase/';

	$GLOBALS['working_dir'] = $working_dir;
	$GLOBALS['zxing_dir'] = $zxing_dir;
	$GLOBALS['new_file_array'] = array();

	if($source_handle = opendir($starting_dir)){

		while (false !== ($file = readdir($source_handle))) {
       		 	if ($file != "." && $file != "..") {
			
				$old_file = $starting_dir . $file;
				$new_file = $working_dir . $file;
				copy($old_file,$new_file);
				process_file($new_file);

			}
	    	}	
	}


        foreach($GLOBALS['new_file_array'] as $code => $files){
                $merge_command = 'pdftk ';
                foreach($files as $file){
                        $merge_command .= $file . " ";
                }
                $merge_command .= "cat output /var/www/html/preview/user/documents/$code.pdf";
                //echo "using merge command \n $merge_command";
                exec($merge_command);
        }





function process_file($file_name){

	$rand_num = rand(1000,9999);

	$file_results = array();	
	


	$working_dir = $GLOBALS['working_dir'];
	$zxing_dir = $GLOBALS['zxing_dir'];
	chdir($working_dir);
	$split_command = "pdftk $file_name burst  output burst_$rand_num". '_%03d.pdf';
	//echo "running \n $split_command \n";
	exec($split_command);

	if($working_handle = opendir($working_dir)){
        	while (false !== ($file = readdir($working_handle))) {
                	if ($file != "." && $file != "..") {

				$pos = strpos($file,(string) $rand_num);
			//	echo "testing $file against $rand_num with a result of $pos\n";
               			if($pos > 0){
					$long_name = $working_dir . $file;
					$img_name = $working_dir . $file . '.jpg';
					$convert_command = "convert $long_name $img_name";
			//		echo "running \n $convert_command \n";
					exec($convert_command);				
					chdir($zxing_dir);
			
					$zxing_command = 
			"java -cp /root/zxing/javase/javase.jar:/root/zxing/core/core.jar com.google.zxing.client.j2se.CommandLineRunner $img_name";

					$result = exec($zxing_command);	
					//echo("Zxing result for $file: $result \n");	
				
					$no_image_found_string = "No barcode found";	
					$no_image_pos = strpos($result,$no_image_found_string);

					$could_not_load_string = "Could not load image";	
					$could_not_load_pos = strpos($result,$could_not_load_string);
				
					if($could_not_load_pos > 0){
						// this is not an image
					}

					$new_pdf_filename = '0';

					if($no_image_pos > 0){
					
						//this pdf is content and has no barcode. 
						//echo "no image found for $img_name\n";

					}else{
			//example URL you might find here
			// https://something.example.com/mirrormed/index.php/main/PatientDashboard/view?id=22314703&cat_id=1
						list($url, $args) = explode('?',$result);
						list($id_string,$cat_string) = explode('&',$args);
						list($throw_away,$id) = explode('=',$id_string);
						list($throw_away,$cat) = explode('=',$cat_string);
			
						//echo "\n result $result boils down to id $id and cat $cat\n";
						$new_pdf_filename = $id . "_" . $cat;
						
					}
	
					$file_results[$long_name] = $new_pdf_filename;
	
				}// if pos of rand_num
			}// no . or ..
        	}//while on dir loop


	//	var_export($file_results);


		$count = 1;
		$count_string = str_pad($count,3,'0',STR_PAD_LEFT);
		$file_string = $working_dir . "burst_$rand_num"."_$count_string.pdf";
		while(isset($file_results[$file_string])){
			
			$new_file_code = $file_results[$file_string];
			if($new_file_code !=0){
				$GLOBALS['new_file_array'][$new_file_code][] = $file_string;
				$last_file_code = $new_file_code;
			}else{
				$GLOBALS['new_file_array'][$last_file_code][] = $file_string;
			}



			$count++;
			$count_string = str_pad($count,3,'0',STR_PAD_LEFT);
			$file_string = $working_dir . "burst_$rand_num"."_$count_string.pdf";
		}

	//	echo "new array with gueses on the zeros\n";
	//	var_export($new_file_array);

	//moved to global save...
	/*
	foreach($new_file_array as $code => $files){
		$merge_command = 'pdftk '; 
		foreach($files as $file){
			$merge_command .= $file . " ";
		}
		$merge_command .= "cat output /var/www/html/preview/user/documents/$code.pdf";
		echo "using merge command \n $merge_command";
		exec($merge_command);
	}
	*/


        }// if open the dir




}//function end




?>
