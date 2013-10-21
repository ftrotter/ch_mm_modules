<?php

$config['ftp_server'] = '192.168.1.111';
$config['ftp_user_name'] = 'root';
$config['ftp_user_pass'] = 'azrael';
$config['mm_data_dir'] = '/usr2/meddata/'; //include the trailing slash
$config['local_data_dir'] = './'; //include the trailing slash

$files_to_sync = array (

	'patfile.dat',
	'insfile.dat',
);

foreach($files_to_sync as $file){

	mm_sync($file,$config);

}

function mm_sync($file_name,$config){

$local_timestamp_file = $file_name.'.time.php';
$local_file = $config['local_data_dir'].$file_name;
$server_file = $config['mm_data_dir'].$file_name;
$have_status = false;
if(file_exists($local_timestamp_file)){
	include_once($local_timestamp_file);
	$have_status = true;
}

// set up basic connection
$conn_id = ftp_connect($config['ftp_server']);

// login with username and password
$login_result = ftp_login($conn_id, $config['ftp_user_name'], $config['ftp_user_pass']);

// check connection
if ((!$conn_id) || (!$login_result)) {
        echo "FTP connection has failed!\n";
        echo "Attempted to connect to ". $config['ftp_server']." for user ".$config['ftp_user_name']."\n";
        exit;
    } else {
        echo "Opened FTP connection for $file_name\n";
    }


$result_array = ftp_rawlist($conn_id,$server_file);
$result_string = $result_array[0];

     $current = preg_split("/[\s]+/",$result_string,9);
           
       //     $struc['perms']    = $current[0];
       //     $struc['number']= $current[1];
       //     $struc['owner']    = $current[2];
        //    $struc['group']    = $current[3];
            $struc['month']    = $current[5];
            $struc['day']    = $current[6];
            $struc['time']    = $current[7];
      //      $struc['name']    = str_replace('//','',$current[8]);
            //$struc['raw']    = $folder;


$changed = false;

if($have_status){// there is something to compare too
	foreach($struc as $key => $val){
		if(strcmp($val,$current_status[$key])==0){//this field did not change
			//do nothing 
		}else{
			$changed = true;
			echo "timestamp change $key $val ... ";
		}
	}	
}else{
	$changed = true; //nothing to compare to... assume this is the initial download
	echo "no timestamp file to compare to... ";
}

if($changed){

	echo "downloading $file_name\n";
	if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
    		echo "Successfully written $server_file to $local_file\n";
	} else {
	    echo "There was a problem getting $server_file\n";
	}	


}else{
	echo "no change detected detected for $file_name\n";

}

	$fh = fopen($local_timestamp_file,'w');
	$status_string = "<?php \n".'$current_status = '.var_export($struc,true)."\n?>";
	fwrite($fh,$status_string);

ftp_close($conn_id);
}

?> 
