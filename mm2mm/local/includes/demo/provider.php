<?php


if(array_key_exists('provider',$_GET)){//Then the form as been submitted

	$provider = $_GET['provider'];
	echo display_matching_providers($provider);

}else{// the form has not been displayed
	
	echo "<h1>You have to send me a provider id in _GET</h1><a href='demo.php'>Main Search</a>";

}


function display_matching_providers($provider_id){


echo "<h1>Provider Drill Down</a>";
	include_once('../mysql.php');
	
//$provider_id = mysql_real_escape_string($provider_id);

	$sql = "
SELECT *
 FROM mm2mm_drfile
WHERE
mm2mm_drfile.provider_number = '$provider_id'	
";


$result = mysql_query($sql) or die('Query failed: ' . mysql_error());



	if(!($first_line = mysql_fetch_array($result, MYSQL_ASSOC))) {
	echo "<h3>no providers match... wierd...</h3>";
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
