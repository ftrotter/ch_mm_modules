<?php

//woot!
/**
 * This is va_drug_loader.php
 *
 * This does .......
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */
/**
 * @see 'config.php'
 */
require_once('config.php');

/**
 * @see 'FileManRow.class.php'
 */
require_once('FileManRow.class.php');
// includes username, password, host etc etc

$database = 'ydp';
$count = 0;
$quit_count = 0;

mysql_connect($server, $user, $password) or die(mysql_error());
mysql_select_db($database) or die(mysql_error());





$myIngredientFile = new FileManRow('PS','50.416');// ingredient file
$myProductFile = new FileManRow('PSNDF','50.68');// product file
//$myGenericsFile = new FileManRow('PSNDF','50.6');// generic file
$myTradeNameFile = new FileManRow('PSNDF','50.67');// trade name file
$myInteractionsFile = new FileManRow('PS','56');// interaction file


$myInteractionsFile = new FileManRow('PS','56');// interaction file

echo "Adding Interactions \n";
$count = 0;
while($this_array = $myInteractionsFile->get_next()){

	$count++;if($count%100==0){echo '.';} //prints the little progress dots...

	$sql = map_PS_56($this_array);
	mysql_query($sql) or die(mysql_error());
}
echo "\n";



echo "Adding Tradenames \n";
$count = 0;
while($this_array = $myTradeNameFile->get_next()){
//for($i=0;$i<10;$i++){
//	$this_array = $myTradeNameFile->get_next();

	$count++;if($count%100==0){echo ".";} //prints the little progress dots...

	$sql = map_PSNDF_5067($this_array);
//	echo $sql ."\n";
}
echo "\n";



echo "Adding Products \n";
$count = 0;
while($this_array = $myProductFile->get_next()){
//for($i=0;$i<10;$i++){
//	$this_array = $myTradeNameFile->get_next();

	$count++;if($count%100==0){echo ".";} //prints the little progress dots...

	$sql = map_PSNDF_5068($this_array);
//	echo $sql ."\n";
}
echo "\n";



echo "Adding Ingredients \n";
$count = 0;
while($this_array = $myIngredientFile->get_next()){
//for($i=0;$i<1;$i++){
//	$this_array = $myIngredientFile->get_next();

	$count++;if($count%100==0){echo ".";} //prints the little progress dots...

//	print_r($this_array);


	$sql = map_PS_50416($this_array);
//	echo $sql ."\n";
}
echo "\n";




	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param array
 	* 
 	* @return array
        */
function map_PS_50416($data_array){

$sql = '';

foreach($data_array as $id => $zero_array){
	
	$name_array = $zero_array[0];

	$name = mysql_real_escape_string($name_array[0]);

	

		$sql = "
INSERT INTO `va_ingredients` (
`id` ,
`name`
)
VALUES (
'$id', '$name'
);";

mysql_query($sql) or die("problem saving ingredients :".mysql_error());

	if(array_key_exists(1,$zero_array)){
	$product_array = $zero_array[1];
	$throw_away_first_record = array_shift($product_array);

	foreach($product_array as $key => $data_array){
		if(is_numeric($key)){
			$link_string = array_pop($data_array);
			list( $generic_id, $product_id) = split('A',$link_string);
			
			$unique = $id . $key; 

			$sql = "
INSERT INTO `va_ingredients_to_products` (
`id` ,
`ingredient_id` ,
`product_id`
)
VALUES (
'$unique', '$id', '$product_id'
);";
mysql_query($sql) or die("problem saving ingredients_to_products :".mysql_error());

		}
	}
	}//if array_key_exists


	
}//foreach
mysql_query($sql) or die("problem saving ingredients :".mysql_error());
return($sql);

}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param array
 	* 
 	* @return array
        */
function map_PSNDF_5068($data_array){

$sql = '';

foreach($data_array as $id => $zero_array){
	
	$my_array = $zero_array[0];

	$name = mysql_real_escape_string($my_array[0]);

	$check_sql = "
SELECT *
FROM `va_productname`
WHERE `name` = '$name'
"; 
	$result = mysql_query($check_sql) or die("Problems checking productnames" .mysql_error());
	if($row = mysql_fetch_array($result)){
	// then we add to the va_tradenames_extra_id table
	$first_id = $row['id'];
//	echo "already added $name at $first_id\n";

	$sql .= "
INSERT INTO `va_productname_extra_ids` (
`id` ,
`name_id`
)
VALUES (
'$id', '$first_id'
);";

	}else{	

		$sql .= "
 INSERT INTO `va_productname` (
`id` ,
`name`
)
VALUES (
'$id', '$name'
);";


	}
}
mysql_query($sql) or die("problem saving productnames :".mysql_error());
return($sql);

}





	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param array
 	* 
 	* @return array
        */
function map_PSNDF_5067($data_array){

$sql = '';

foreach($data_array as $id => $zero_array){
	
	$my_array = $zero_array[0];

	$name = mysql_real_escape_string($my_array[4]);
	$product_id = $my_array[5];

	$check_sql = "
SELECT *
FROM `va_tradenames`
WHERE `name` = '$name'
"; 
	$result = mysql_query($check_sql) or die("Problems checking tradenames" .mysql_error());
	if($row = mysql_fetch_array($result)){
	// then we add to the va_tradenames_extra_id table
	$first_id = $row['id'];
//	echo "already added $name at $first_id\n";

	$sql .= "
INSERT INTO `va_tradenames_extra_ids` (
`id` ,
`product_id` ,
`name_id`
)
VALUES (
'$id', '$product_id', '$first_id'
);";

	}else{	

		$sql .= "
 INSERT INTO `va_tradenames` (
`id` ,
`product_id` ,
`name`
)
VALUES (
'$id',  '$product_id', '$name'
);";


	}
}
mysql_query($sql) or die("problem saving tradenames :".mysql_error());
return($sql);

}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param array
 	* 
 	* @return array
        */
function map_PS_56($data_array){

$sql = '';

foreach($data_array as $id => $zero_array){
	
	$my_array = $zero_array[0];

	$inactivation_date = '';

// renable the if statement when you sort out how to translate FileMan dates to MySQL.
//	if(count($my_array) == 6){
		list($name, $ingredient_1, $ingredient_2, $severity, 
				$national, $total_indexes ) = $my_array;
//	}else{
//		list($name, $ingredient_1, $ingredient_2, $severity, 
//				$national, $total_indexes, $inactivation_date ) = $my_array;
//	}

	list( $name_1 , $name_2 ) = split('/',$name);
	$name_1 = mysql_real_escape_string($name_1);
	$name_2 = mysql_real_escape_string($name_2);


$sql .= "INSERT INTO `va_interactions` (  `id` ,  `name_1` ,  `name_2` ,  `ingredient_1` ,  
		`ingredient_2` ,  `severity` ,  `national` ,  `total_indexes` ,  `inactivation_date`  )
VALUES (
	'$id', 
	'$name_1', 
	'$name_2', 
	'$ingredient_1', 
	'$ingredient_2', 
	'$severity', 
	'$national', 
	'$total_indexes', 
	'$inactivation_date'
);";


}

return($sql);

}




?>
