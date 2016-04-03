<?php
//VARIABILI DB
$servername = "localhost";
$username = "Nemesi";
$password = "Nemesi";

//VARIABILI DELLA PAGINA
$nameErr = $bancaErr = "";
$queryResult = "";

//INSERIMENTO IN ARCHIVIO
if(isset($_POST['inserisci']))
{ 
	$name = trim($_POST["nome_cliente"]);
	$nuovaBanca = trim($_POST["nuovaBanca"]);
	$descrizione = trim($_POST["descrizione"]);
	if(isset($_POST["banca"])){ //questo è necessario per la select disabled
		$banca = $_POST["banca"];
	}else{
		$banca = 0;
	}
	$inserisci = true;

	if (empty($name)) {
		$nameErr = "Campo Obbligatorio";
		$inserisci = false;
	}

	if ($banca==0 && empty($nuovaBanca)) {
		$bancaErr = "Campo Obbligatorio";
		$inserisci = false;
	}
	//Effettuo l'inserimento se tutti i dati obbligatori sono stati compilati
	if($inserisci){

		$conn = new mysqli($servername, $username, $password);
		if ($conn->connect_error) {
			die("Errore di connessione: " . $conn->connect_error);
		} 

		//Inserisco i dati nel DB
		$sql = "";
		
		if($banca!=0){	
			//Caso in cui la banca esiste
			$sql = "INSERT INTO Nemesi.t_archivio (nome_cliente, banca, descrizione)
			VALUES ('".$name."','".$banca."','".$descrizione."')";
			
			if ($conn->query($sql) === TRUE) {
			$queryResult = "Record inserito correttamente";
			} else {
				$queryResult = $conn->error;
			}

			$conn->close();
			
		}else{
			//caso nuova banca: inserisco la nuova banca e l'archivio
			$sql = "INSERT INTO Nemesi.l_banca (nome)
			VALUES ('".$nuovaBanca."')";
			
			if ($conn->query($sql) === TRUE) {
				$queryResult = "Record inserito correttamente";
			} else {
				$queryResult = "Errore inserimento banca: ".$conn->error;
			}
			$conn->close();
			
			$conn = new mysqli($servername, $username, $password);
			if ($conn->connect_error) {
				die("Errore di connessione: " . $conn->connect_error);
			} 
			$sql2 = "INSERT INTO Nemesi.t_archivio (nome_cliente, banca, descrizione)
			SELECT '".$name."',id_banca,'".$descrizione."'
			FROM Nemesi.l_banca
			where nome = '".$nuovaBanca."'";
			
			if ($conn->query($sql2) === TRUE) {
				$queryResult = "Record inserito correttamente";
			} else {
				$queryResult = "Errore inserimento Archivio: ".$conn->error;
			}
			$conn->close();
		}
	}
}
///

// CREA LA TABLE DELL'ARCHIVIO
function draw_table(){

	//recupero i dati dell'archivio
	global $servername, $username, $password;
	$conn = new mysqli($servername, $username, $password);

	if ($conn->connect_error) {
		die("Errore di connessione: " . $conn->connect_error);
	} 

	$sql = "SELECT a.id_archivio, a.nome_cliente, b.nome, a.descrizione
			FROM Nemesi.t_archivio a
			LEFT JOIN Nemesi.l_banca b 
			ON a.banca = b.id_banca 
			order by 1";
	$result = $conn->query($sql);

	//Genero la stringa html
	$str_table ="<table class='table table-hover'>
				<thead>
					<tr>
						<th width='5%'>ID</th>
						<th width='20%'>Nome Cliente</th>
						<th width='20%'>Banca</th>
						<th width='37%'>Descrizione</th>
						<th width='55px'></th>
						<th width='55px'></th>
						<th width='55px'></th>
					</tr>
				</thead>
				<tbody>";
	
	//Trasformo i risultati della query in associativi
	while ($row = $result->fetch_assoc()) {
		$str_table.= "<tr>
						<td>".$row["id_archivio"]."</td>
					  	<td>".$row["nome_cliente"]."</td> 
						<td>".$row["nome"]."</td>
						<td>".$row["descrizione"]."</td>
						<td><button class='btn btn-primary'>
							<i class='fa fa-search-plus'></i>
						  </button></td>
						<td><button class='btn btn-primary'>
							<i class='fa fa-wrench'></i>
						  </button></td>
						<td><button class='btn btn-primary'>
							<i class='fa fa-trash'></i>
						  </button></td>
					  </tr>";
	}
	$str_table.= "</tbody></table>";
	
	$conn->close();
	
	return $str_table;
}
///						

