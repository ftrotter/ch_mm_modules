<?php

$start_time = date('h:i:s A');

require_once('dbconfig.php');

if(isset($argv[1])){
	$patfile = $argv[1];
}else{
	$patfile = $config_patfile;
}
if(isset($argv[1])){
	$offnotes = $argv[2];
}else{
	$offnotes = $config_offnotes;
}



if (!defined('CELINI_ROOT')) {
        define('CELINI_ROOT', $mirrormed . "celini/");
}                                                                                 
/**
* Base application dir
*/
if (!defined('APP_ROOT')) {
        define('APP_ROOT', $mirrormed);
}
                                                                                
/**
* Base module dir
*/
if (!defined('MODULE_ROOT')) {
        define('MODULE_ROOT',realpath(APP_ROOT."/modules"));
}

require_once CELINI_ROOT . "bootstrap.php";
require_once APP_ROOT . "local/config.php";
//require_once CELINI_ROOT . "bootstrap.php";
//require_once CELINI_ROOT . "ordo/ORDataObject.class.php";
//require_once CELINI_ROOT . "includes/Celini.class.php";
//require_once CELINI_ROOT . "includes/ORDOFactory.class.php";
set_time_limit(0);



//$import =& Celini::newOrdo('Mm2mmImportMap');
require_once("controllers/C_Import.class.php");
$import = new C_Import();

if(file_exists($patfile)){
	$import->import($patfile);
}else{
	echo "no patfile $patfile\n";
	exit();
}

if(file_exists($offnotes)){
	$import->importComments($offnotes);
}else{
	echo "no offnotes $offnotes\n";
	exit();
}


$end_time = date('h:i:s A');

echo "start $start_time end $end_time\n";

?>
