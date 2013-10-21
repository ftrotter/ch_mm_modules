<?php

// A class that reads a GTM .zwr text file, and can intelligently translate the data stored there'
// into php arrays. 
// This might not properly support the ability for MUMPS globals to have both a pointer to another array AND a value.
// But then I did not see that in my test data... best of luck...
// Author: Fred Trotter - fred.trotter@gmail.com
// License: GPLv3 or later

/**
 * This is FileManRow.class.php
 *
 * This does .......
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */
/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class FileManRow{

	public $FMGlobal = '';
	public $FMFile = '';	
	private $file_handle;
	private $lastline = '';
	private $debug = false;
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* 
 	* @return boolean
        */
	function FileManRow($FMGlobal, $FMFile){

		$best_file = $FMGlobal.".zwr.".$FMFile;
		$good_file = $FMGlobal.".zwr";

	//	echo $best_file;
		if(file_exists($best_file)){
			$handle = @fopen($best_file, "r");
		}else{
			$handle = @fopen($good_file, "r");
		}
		if ($handle) {
			$this->FMGlobal = $FMGlobal;
			$this->FMFile = $FMFile;
			$this->file_handle = $handle;
			return(true);
		}else{
			return(false);
		}
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return array
        */
	function get_next(){

		if($this->debug){ echo "\nSTARTING get_next \n  ";}
		

		$my_VAFile = '';
		$current_record = -1;
		$current_array = array();
			if($this->debug){
				if(feof($this->file_handle)){
					echo "end of file\n";
				}
			}

		while (!feof($this->file_handle)) {


			if(strlen($this->lastline)>0){
				$line=$this->lastline;
				if($this->debug){ echo "using this- >lastline  ";}
				$this->lastline = '';
			}else{
				if($this->debug){ echo "getting a new line  ";}
        			$line = fgets($this->file_handle, 4096);
			}
			if($this->debug){echo "my line = $line";}
			if(strlen($line)>0 && strstr($line,"=")){ //ignore the non-data lines

				list( $left, $right) = split('=',$line);
				//left should look like 
				// ^PS(56,10,0)
				//right should be like
				//"AMINOPHYLLINE/CIPROFLOXACIN^653^1919^1^1^16173"
				$open_paren_pos = strpos($left,'('); 
				$close_paren_pos = strpos($left,')'); 
				$VAglobal = substr($left,1,$open_paren_pos - 1);
			//	echo "for $left \n";
			//	echo "open at $open_paren_pos, close at $close_paren_pos VAFile = $VAglobal\n";
				//VAfile should grab the "PS" from above
				//or more for larger file names
				$position_string = substr($left,$open_paren_pos +1 , ($close_paren_pos - $open_paren_pos) -1);
		
			//	echo $count . ": " . $position_string . "\n";
				$position_string = str_replace('"','',$position_string);
				$location_array = split(',',$position_string);
				$data_string = chop(str_replace('"','',$right));
	
				if(count($location_array)<3){
					//this is not a data row.
					if($this->debug){ echo "under three items, not a data row \n";}
					continue;
				}

				$my_VAfile = $location_array[0];
				$row_id = $location_array[1];


				

				$item_id = $location_array[2];	
				if($my_VAfile == $this->FMFile){
				// then this is what we need to pay attention to

					if($this->debug){ echo "\n\n WORKING\n";}
					if($current_record == -1){
						//then this is first look
						$current_record = $row_id;
						if($this->debug){ echo "first look at the row id $row_id\n";}
					
					}elseif($current_record != $row_id){
						//then we have changed records. save this line and return
						$this->lastline = $line;
						if($this->debug){ echo "RETURNING last line and returning result\n";}
						return($current_array);
					}

					if(!is_numeric($row_id)){
						//for the time being... lets ignore the 
						//index rows....
						// important that this comes after
						// the logic to detect the last portion of an array...
						// this will always return, only after the last array 
						// has returned in the loop above...
						return(false);
					}

					$sub_array = $location_array;
					array_shift($sub_array);

					if($this->debug){ 
						echo "SUB_ARRAY starts as \n";
						var_export($sub_array);
						echo "\n";
					}

	
					if(strstr($data_string,"^")){
						$data = split("\^",$data_string);
					}else{
						$data = $data_string;
					}

					$current_array = $this->recurse_struct($current_array,$sub_array,$data);
				}else{ 
					if($this->debug){ echo "\n\n SKIPPING\n";}

				}
			}


		}//while

		// if we have reached here then we are at the end of the file
		if($this->debug){ echo "EOF returning last current_array\n";}
		return($current_array);
	}//function get_next

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param array 
 	* @param array
 	* @param string
 	* 
 	* @return array
        */
	function recurse_struct($current_array, $key_array,$data){
	//Where the magic happens....
	//in mumps you can define things by just saying that they exist
	// in php if is a little harder
	// although another way to do this would be to have made a string and evaled it
	// which feels naughty but might have been much simpler. 
		$return_array = array();			

		$left_element = array_shift($key_array);

		if($this->debug){ 
		if(is_array($current_array)){
			echo "current_array = \n";
		//	var_export($current_array);
			echo "\n";
		}

		if(is_array($key_array)){
			echo "key_array = \n";
			var_export($key_array);
			echo "\n";
		}

			echo "look at left element $left_element\n";
			echo "data_array = \n";
			var_export($data);
			echo "\n";

		}

		if(!is_array($current_array)){
			$current_array = array();
		}

		if(array_key_exists($left_element,$current_array)){
			if($this->debug){ echo "left_element already in array\n";}
			$next_level = $current_array[$left_element];
			if(is_array($next_level)){
				//we have been here before
			}else{
				echo "how did we get here?? $next_level";
				return(false);
			}
		}else{
			if($this->debug){ echo "left_element not in array\n";}
			$current_array[$left_element] = $data;
			$next_level = array(); // to avoid warnings
		}
		if(count($key_array)>0){	
			if($this->debug){ echo "RECURSING\n";}
			$current_array[$left_element] = $this->recurse_struct($next_level,$key_array,$data);
		}

		if($this->debug){ echo "RETURNING\n";}
		return($current_array);


	}


}//class FileManRow





?>
