<?php

function connectBD()
{
	$server = "localhost";

	$user = "root";

	$pass = "";

	$dbName = "POC_Charles1";

	try {
		$co = new PDO("mysql:host=" . $server . ";dbname=" . $dbName, $user, $pass);
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e) {
		die('Erreur : ' . $e->getMessage());
	}
	return $co;
}
