<?php
// inizializzazione della sessione
session_start();
require_once ("../config.php");
include_once 'utils.php';
// se la sessione di autenticazione
// � gi� impostata non sar� necessario effettuare il login
// e il browser verr� reindirizzato alla pagina di scrittura dei post
if (isset($_SESSION['login'])) {
    // reindirizzamento alla homepage in caso di login gi� in memoria
    header("Location: index.php");
}
// controllo sul parametro d'invio
if (isset($_POST['submit']) && (trim($_POST['submit']) == "Login")) {
    // controllo sui parametri di autenticazione inviati
    if (! isset($_POST['username']) || $_POST['username'] == "") {
        echo "Attenzione, inserire la username.";
    } elseif (! isset($_POST['password']) || $_POST['password'] == "") {
        echo "Attenzione, inserire la password.";
    } else {
        // validazione dei parametri tramite filtro per le stringhe
        $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
        $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
        $password = sha1($password);
        // inclusione del file della classe
        //include "funzioni_mysql.php";
        // chiamata alla funzione di connessione
        $conn = connetti();
        // interrogazione della tabella
        $sqlString = "SELECT id_login FROM login WHERE username = '".$username."' AND password = '".$password."'";
        $auth = $conn->query($sqlString);
        // controllo sul risultato dell'interrogazione
        if ($auth->num_rows == 0) {
            // reindirizzamento alla homepage in caso di insuccesso
            header("Location: login.php");
        } else {
            // chiamata alla funzione per l'estrazione dei dati
            $res = $auth->fetch_assoc();
            // creazione del valore di sessione
            $_SESSION['login'] = $res["id_login"];
            // disconnessione da MySQL
            $conn->close();
            // reindirizzamento alla pagina di amministrazione in caso di
            // successo
            header("Location: index.php");
        }
    }
} else {
    // form per l'autenticazione
    ?>
<!--  <h1>Login:</h1>
<form action="<?php #echo $_SERVER['PHP_SELF']; ?>" method="POST">
Username:<br />
<input name="username" type="text"><br />
Password:<br />
<input name="password" type="password" size="20"><br />
<input name="submit" type="submit" value="Login">
</form>
  -->



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login</title>

<!-- BOOTSTRAP STYLES-->
<link href="css/bootstrap.css" rel="stylesheet" />
<!-- FONTAWESOME STYLES-->
<link href="css/font-awesome.css" rel="stylesheet" />
<!-- GOOGLE FONTS-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans'
	rel='stylesheet' type='text/css' />

</head>
<body style="background-color: #E2E2E2;">
	<div class="container">
		<div class="row text-center " style="padding-top: 100px;">
			<div class="col-md-12">
				<img src="img/logo-invoice.png" />
			</div>
		</div>
		<div class="row ">

			<div
				class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

				<div class="panel-body">
					<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>"
						method="POST">

						<hr />
						<h5>Enter Details to Login</h5>
						<br />
						<div class="form-group input-group">
							<span class="input-group-addon"><i class="fa fa-tag"></i></span>
							<input type="text" name="username" class="form-control"
								placeholder="Your Username " />
						</div>
						<div class="form-group input-group">
							<span class="input-group-addon"><i class="fa fa-lock"></i></span>
							<input type="password" name="password" class="form-control"
								placeholder="Your Password" />
						</div>
						<div class="form-group">
							<label class="checkbox-inline"> <input type="checkbox" />
								Remember me
							</label> <span class="pull-right"> <a href="index.html">Forget
									password ? </a>
							</span>
						</div>

						<!-- <a href="index.html"  class="btn btn-primary ">Login Now</a> -->
						<input name="submit" type="submit" value="Login">
						<hr />
						Not register ? <a href="index.html">click here </a> or go to <a
							href="index.html">Home</a>
					</form>
				</div>

			</div>


		</div>
	</div>

</body>
</html>


<?php
}
?>