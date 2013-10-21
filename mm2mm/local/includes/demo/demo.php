<?php


if(array_key_exists('first_name',$_POST)||
	array_key_exists('first_name',$_POST)){//Then the form as been submitted

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	echo display_matching_patients($first_name,$last_name);

}else{// the form has not been displayed
	
	echo display_patient_search_form();

}


function display_matching_patients($first_name,$last_name){


	include_once('../mysql.php');
	
$first_name = strtoupper(mysql_real_escape_string($first_name));
$last_name = strtoupper(mysql_real_escape_string($last_name));

	$sql = "
SELECT 
mm2mm_patfile.account_number,
mm2mm_patfile.social_security_number,
mm2mm_patfile.first_name,
mm2mm_patfile.last_name,
mm2mm_patfile.date_last_modified,
mm2mm_patfile.doctor_number,
mm2mm_patfile.date_of_birth,
mm2mm_patfile.address,
mm2mm_patfile.state,
mm2mm_patfile.city,
mm2mm_patfile.zip,
mm2mm_patfile.phone,
mm2mm_patfile.sex,
mm2mm_patfile.employer_name,
mm2mm_drfile.provider_first_name,
mm2mm_drfile.provider_last_name,
mm2mm_drfile.provider_number,
mm2mm_aptfile.appointment_date
FROM mm2mm_patfile
JOIN mm2mm_drfile ON mm2mm_patfile.doctor_number = mm2mm_drfile.provider_number
LEFT JOIN mm2mm_aptfile ON mm2mm_patfile.account_number = mm2mm_aptfile.account_number
WHERE
(mm2mm_patfile.first_name = '$first_name'  OR 
mm2mm_patfile.last_name = '$last_name' )

";


$result = mysql_query($sql) or die('Query failed: ' . mysql_error());

/*
	if(count($all_matches) < 1){
		echo "No matching patients<br>";
		exit();
	}
*/


	if(!($first_line = mysql_fetch_array($result, MYSQL_ASSOC))) {
	echo "<h3>no patients match</h3>";
	echo display_patient_search_form();
	exit();
	}

	echo "<table border=1>\n";
		echo display_patient_header($first_line);
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo display_patient_line($line);
	}
	echo "</table>\n";

echo "<h3>The SQL that generated this table</h3><br>".nl2br($sql);

}

function display_patient_header($record){

$to_return = "<tr>\n";

		$to_return .= "<td><b>account_number</b></td>\n";
		$to_return .= "<td><b>appointments</b></td>\n";
		$to_return .= "<td><b>social_security</b></td>\n";
		$to_return .= "<td><b>last_name</b></td>\n";
		$to_return .= "<td><b>first_name</b></td>\n";
		$to_return .= "<td><b>last_modified</b></td>\n";
		$to_return .= "<td><b>doctor</b></td>\n";
		$to_return .= "<td><b>date_of_birth</b></td>\n";
		$to_return .= "<td><b>address</b></td>\n";
		$to_return .= "<td><b>city</b></td>\n";
		$to_return .= "<td><b>state</b></td>\n";
		$to_return .= "<td><b>zip</b></td>\n";
		$to_return .= "<td><b>phone</b></td>\n";
		$to_return .= "<td><b>gender</b></td>\n";
		$to_return .= "<td><b>employer</b></td>\n";

 
$to_return .= "</tr>\n";

return $to_return;
}


function display_patient_line($record){

$to_return = "<tr>\n";

		$to_return .= "<td>".$record['account_number']."</td>\n";
		if($record['appointment_date']>0){
			$to_return .= "<td><a href='appointments.php?patient=".$record['account_number'].
						"'>has app</a></td>\n";
		}else{
	
			$to_return .= "<td>no app</td>\n";
		}
		$to_return .= "<td>".$record['social_security_number']."</td>\n";
		$to_return .= "<td>".$record['last_name']."</td>\n";
		$to_return .= "<td>".$record['first_name']."</td>\n";
		$to_return .= "<td>".$record['date_last_modified']."</td>\n";

		$to_return .= "<td><a href='provider.php?provider=".$record['doctor_number']."'>".
		$record['provider_last_name']." ".$record['provider_first_name']."</a></td>\n";

		$to_return .= "<td>".$record['date_of_birth']."</td>\n";
		$to_return .= "<td>".$record['address']."</td>\n";
		$to_return .= "<td>".$record['city']."</td>\n";
		$to_return .= "<td>".$record['state']."</td>\n";
		$to_return .= "<td>".$record['zip']."</td>\n";
		$to_return .= "<td>".$record['phone']."</td>\n";
		$to_return .= "<td>".$record['sex']."</td>\n";
		$to_return .= "<td>".$record['employer_name']."</td>\n";
 
$to_return .= "</tr>\n";

return $to_return;
}

function display_patient_search_form(){


	$html_form = "
	<br><br><br><br>
	<form method='post' action=''>
	<table align='center'><tr><td>
	Enter First Name:</td><td><input type='text' name='first_name'></td>
	</tr><tr>
	<td>Enter Last Name:</td><td><input type='text' name='last_name'></td>
	</tr><tr>
	<td></td><td><input type='submit' name='submit' value='Search'></td>
	</td></tr></table>
	</form>";

return $html_form;
}




?>
