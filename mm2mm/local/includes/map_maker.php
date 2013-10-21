<?php

/// This main loops goes over the files and gives an indication as to what goes where..


	$data_dir = '/var/www/html/php4/ndta_data/';
	$map_dir = '/var/www/html/php4/mm2mm/maps/';
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
		
		echo "working on $file \n";
       		$map = make_map($file,$file_nick_name,$data_dir);
		//get rid of the .dat
		$php_file_name = $map_dir.$file_nick_name.".map.php";
		if (!$file_handle = fopen($php_file_name,"w")) { echo "Cannot open file $php_file_name\n"; }  
		if (!fwrite($file_handle,$map)) { echo "Cannot write to file $php_file_name\n"; } 
		else{ 
			echo "Created $php_file_name \n";   
		}
		fclose($file_handle); 
	}
    }

    closedir($handle);

}


// this function takes a single file and makes a map for it...
function make_map($file_name,$file_nick_name,$dir_name){

	$lines = file($dir_name.$file_name);
	$number_of_elements = count($lines[0]);
	$unique_index = 1;
	$last_value = 0;
	foreach($lines as $line){
		$line_array = split(",",$line);
		$new_count = count($line_array);
		if($new_count>$number_of_elements){
		//	echo "element count $number_of_elements to $new_count\n";
			$number_of_elements = $new_count;
		//	var_export($line);
		}
		if($line_array[$unique_index]==$last_value){
			$unique_index++;
			echo "moved index to $unique_index\n";
		}
		$last_value = $line[$unique_index];
	}
	//echo "number of elements $number_of_elements\n";
	$map_code = make_class($file_name,$file_nick_name,$number_of_elements,$unique_index);

	return($map_code);

}

function namer($string){ return $string;}

function make_class($file_name,$file_nick_name,$number_of_elements,$unique_index){


	$fields_dir = "/var/www/html/php4/mm2mm/fields/";
	$fields_file = $fields_dir.$file_nick_name.".field.php";
	$unique_field_name = $file_nick_name."_".$unique_index;

	if(!file_exists($fields_file )){// we are auto generating... should not happen...
		$variables = "";
		$i=0;
			//we do this just so we can rid ourselves of a starting comma!!
			$variables .= 	"array (\n\t\t".$i.' => array( "name" => "'.$file_nick_name.'_'.$i.'"'; 
			$variables .= 	"\n\t\t\t\t//,'type' => 'varchar(100)'"; 
			$variables .= 	"\n\t\t\t\t//,'type' => 'int(20)'"; 
			$variables .= 	"\n\t\t\t\t//,'type' => 'DATE'"; 
			$variables .= 	"\n\t\t)"; 


		for($i=1;$i<$number_of_elements;$i++){

			$variables .= 	",\n\n\t\t".$i.' => array( "name" => "'.$file_nick_name.'_'.$i.'"'; 
		//	$j = $i+1;
		//	if($i%10==9){ $variables .= ",\t"."//row $j";}

			$variables .= 	"\n\t\t\t\t//,'type' => 'int(20)'"; 
			$variables .= 	"\n\t\t\t\t//,'type' => 'varchar(100)'"; 
			$variables .= 	"\n\t\t\t\t//,'type' => 'DATE'"; 
			$variables .= 	"\n\t\t)"; 
		}

	
		$variables .= ");\n\n\n}\n?>";
	}else{
		require_once($fields_file);
		$variables = var_export($field_array,true);
		$variables .= ";\n\n\n}\n?>";


	}

//----------------------------------CODE IN CODE START
	$class_def =
'<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for '.$file_name.'

require_once("map.class.php"); 

class '.$file_nick_name.'_Map extends MedManMap{
	
	var $nick_name = "'.$file_nick_name.'";
	var $file_name = "'.$file_name.'";
	var $table_name = "mm2mm_'.$file_nick_name.'";
	var $id_field = "'.$id_field.'";
	var $default_type = "varchar(100)";
	var $fields = ';
//----------------------------------CODE IN CODE END


$class = $class_def.$variables;

return($class);


}


?>
