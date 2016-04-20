<?php
// inclusione del file contenente la classe
include "funzioni_mysql.php";
// istanza della classe
$data = new MysqlClass();
// connessione a MySQL
$data->connetti();
// chiamata alla funzione per la creazione del database
$data->query("qua inserisco la query");
// disconnessione
$data->disconnetti();
?>