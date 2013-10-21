<?php
/**
 * This is add_icd_descriptions.php
 *
 * This does .......
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */
mysql_connect("localhost", "root", "password") or die(mysql_error());
mysql_select_db("ydp") or die(mysql_error());

$count = 0;
$i = 0;
$handle = fopen("icd_descriptions.csv", "r");
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
	$code = $data[0];
	$text = mysql_real_escape_string($data[1]);
	$count++;
	$i++;

	$sql = "
UPDATE `ydp`.`codes` SET `code_text_short` = '$text' 
WHERE `codes`.`code` = '$code' AND `codes`.`code_type` = 2 LIMIT 1;
";

//echo $sql . "\n";	
$result = mysql_query($sql) or die(mysql_error());

	if($i > 100){
		echo " $count\n";
		$i = 0;

	}

}
fclose($handle);




?>
