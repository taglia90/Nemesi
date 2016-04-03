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



require_once ("/header.php");
require_once ("/leftPanel.php");
?>


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
    <script src="js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="js/custom.js"></script>
	
	<!-- CUSTOM SCRIPT PER QUESTA PAGINA -->	
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
