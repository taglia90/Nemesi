<?php

// Restituisce la connessione
function connetti ()
{
    global $config;
    $conn = new mysqli($config["db"]["db1"]["host"], 
            $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], 
            $config["db"]["db1"]["dbname"]);
    
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    return $conn;
}

function validator ($regex, $field)
{
    echo $regex." ".$field;
    if (preg_match($regex, $field)) {
        return true;
    } else {
        return false;
    }
}

function validate_date ($field)
{
    return validator(REGEX_DATE, $field);
}

function validate_number ($field)
{
    return validator(REGEX_NUMBER, $field);
}

// /
?>