<?php



$config['db_type'] = "mysql";
$config['db_host'] = "localhost";
$config['db_user'] = "root"; 
$config['db_password'] = "password";
$config['db_name'] = "mirrormed";
$config['openemr_db'] = "openemr";
$GLOBALS['config'] = $config;


$mirrormed = '/var/www/html/mirrormed/';


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



$import =& Celini::newOrdo('Mm2mmImportMap');

$patfile = '/home/ftrotter/synseer/visser/031208/patfile.dat';

$import->import($patfile);




?>
