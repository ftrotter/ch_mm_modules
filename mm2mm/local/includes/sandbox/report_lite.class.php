<?php


require_once('parser.class.php');
//This is the base class that provides basic file parsing, SQL saving
// and other generic goodies that every map is going to need...
class MedManReportsLite { //Abstract...

var $parser = NULL;
var $map == NULL;


function run($data_file_dir){

	$full_data_file = $data_file_dir.$this->file_name;

	require_once($map_file);
	$this->map = new $map_class;

	echo "running report on $full_data_file\n";

	$report = '';

	while($record_array = $this->parse_file($full_data_file)){

		foreach($record_array as $key => $record){

			if($this->report($record)){	
				$reported++;
			}else{
				echo "failed insert.  ";
			}
		}
		echo "reported $reported rows\n";
	}


}


function report($record){

	return true;
}

function parse_file($file,$rows_to_get = 100000){

	echo "parseing $file";

	if($this->parser == NULL){	
		$my_parser = new MedManParser($file);
		$this->parser = $my_parser;
	}


	if($this->map == NULL){	
		$my_map = new MedManParser($file);
		$this->parser = $my_parser;
	}



	$rows_of_data = $this->parser->get($rows_to_get);

	if(!$rows_of_data) {return(false);}

	foreach($rows_of_data as $data){

		//this statment should be based on a per-file
		if(!$this->test_line($data)){
			$pos_records++;
			break;	
		}
	
		if($valid_results = $map->map($data)){
			if($this->match($valid_results)){
					echo ".";
					$matched_data[]=$valid_results;
			}
		}
	}

	return($matched_data);

}


function match($line){

	return true;

}

function test_line($line){

	$valid =true;

	if($line[0] > -1){

		$valid = false;

	}

return($valid);

}







?>
