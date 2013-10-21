<?php

// given a file name, and place to start and stop (for memory preservation)
// This will return a multidmensional labeled array for offnotes.dme

// it appears that the text of a single note is spread between several 
// lines in this file. It also appears that many types of comments are saved here. 

if(file_exists('map.class.php')){
        require_once("map.class.php"); 
}elseif(file_exists('../map.class.php')){

        require_once('../map.class.php');
}// if neither of these work, lets hope its already loaded...




class offnotes_Map extends MedManMap{
	
	var $nick_name = "offnotes";
	var $file_name = "offnotes.dme";
	var $table_name = "mm2mm_offnotes";
	var $id_field = "offnotes_id";
	var $default_type = "varchar(100)";

	var $fields = array (
  0 => 
  array (
    'name' => 'user',
    'type' => 'int(20)',
  ),
  1 => 
  array (
    'name' => 'offnotes_id',
    'type' => 'int(20)',
  ),
  2 => 
  array (
    'name' => 'parent_offnotes_id',
    'type' => 'int(20)',
  ),
  3 => 
  array (
    'name' => 'child_offnotes_id',
    'type' => 'int(20)',
  ),
  4 => 
  array (
    'name' => 'notes_system',
    'type' => 'varchar(5)',
  ),
  5 => 
  array (
    'name' => 'patient_and_type',
    'type' => 'varchar(50)',
  ),
  6 => 
  array (
    'name' => 'note_num',
    'type' => 'varchar(50)',
  ),
  7 => 
  array (
    'name' => 'note',
    'type' => 'varchar(100)',
  ),
  8 => 
  array (
    'name' => 'flags',
    'type' => 'varchar(10)',
  ),
  9 => 
  array (
    'name' => 'other_user_id',
    'type' => 'int(10)',
  ),
  10 => 
  array (
    'name' => 'extra_alpha_numeric',
    'type' => 'varchar(10)',
  ),

);

	var $get_all = false;
	var $get_filter = 'ehr:';

	var $calculated_fields = array (
  11 => 
  array (
    'name' => 'patient_id',
    'type' => 'int(20)',
  ),
  12 => 
  array (
    'name' => 'note_type',
    'type' => 'varchar(20)',
  ),
);

	function parse_file($file, $rows_to_get = 50000){ 
			// this function will break if the whole file cannot be parsed at once.... :)
			// we need to make the parser aware of this problem somehow.
			// of course, we may never have this problem in production...
			// at least we can test for the condition here.
			// the problem is if we split a multi-line record, when we arbiratiry stop getting lines...

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

		//echo ".";
	
		if($valid_results = $this->map($data)){
//				$parsed_data[]=$valid_results;
			$my_id = $valid_results['offnotes_id'];
			$my_local_parent = $valid_results['parent_offnotes_id'];

			if($my_local_parent == 0){ //I have no parent...
			//I have to assume that I might have kids (I suppose I could check...)
			// so I have to create a "parent" array for myself
			// which makes space for my "child" records, under the same array...	
				$mergeable_data[$my_id][$my_id] = $valid_results;
			}else{
			//so I have a parent, which may or may not already have a record in my mulit-array
			//so I will operate in such a fashion that I will make the space
			// and the future parent will sort itself...
				if(isset($parents[$my_local_parent])){
					$my_ultimate_parent = $parents[$my_local_parent];
					$mergeable_data[$my_ultimate_parent][$my_id] = $valid_results;
					$parents[$my_id] = $my_ultimate_parent;
				}else{
					$parents[$my_id] = $my_local_parent;
					$mergeable_data[$my_local_parent][$my_id] = $valid_results;
				}

			}// if I have a parent


		}// if valid results
	
		
	}// end my foreach

	// now I have my mergeable array...

	$merged_data = array();

	foreach($mergeable_data as $parent_id => $children){

		$parent_data = array();
		$merged_note = '';
		foreach($children as $child_id => $data){
			if($parent_id == $child_id){
				$parent_data = $data;
				// append this later
				$last_line = preg_replace('/[^(\x20-\x7F)]*/','', $data['note']) . "\n";
			}else{
				$merged_note .= preg_replace('/[^(\x20-\x7F)]*/','', $data['note']) . "\n";
			}
			
		}
		$merged_note .= $last_line;
		$to_import = false;
		if($this->get_all == false){// this means that we should only import IF there is a marking string 
						//in the comment...

			if(strpos($merged_note,$this->get_filter) === false){
				//then the string is not found...
				//we skip this record...
			}else{
				//then we import this record!!!
				$to_import = true;

			}

		}else{ // we import eveyrthing!!
			$to_import = true;
		}

		if($to_import){

			$merged_data[$parent_id] = $parent_data; //use the parent record for everything...
			$merged_data[$parent_id]['note'] = $merged_note; //except the note which is merged above...
			list($nothing_here, $patient_id, $note_source) = split(' ',$merged_data[$parent_id]['patient_and_type']);
			$merged_data[$parent_id]['patient_id'] = $patient_id;	
			$merged_data[$parent_id]['note_source'] = $note_source;	
		}
	} 

	return($merged_data);

	}


}
?>
