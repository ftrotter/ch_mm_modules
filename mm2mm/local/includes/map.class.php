<?php

require_once('parser.class.php');
//This is the base class that provides basic file parsing, SQL saving
// and other generic goodies that every map is going to need...
class MedManMap { //Abstract...

var $parser = NULL;


function parse_file_match($file,$field_to_match,$array_of_values_to_match){

	if(!is_array($array_of_values_to_match)){
		$array_of_values_to_match = array ($array_of_values_to_match);	
	}


	$debug = false;

	if($this->parser == NULL){	
		$my_parser = new MedManParser($file);
		$this->parser = $my_parser;
	}

	if($debug){echo "about to call get on parser \n";}

	while($rows_of_data = $this->parser->get(10000)){
		$rows_returned = count($rows_of_data);
		if($debug){echo "parser returned $rows_returned rows of data from $file\n";}

		$i = 1;

		foreach($rows_of_data as $data){
		//this statment should be based on a per-file
			if(!$this->is_not_deleted($data)){
				$pos_records++;
				continue;	
			}

			if($results = $this->map($data)){

				foreach($array_of_values_to_match as $value_to_match){
					if(strcmp($results[$field_to_match],$value_to_match)==0){
						$matching_data[$value_to_match][]=$results;
					//	echo "$i being added\n";
					}
				}
			}
			$i++;	
		}
	}

	$this->parser->close_file();

	$match_count = count($matching_data);
	if($debug){echo "matched $match_count from $file where $field_to_match = $value_to_match\n";}
	return($matching_data);

}

function parse_file($file,$rows_to_get = 10000){

	if($this->parser == NULL){	
		$my_parser = new MedManParser($file);
		$this->parser = $my_parser;
	}

	$rows_of_data = $this->parser->get($rows_to_get);

	if(!$rows_of_data) {return(false);}

	$pos_records = 0;

	foreach($rows_of_data as $data){

		//this statment should be based on a per-file
		if(!$this->is_not_deleted($data)){
			$pos_records++;
			break;	
		}
	
		if($valid_results = $this->map($data)){
				$parsed_data[]=$valid_results;
		}
	
	}

	return($parsed_data);

}


function is_not_deleted($line){

	$valid = true;

	if($line[0] > -1){

		$valid = false;

	}

	return($valid);

}

function test_record($record){// designed to be overridden 

	$valid = true;
	return($valid);

}



function map($row){

	if(!array_key_exists(1,$row)){ return false;}

	$debug = true;
	
	$row_count = count($row);
	$field_count = count($this->fields);


	if($debug){
	
				


	}



	foreach($this->fields as $num => $field_array){
		if($num >= $row_count){
			break;
		} 
		$name = $field_array['name'];
		$return_array[$name] = $row[$num];
	}

	if($this->test_record($return_array)){
		return($return_array);// according to the map this is a nice record
	}else{
		return(false);// according to the map this is naughty record!!!
	}
}

function insert_array($record){

$sql = "INSERT into `".$this->table_name."` (
	`".$this->nick_name."_id`";  

	foreach($this->fields as $num => $field_array){
		$name = $field_array['name'];
		$sql .= ",\n\t`$name`";
	}
//	var_export($record);
//	echo $this->id_field_number;

	$id = (int)$record[$this->id_field];

	$sql .= ") \n VALUES (\n ".
		"'$id'";  

	foreach($this->fields as $num => $field_array){
		$name = $field_array['name'];
		$safe_value = mysql_real_escape_string($record[$name]);
		$sql .= ",\n\t\t'$safe_value'";
	}
 
	$sql .= ")\n";

	//echo $sql;

	$result = mysql_query($sql);
	if(!$result){echo(mysql_error()); die(" $sql DOh!");}	
	return $result;
	// change this to running the query...

}

function update_array($record){

$id = 		$this->nick_name."_id";
$id_value = 	$record[$this->id_field];

$sql = "UPDATE `".$this->table_name."` SET 
	`$id` = '$id_value'";  

	foreach($this->fields as $num => $field_array){
		$name = $field_array['name'];
		$safe_value = mysql_real_escape_string($record[$name]);

		$sql .= ",\n\t`$name` = '$safe_value'";
	}

	$sql .= "\nWHERE `$id` = $id_value LIMIT 1\n ";

	$result = mysql_query($sql);
	if(!$result){echo(mysql_error()); die(" $sql DOh!");}	
	return $result;
	// change this to running the query...
/*
UPDATE `mm2mm_actfile` SET `actfile_id` = '111',
`actfile_0` = '123211',
`actfile_1` = '2950112311'
`actfile_11` = '111',
`actfile_12` = 'V42.0111',
`actfile_13` = '285.9111' WHERE `actfile_id` =11 LIMIT 1 

*/
}


	function create_table(){

	$id = $this->nick_name."_id";
	$sql = "CREATE TABLE `".$this->table_name."` (\n".
	"`$id` int(10) default NULL ";

	foreach($this->fields as $num => $field_array){
		$name = $field_array['name'];
		if(array_key_exists('type',$field_array)){
			$type = $field_array['type'];
		}else{
			$type = $this->default_type;
		}
		$sql .= ",\n\t  `$name` $type default NULL";
	}

	$sql .= ")";
//	$sql .= ",\n\t PRIMARY KEY  (`$id`)
//	) ENGINE=MyISAM DEFAULT CHARSET=latin1;\n";

	$result = mysql_query($sql);
	if(!$result){echo(mysql_error()); die(" $sql DOh!");}	
	return $result;
	}
}


?>
