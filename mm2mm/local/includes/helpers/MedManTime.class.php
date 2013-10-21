<?php

class MedManTime { //Abstract...

static function medman_fields2timestamp($mm_date_string,$mm_time_string = 0){// works for aptfile...

	$year = substr($mm_date_string,0,4);
	$month = substr($mm_date_string,4,2);
	$day = substr($mm_date_string,6,2);
	
//	echo "year $year month $month day $day\n";	

	if($mm_time_string == 0){
		$split_time = 5; // the hour that p.m. shifts to a.m. in our world
		
		if(strcmp(substr($mm_time_string,0,1)," ")==0){ 
			//less than 10:00 
			$hour = (int) substr($mm_time_string,1,1);//one digit time
		}else{
			$hour = (int) substr($mm_time_string,0,2);//two digit time
		}
	
		$minute = (int) substr($mm_time_string,3,2);
	
		if(!($hour > $split_time)){//p.m
			$hour = $hour + 12;
		}// now $hour should be in twenty four hour format...
	}else{
		$minute = 1;
		$hour = 0;
		$year = 0;
	}

	$my_stamp = mktime($hour,$minute,0,$month,$day,$year);// no minute, month, day, year

//	echo "hour $hour minute $minute \n";
	return($my_stamp);

}//medmanstring2timestamp

}

?>