function create_dropdown(){
	//recupero i dati dell'archivio
	global $servername, $username, $password;
	$conn = new mysqli($servername, $username, $password);

	if ($conn->connect_error) {
		die("Errore di connessione: " . $conn->connect_error);
	} 
	
	$sql = "SELECT id_banca, nome FROM Nemesi.l_banca";
	$result = $conn->query($sql);

	//Genero la stringa html
	$str_select ="<select id='banca' name='banca' class=form-control>
				  <option value=0/>";
	
	//Trasformo i risultati della query in associativi
	while ($row = $result->fetch_assoc()) {
	$str_select.="<option value='".$row["id_banca"]."'>".$row["nome"]."</option>";
	}
	$str_select.= "</select>";
	
	$conn->close();
	
	return $str_select;
}

function delete_row(){

	
	

	//recupero i dati dell'archivio
	global $servername, $username, $password;
	$conn = new mysqli($servername, $username, $password);

	if ($conn->connect_error) {
		die("Errore di connessione: " . $conn->connect_error);
	} 
	
	$sql = "SELECT id_banca, nome FROM Nemesi.l_banca";
	$result = $conn->query($sql);

	//Genero la stringa html
	$str_select ="<select id='banca' name='banca' class=form-control>
				  <option value=0/>";
	
	//Trasformo i risultati della query in associativi
	while ($row = $result->fetch_assoc()) {
	$str_select.="<option value='".$row["id_banca"]."'>".$row["nome"]."</option>";
	}
	$str_select.= "</select>";
	
	$conn->close();
	
	return $str_select;
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Bootstrap Advance Admin Template</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="assets/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
	        
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">COMPANY NAME</a>
            </div>

            <div class="header-right">

              <a href="message-task.html" class="btn btn-info" title="New Message"><b>30 </b><i class="fa fa-envelope-o fa-2x"></i></a>
                <a href="message-task.html" class="btn btn-primary" title="New Task"><b>40 </b><i class="fa fa-bars fa-2x"></i></a>
                <a href="login.html" class="btn btn-danger" title="Logout"><i class="fa fa-exclamation-circle fa-2x"></i></a>
            </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <div class="user-img-div">
                            <img src="assets/img/user.png" class="img-thumbnail" />

                            <div class="inner-text">
                                Jhon Deo Alex
                            <br />
                                <small>Last Login : 2 Weeks Ago </small>
                            </div>
                        </div>

                    </li>


                    <li>
                        <a  href="index.html"><i class="fa fa-dashboard "></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-desktop "></i>UI Elements <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li>
                                <a href="panel-tabs.html"><i class="fa fa-toggle-on"></i>Tabs & Panels</a>
                            </li>
                            <li>
                                <a href="notification.html"><i class="fa fa-bell "></i>Notifications</a>
                            </li>
                             <li>
                                <a href="progress.html"><i class="fa fa-circle-o "></i>Progressbars</a>
                            </li>
                             <li>
                                <a href="buttons.html"><i class="fa fa-code "></i>Buttons</a>
                            </li>
                             <li>
                                <a href="icons.html"><i class="fa fa-bug "></i>Icons</a>
                            </li>
                             <li>
                                <a href="wizard.html"><i class="fa fa-bug "></i>Wizard</a>
                            </li>
                             <li>
                                <a href="typography.html"><i class="fa fa-edit "></i>Typography</a>
                            </li>
                             <li>
                                <a href="grid.html"><i class="fa fa-eyedropper "></i>Grid</a>
                            </li>
                            
                           
                        </ul>
                    </li>
                     <li>
                        <a href="#"><i class="fa fa-yelp "></i>Extra Pages <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li>
                                <a href="invoice.html"><i class="fa fa-coffee"></i>Invoice</a>
                            </li>
                            <li>
                                <a href="pricing.html"><i class="fa fa-flash "></i>Pricing</a>
                            </li>
                             <li>
                                <a href="component.html"><i class="fa fa-key "></i>Components</a>
                            </li>
                             <li>
                                <a href="social.html"><i class="fa fa-send "></i>Social</a>
                            </li>
                            
                             <li>
                                <a href="message-task.html"><i class="fa fa-recycle "></i>Messages & Tasks</a>
                            </li>
                            
                           
                        </ul>
                    </li>
                    <li>
                        <a href="table.html"><i class="fa fa-flash "></i>Data Tables </a>
                        
                    </li>
                     <li>
                        <a class="active-menu-top" href="#"><i class="fa fa-bicycle "></i>Forms <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level collapse in">
                           
                             <li>
                                <a  class="active-menu" href="form.html"><i class="fa fa-desktop "></i>Basic </a>
                            </li>
                             <li>
                                <a href="form-advance.html"><i class="fa fa-code "></i>Advance</a>
                            </li>
                             
                           
                        </ul>
                    </li>
                     <li>
                        <a href="gallery.html"><i class="fa fa-anchor "></i>Gallery</a>
                    </li>
                     <li>
                        <a href="error.html"><i class="fa fa-bug "></i>Error Page</a>
                    </li>
                    <li>
                        <a href="login.html"><i class="fa fa-sign-in "></i>Login Page</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-sitemap "></i>Multilevel Link <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li>
                                <a href="#"><i class="fa fa-bicycle "></i>Second Level Link</a>
                            </li>
                             <li>
                                <a href="#"><i class="fa fa-flask "></i>Second Level Link</a>
                            </li>
                            <li>
                                <a href="#">Second Level Link<span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#"><i class="fa fa-plus "></i>Third Level Link</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-comments-o "></i>Third Level Link</a>
                                    </li>

                                </ul>

                            </li>
                        </ul>
                    </li>
                   
                    <li>
                        <a  href="blank.html"><i class="fa fa-square-o "></i>Blank Page</a>
                    </li>
                </ul>
            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Archivio</h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="panel panel-default">
                        <div class="panel-heading">
                           INSERIMENTO ARCHIVIO
                        </div>
                        <div class="panel-body">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
								<div class="form-group">
									<label>Nome Cliente</label>
									<input class="form-control" type="text" name="nome_cliente">
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
								</div>
								<div>
									<label>Banca</label>
								</div>
                                 <div class="input-group">									
									<?php echo create_dropdown(); ?> 
									 <span class="form-group input-group-btn">
									 	<button type="button" class="btn btn-primary" onClick="showNuovaBanca();">
									 		<i class="glyphicon glyphicon-plus"></i>
									 	</button>
									 </span>
								</div>
								<div>
									<p class="help-block" style="color:red;"><?php echo $bancaErr;?></p>
								</div>
								<div id="nuovaBancaDiv" style="display: none;">
									<div>
										<label>Nuova Banca</label>
									</div>
									<div class="input-group">
										<input class="form-control" type="text" name="nuovaBanca" id="nuovaBanca">
										<span class="form-group input-group-btn">
											<button type="button"class="btn btn-primary" onClick="hideNuovaBanca();">
												<i class="glyphicon glyphicon-minus"></i>
											</button>
										 </span>
									</div>
								</div>
								<div class="form-group">
                                            <label>Descrizione</label>
                                            <input class="form-control" type="text" name="descrizione">
 							    </div>
                                 <div> &nbsp </div>
                                        <button type="submit" name="inserisci" class="btn btn-primary">Inserisci </button>
										 <p class="help-block" float=right; style="color:green;"><?php echo $queryResult;?></p>
                                    </form>
                            </div>
                        </div>
        </div>
        

             <!--/.ROW-->
            </div>
            <!--    Hover Rows  -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            LISTA ARCHIVIO
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php echo draw_table(); ?>
                            </div>
                        </div>
                    </div>
                    <!-- End  Hover Rows  -->
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!--<div id="footer-sec">
        &copy; 2014 YourCompany | Design By : <a href="http://www.binarytheme.com/" target="_blank">BinaryTheme.com</a>
    </div>-->
    <!-- /. FOOTER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>

	<script>
	
	//abilito campo nuova banca
	function showNuovaBanca(){
		$('#nuovaBancaDiv').show();
		$('#banca').val(0);
		$('#banca').prop('disabled', 'disabled');
	}
	//nascondo campo nuova banca
	function hideNuovaBanca(){
		$('#nuovaBanca').val('');
		$('#nuovaBancaDiv').hide();
		$('#banca').prop('disabled', false);
	} 
	 
	</script>

</body>
</html>
