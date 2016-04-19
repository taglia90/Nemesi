<?php

class MysqlClass
{
    // parametri per la connessione al database
    private $nomehost = "localhost";

    private $nomedb = "Nemesi";

    private $nomeuser = "Nemesi";

    private $password = "Nemesi";
    
    // controllo sulle connessioni attive
    private $attiva = false;
    
    // funzione per la connessione a MySQL
    public function connetti ()
    {
        if (! $this->attiva) {
            if ($connessione = mysql_connect($this->nomehost, $this->nomeuser, 
                    $this->password) or die(mysql_error())) {
                // selezione del database
                $selezione = mysql_select_db($this->nomedb, $connessione) or
                         die(mysql_error());
            }
        } else {
            return true;
        }
    }
    
    // funzione per la chiusura della connessione
    public function disconnetti ()
    {
        if ($this->attiva) {
            if (mysql_close()) {
                $this->attiva = false;
                return true;
            } else {
                return false;
            }
        }
    }
    
    // funzione per l'esecuzione delle query
    public function query ($sql)
    {
        if (isset($this->attiva)) {
            $sql = mysql_query($sql) or die(mysql_error());
            return $sql;
        } else {
            return false;
        }
    }
    
    // funzione per l'inserimento dei dati in tabella
    // $t: il nome della tabella in cui effettuare l’inserimento;
    // $v: i valori da inserire;
    // $r: i campi da popolare tramite i valori specificati dall’argomento
    // precedente.
    public function inserisci ($t, $v, $r = null)
    {
        if (isset($this->attiva)) {
            $istruzione = 'INSERT INTO ' . $t;
            if ($r != null) {
                $istruzione .= ' (' . $r . ')';
            }
            
            for ($i = 0; $i < count($v); $i ++) {
                if (is_string($v[$i]))
                    $v[$i] = '"' . $v[$i] . '"';
            }
            $v = implode(',', $v);
            $istruzione .= ' VALUES (' . $v . ')';
            
            $query = mysql_query($istruzione) or die(mysql_error());
        } else {
            return false;
        }
    }
    
    // funzione per l'estrazione dei record
    public function estrai ($risultato)
    {
        if (isset($this->attiva)) {
            $r = mysql_fetch_object($risultato);
            return $r;
        } else {
            return false;
        }
    }
}

?>