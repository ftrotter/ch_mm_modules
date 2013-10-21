<?php

/// This main loops goes over the files and gives an indication as to what goes where..


	$data_dir = '/var/www/html/php4/ndta_data/';
	$field_dir = '/var/www/html/php4/mm2mm/fields/';

if ($handle = opendir($data_dir)) {
    echo "Directory handle: $handle\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
	if(strcmp($file,'.')==0||strcmp($file,'..')==0){
		//not real files	
	}else{
		$file_name_array = split("\.",$file);
		$file_nick_name = $file_name_array[0];
		
		echo "working on $data_dir $file \n";
		$fields_file_name = $field_dir.$file_nick_name.".field.php";
       		$field = make_fields($file,$file_nick_name,$data_dir,$fields_file_name);
		//get rid of the .dat
		if (!$file_handle = fopen($fields_file_name,"w")) { echo "Cannot open file $fields_file_name\n"; }  
		if (!fwrite($file_handle,$field)) { echo "Cannot write to file $fields_file_name\n"; } 
		else{ 
			echo "Created $fields_file_name \n";   
		}
		fclose($file_handle); 
	}
    }

    closedir($handle);

}


// this function takes a single file and makes a map for it...
function make_fields($file_name,$file_nick_name,$dir_name,$field_file_name){
	
	echo "inside make_fields $dir_name$file_name";
	$lines = file($dir_name.$file_name);
	$number_of_elements = count($lines[0]);
	$unique_index = 1;// 0 is ususally reserved...
	$last_value = 0;

	// Lets look through the file and find the largest line 
	// and what appears to be a unique array
	$index_array = array();
	$count = 0;
	foreach($lines as $line){
		$line_array = split(",",$line);
		$new_count = count($line_array);
		if($new_count>$number_of_elements){
			echo "element count $number_of_elements to $new_count\n";
			$number_of_elements = $new_count;
			$big_line_array = $line_array;

		}
		if(in_array($line_array[$unique_index],$index_array)){
			$unique_index++;
			$index_array = array(); //empty the array...
			echo "moved index to $unique_index\n";
		}
		$index_array[] = $line[$unique_index];
		$count++;
		if($count==1000){break;}
	}
	echo "number of elements $number_of_elements\n";
	$field_code = parse_fields($file_name,$file_nick_name,$big_line_array,$unique_index,$field_file_name);

	return($field_code);

}

function namer($string){ return $string;}

function parse_fields($file_name,$file_nick_name,$big_line_array,$unique_index,$field_file_name){

	$fields = array();
	$count = 0;
	foreach($big_line_array as $element){
		$this_field = array();
		$this_field['name'] = "$file_nick_name"."_".$count;	
			$type = "int(20)";
		if(preg_match('/^"/',$element)){
			$type = "varchar(100)";
		}else{	
			if(strlen($element)==8 && 
					(
						preg_match("/^19/",$element)||
						preg_match("/^20/",$element)
						
					)
			){
				$type = "DATE";

			}	
		}
		$this_field['type'] = $type;
		$fields[] = $this_field;
		$count++;
	}


//	if(file_exists($field_file_name)){
//		include($field_file_name);
//	}

$fields_text = 
'<?php

	$id_field = '.$file_nick_name.'_'.$unique_index.';

	$field_array = 

';

$fields_text .= var_export($fields,true);

$fields_text .= "; \n\n ?>"; 

return($fields_text);
}


?>
