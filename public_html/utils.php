<?php
//Restituisce la connessione
function connetti (){

	global $config;
	$conn = new mysqli($config["db"]["db1"]["host"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
		
		if ($conn->connect_error) {
			die("Errore di connessione: " . $conn->connect_error);
		}
	return $conn;
}
///
?>