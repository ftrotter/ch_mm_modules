<?php

	require_once('mysql.php');
//	$result = mysql_query($sql);



	$dir = '/var/www/html/php4/ndta_data/';
	$output_dir = '/var/www/html/php4/mm2mm/maps/';

if ($handle = opendir($dir)) {
    //echo "Directory handle: $handle\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($data_file = readdir($handle))) {
	if(strcmp($data_file,'.')==0||strcmp($data_file,'..')==0){
		//not real files	
	}else{
		$file_name_array = split("\.",$data_file);
		$file_nick_name = $file_name_array[0];
		$map_file = $output_dir.$file_nick_name.".map.php";
		$data_file = $dir.$data_file;
		//echo "working on $data_file \n";
		//echo "$map_file $data_file $file_nick_name";
       		create_database($map_file,$data_file,$file_nick_name);
		echo "Database $file_nick_name created\n";	
       		populate_database($map_file,$data_file,$file_nick_name);
		echo "Database $file_nick_name populated\n";	
	}
    }

    closedir($handle);

}

function create_database($map_file,$data_file,$nick_name){

	require_once($map_file);

	$map_class = $nick_name._Map;

	$map = new $map_class();

	$result = $map->create_table();
}

function populate_database($map_file,$data_file,$nick_name){

	require_once($map_file);

	$map_class = $nick_name._Map;

	$map = new $map_class();
	$record_array = array(1 => 1);
	$marker = 0;
	$batch_size = 5000;	
	$inserted = 0;
	while($record_array = $map->parse_file($data_file,$batch_size)){

		foreach($record_array as $key => $record){
			if($map->insert_array($record)){
			//	echo ".";
			$inserted++;
			}else{
				echo "failed insert.  ";
			}
		}
		echo "inserted $inserted rows in $nick_name table\n";
	}

}


?>
