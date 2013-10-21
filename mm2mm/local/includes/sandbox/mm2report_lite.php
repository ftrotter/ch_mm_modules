<?php

//	require_once('mysql.php');
//	$result = mysql_query($sql);



	$data_dir = '/var/www/html/php4/ndta_data/';
	$report_dir = '/var/www/html/php4/mm2mm/reports/';
	$output_dir = '/var/www/html/php4/mm2mm/maps/';

if ($handle = opendir($report_dir)) {
    //echo "Directory handle: $handle\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($report_file = readdir($handle))) {
	if(strcmp($report_file,'.')==0||strcmp($report_file,'..')==0){
		//not real files	
	}else{

		$full_report_file_name = $report_dir.$report_file;
		$file_name_array = split("\.",$report_file);
		$file_nick_name = $file_name_array[0];
		
		require_once($full_report_file_name);


		$report_class = $file_nick_name._Report;

		$report = new $report_class();

		$report->run($data_dir);

	}
    }

    closedir($handle);

}

?>
