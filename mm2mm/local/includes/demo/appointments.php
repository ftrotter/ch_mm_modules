<?php


if(array_key_exists('patient',$_GET)){//Then the form as been submitted

	$patient = $_GET['patient'];
	echo display_matching_apps($patient);

}else{// the form has not been displayed
	
	echo "<h1>You have to send me a patient id in _GET</h1><a href='demo.php'>Main Search</a>";

}


function display_matching_apps($patient_id){


echo "<h1>Appointment Drill Down</a>";
	include_once('../mysql.php');
	

	$sql = "
SELECT *
 FROM mm2mm_aptfile
WHERE
mm2mm_aptfile.account_number = '$patient_id'	
";


$result = mysql_query($sql) or die('Query failed: ' . mysql_error());



	if(!($first_line = mysql_fetch_array($result, MYSQL_ASSOC))) {
	echo "<h3>no appointments</h3>";
	exit();
	}

	echo "<table border=1>\n";
		echo display_header($first_line);
		echo display_line($first_line);
	echo "</table>\n";

echo "<h3>The SQL that generated this table</h3><br>$sql";

}

function display_header($record){

$to_return = "<tr>\n";

		foreach($record as $name => $value){

			$to_return .= "<td><b>$name</b></td>";
		}

 
$to_return .= "</tr>\n";

return $to_return;
}


function display_line($record){

$to_return = "<tr>\n";


		foreach($record as $name => $value){

			$to_return .= "<td>$value</td>";
		}


 
$to_return .= "</tr>\n";

return $to_return;
}


?>
