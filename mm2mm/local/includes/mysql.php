<?php

	$config['db_type'] = "mysql";
	$config['db_host'] = "localhost";
	$config['db_user'] = "root"; 
	$config['db_password'] = "password";
	$config['db_name'] = "mm2mm";
	mysql_connect($config['db_host'],$config['db_user'],$config['db_password']);
	mysql_select_db($config['db_name']) or die(" Could not select the database");

?>
