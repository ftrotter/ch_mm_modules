<?php

$random_name_file_name = '10000_fake_names.csv';
$patfile_file_name = '/var/www/html/php4/ndta_data/patfile.dat';
$clean_patfile_file_name = '/var/www/html/php4/ndta_data/patfile.dat.clean';
$aptfile_file_name = '/var/www/html/php4/ndta_data/aptfile.dat';
$clean_aptfile_file_name = '/var/www/html/php4/ndta_data/aptfile.dat.clean';
$drfile_file_name = '/var/www/html/php4/ndta_data/drfile.dat';
$clean_drfile_file_name = '/var/www/html/php4/ndta_data/drfile.dat.clean';


$clean_patfile = fopen($clean_patfile_file_name,'w');
$old_patfile = fopen($patfile_file_name,'r');
$clean_aptfile = fopen($clean_aptfile_file_name,'w');
$old_aptfile = fopen($aptfile_file_name,'r');
$clean_drfile = fopen($clean_drfile_file_name,'w');
$old_drfile = fopen($drfile_file_name,'r');



$random_names = fopen($random_name_file_name,'r');

$first_line_array = fgetcsv($old_patfile,1000,",");//discard first line
fputcsv($clean_patfile,$first_line_array);

$random_name_fieldnames = fgetcsv($random_names,1000,",");


while(($data = fgetcsv($old_patfile,1000,",")) !== FALSE){

	if($data[0] > -1){continue;}// ignore deleted lines!!

	if(($random_data = fgetcsv($random_names,1000,",")) == FALSE){
		//then we are out of random data...
		//loop through the file again...
		fclose($random_names);
		$random_names = fopen($random_name_file_name,'r');
		$random_name_fieldnames = fgetcsv($random_names,1000,",");
	}

	$data = patfile_swap($data,$random_data);
	
	fputcsv($clean_patfile,$data);


}//while

$first_line_array = fgetcsv($old_aptfile,1000,",");//discard first line
fputcsv($clean_aptfile,$first_line_array);

while(($data = fgetcsv($old_aptfile,1000,",")) !== FALSE){

	if($data[0] > -1){continue;}// ignore deleted lines!!

	if(($random_data = fgetcsv($random_names,1000,",")) == FALSE){
		//then we are out of random data...
		//loop through the file again...
		fclose($random_names);
		$random_names = fopen($random_name_file_name,'r');
		$random_name_fieldnames = fgetcsv($random_names,1000,",");
	}

	$data = aptfile_swap($data,$random_data);
	
	fputcsv($clean_aptfile,$data);


}//while


$first_line_array = fgetcsv($old_drfile,1000,",");//discard first line
fputcsv($clean_drfile,$first_line_array);

while(($data = fgetcsv($old_drfile,1000,",")) !== FALSE){

	if($data[0] > -1){continue;}// ignore deleted lines!!

	if(($random_data = fgetcsv($random_names,1000,",")) == FALSE){
		//then we are out of random data...
		//loop through the file again...
		fclose($random_names);
		$random_names = fopen($random_name_file_name,'r');
		$random_name_fieldnames = fgetcsv($random_names,1000,",");
	}

	$data = drfile_swap($data,$random_data);
	
	fputcsv($clean_drfile,$data);

}//while

function drfile_swap($data,$random_data){

	//from the aptfile map
	//$data[2] = provider_last_name
	//$data[3] = provider_first_name
	//$data[4] = provider_mi
	//$data[5] = provider address
	//$data[10] = provider phone
	//$random_data[1] = fname
	//$random_data[3] = lname
	//$random_data[2] = mi
	echo "dr replace ";
	$new_data[0] = $data[0];
	echo $new_data[0]." ".$data[0]." ";
	$new_data[1] = $data[1];
	echo $new_data[1]." ".$data[1]." ";
	$new_data[2] = strtoupper($random_data[3]);
	echo $new_data[2]." ".strtoupper($random_data[3])." ";
	$new_data[3] = strtoupper($random_data[1]);
	echo $new_data[3]." ".strtoupper($random_data[1])." ";
	$new_data[4] = strtoupper($random_data[2]);
	echo $new_data[4]." ".strtoupper($random_data[2])." ";
	$new_data[5] = strtoupper($random_data[4]);
	echo $new_data[5]." ".strtoupper($random_data[4])." ";
	$new_data[6] = $data[6];
	echo $new_data[6]." ".$data[6]." ";
	$new_data[7] = $data[7];
	echo $new_data[7]." ".$data[7]." ";
	$new_data[8] = $data[8];
	echo $new_data[8]." ".$data[8]." ";
	$new_data[9] = 0;
	echo "erasing ".$new_data[9]." ";
	$new_data[10] = implode(split('-',$random_data[10]));
	echo $new_data[10]." ".implode(split('-',$random_data[10]))." ";
	echo "\n";
	return($new_data);

}


function aptfile_swap($data,$random_data){

	//from the aptfile map
	//$data[10] = special_phone_number
	//$random_data[10] = phone
	
	$phone = implode(split('-',$random_data[10]));
	echo "apt replace ";
	echo $data[10]." ".$phone." ";
	$data[10] = $phone;
	
	$comments = "appointment comments de-identified";
	echo $data[14]." ".$comments." ";
	$data[14] = $comments;
	echo "\n";
	return($data);

}




function patfile_swap($data,$random_data){

	//from the patfile map we know...
	//$data[16] = fname
	//$data[17] = lname
	//$data[18] = mi
	//$data[29] = socsec
	//$data[24] = phone
	//$data[25] = work_phone
	//$data[19] = address

	//$random_data[1] = fname
	//$random_data[3] = lname
	//$random_data[2] = mi
	//$random_data[18] = socsec
	//$random_data[10] = phone
	//$random_data[10] = work_phone
	//$random_data[4] = address

	//so 

	echo "pat replace ";
	$last_name = strtoupper($random_data[1]);
	echo " ".$data[16]." ".$last_name;
	$data[16] = $last_name;

	$first_name = strtoupper($random_data[3]);
	echo " ".$data[17]." ".$first_name;
	$data[17] = $first_name;

	$mi = strtoupper($random_data[2]);
	echo " ".$data[18]." ".$mi;
	$data[18] = $mi;

	$soc_sec = implode(split('-',$random_data[18]));
	echo " ".$data[29]." ".$soc_sec;
	$data[29] = $soc_sec;

	$phone = implode(split('-',$random_data[10]));
	echo " ".$data[24]." ".$phone;
	$data[24] = $phone;

	echo " ".$data[25]." ".$phone;
	$data[25] = $phone;

	$address = strtoupper($random_data[4]);
	echo " ".$data[19]." ".$address;
	$data[19] = $address;

	echo "\n";

	return($data);

}



?>
