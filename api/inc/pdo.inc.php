<?php

	$db_host = '190.25.158.198';
	$db_port = '1433';
	$db_name = 'JROJAS';
	$db_user = 'itsperu';
	$db_pass = '12345*itsperu';

	try {
		$DBS = new PDO('sqlsrv:Server='.$db_host.','.$db_port.';Database='.$db_name,$db_user,$db_pass);
	} catch (PDOException $err) {
		die('ERROR SQL: '.$err->getMessage());
	}

	try {
		$DB = new PDO('mysql:host=localhost;port=3306;dbname=ultraschall_staff','ultraschall_staff','$6x7yE0JKG]t6@DQ');
	} catch (PDOException $e) {
		die('ERROR: '.$e->getMessage());
	}

?>