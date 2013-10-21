<?php
/**
 * This is icd2protocol.php
 *
 * This does .......
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */

require_once('config.php');

mysql_connect($server, $user, $password) or die(mysql_error());
$database = 'demo_unstable';
mysql_select_db($database) or die(mysql_error());


$diabetes = 1;
$depression = 2;
$asthma = 3;
$attention_deficit_disorder = 4;
$child_asthma = 5;
$congestive_heart_failure = 6;
$prevention = 7;
$hypertension = 8;
$obesity = 10;
$otitis_media = 11;
$pharyngitis = 12;
$coronary_artery_disease = 14;
$other = 15;
$lipids = 16;

$terms = array(

	$diabetes => 'Diabetes',
	$depression => 'Depression',
	$asthma => 'Asthma',
	$attention_deficit_disorder => 'Attention',
	$child_asthma => 'Asthma',
	$congestive_heart_failure => 'CHF Congestive',
	$prevention => 'Prevention',
	$hypertension => 'Hypertension',
	$obesity => 'Obesity',
	$otitis_media => 'Otitis',
	$pharyngitis => 'Pharyngitis',
	$coronary_artery_disease => 'CAD',
	$lipids => 'Lipids Cholesterol',

);




$map = array(
//ashtma
"4647" => $asthma,
"4648" => $asthma,
"4649" => $asthma,
"4650" => $asthma,
"4651" => $asthma,
"4652" => $asthma,
"4653" => $asthma,
"4654" => $asthma,
"4655" => $asthma,
"4656" => $asthma,
"4657" => $asthma,
"4658" => $asthma,
"4659" => $asthma,
"4660" => $asthma,

"4281" => $congestive_heart_failure,
"4282" => $congestive_heart_failure,
"4283" => $congestive_heart_failure,
"4284" => $congestive_heart_failure,
"4285" => $congestive_heart_failure,
"4286" => $congestive_heart_failure,
"4287" => $congestive_heart_failure,
"4288" => $congestive_heart_failure,
"4289" => $congestive_heart_failure,
"4290" => $congestive_heart_failure,
"4291" => $congestive_heart_failure,
"4292" => $congestive_heart_failure,
"4293" => $congestive_heart_failure,
"4294" => $congestive_heart_failure,
"4295" => $congestive_heart_failure,




"2229" => $lipids,
"2230" => $lipids,
"2231" => $lipids,
"2232" => $lipids,
"2233" => $lipids,
"2234" => $lipids,
"2235" => $lipids,
"2236" => $lipids,
"2237" => $lipids,
"2238" => $lipids,

"4190" => $coronary_artery_disease,
"4191" => $coronary_artery_disease,
"4192" => $coronary_artery_disease,
"4193" => $coronary_artery_disease,
"4194" => $coronary_artery_disease,
"4195" => $coronary_artery_disease,
"4196" => $coronary_artery_disease,
"4197" => $coronary_artery_disease,
"4198" => $coronary_artery_disease,
"4199" => $coronary_artery_disease,
"4200" => $coronary_artery_disease,
"4201" => $coronary_artery_disease,
"4202" => $coronary_artery_disease,
"4203" => $coronary_artery_disease,
 
"2073" => $diabetes,
"2074" => $diabetes,
"2075" => $diabetes,
"2076" => $diabetes,
"2077" => $diabetes,
"2078" => $diabetes,
"2079" => $diabetes,
"2080" => $diabetes,
"2081" => $diabetes, 
"2082" => $diabetes,
"2083" => $diabetes,
"2084" => $diabetes,
"2085" => $diabetes,
"2086" => $diabetes,
"2087" => $diabetes,
"2088" => $diabetes,
"2089" => $diabetes,
"2090" => $diabetes,
"2091" => $diabetes,
"2092" => $diabetes,
"2093" => $diabetes,
"2094" => $diabetes,
"2095" => $diabetes,
"2096" => $diabetes,
"2097" => $diabetes,
"2098" => $diabetes,
"2099" => $diabetes,
"2100" => $diabetes,
"2101" => $diabetes,
"2102" => $diabetes,
"2103" => $diabetes,
"2104" => $diabetes,
"2105" => $diabetes,
"2106" => $diabetes,
"2107" => $diabetes,
"2108" => $diabetes,
"2109" => $diabetes,
"2110" => $diabetes, 
"2111" => $diabetes,
"2112" => $diabetes,
 

//stopped at depression!!!

"2808" => $depression,
"2519" => $depression,
"2520" => $depression,
"2521" => $depression,
"2522" => $depression,
"2523" => $depression,
"2524" => $depression,
"2525" => $depression,
"2526"=> $depression,
"2527"=> $depression,
"2528"=> $depression,
"2529"=> $depression,
"2530"=> $depression,
"2531"=> $depression,
"2532"=> $depression,
"2600"=> $depression,



"4119" => $hypertension,
"4120" => $hypertension,
"4121" => $hypertension,
"8542" => $hypertension,

//"12953" => $prevention, //removed V72.3 Gnye exam
//"12239" => $prevention,
//"12250" => $prevention,
//"12256" => $prevention,
//"12257" => $prevention,
"12925" => $prevention,
"12451" => $prevention,

"2844" => $attention_deficit_disorder,
"2845" => $attention_deficit_disorder,
"2846" => $attention_deficit_disorder,
"2847" => $attention_deficit_disorder,
"2848" => $attention_deficit_disorder,
"2849" => $attention_deficit_disorder,

"3956" => $otitis_media,
"3957" => $otitis_media,
"3958" => $otitis_media,
"3959" => $otitis_media,
"3960" => $otitis_media,
"3961" => $otitis_media,
"3962" => $otitis_media,
"3963" => $otitis_media,

"4521" => $pharyngitis,
"4528" => $pharyngitis,
"4541" => $pharyngitis,
"4553" => $pharyngitis,
"4554" => $pharyngitis,

"2291" => $obesity,
"2292" => $obesity,
"13023" => $obesity,

);


foreach($map as $id => $protocol_id){
/*
mysql_query("
INSERT INTO `codes_to_protocol` (
`id` ,
`code_id` ,
`protocol_id`
)
VALUES (
NULL , '$id', '$protocol_id'
); ") 
or die(mysql_error());  
*/
$result = mysql_query("SELECT `code_text`
FROM `codes`
WHERE `code_id` = $id");

$result_array = mysql_fetch_array($result);
$current_text = $result_array['code_text'];

$new_text = $terms[$protocol_id] . " " . $current_text;

echo $new_text."\n";

$update_sql = "UPDATE `codes` SET `code_text` = '$new_text ' WHERE codes.code_id = $id ";
mysql_query($update_sql) or die(mysql_error());

}


?>
