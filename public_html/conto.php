﻿<?php
require_once ("header.php");

//VARIABILI DELLA PAGINA
$queryResult = "";

//INSERIMENTO IN ARCHIVIO
if(isset($_POST['inserisci']))
{ 
	$numero = trim($_POST["numero"]);
	$tipo = trim($_POST["tipo"]);
	$intestazione = trim($_POST["intestazione"]);
	$indirizzo = trim($_POST["indirizzo"]);
	$cap = trim($_POST["cap"]);
	$localita = trim($_POST["localita"]);
	$provincia = trim($_POST["provincia"]);
	$dataApertura = trim($_POST["dataApertura"]);
	$dataChiusura = trim($_POST["dataChiusura"]);
	$iban = trim($_POST["iban"]);
	$valuta = trim($_POST["valuta"]);
		
	$inserisci = true;
	
	
	
	/*if(isset($_POST["banca"])){ //questo è necessario per la select disabled
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
	}*/
	
	//Effettuo l'inserimento se tutti i dati obbligatori sono stati compilati
	if($inserisci){
		
		$conn = new mysqli($config["db"]["db1"]["host"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
		
		if ($conn->connect_error) {
			die("Errore di connessione: " . $conn->connect_error);
		} 

		//Inserisco i dati nel DB
		$sql = "";
		
		//Caso in cui la banca esiste
		$sql = "INSERT INTO t_conto (nr_conto, id_tipo_conto, intestazione, indirizzo, 
		cap, localita, provincia, data_apertura, data_chiusura, iban, valuta)
		VALUES (".$numero.",".$tipo.",'".$intestazione."','".$indirizzo."','".$cap."'
		,'".$localita."','".$provincia."','".$dataApertura."','".$dataChiusura."'
		,'".$iban."','".$valuta."')";
		
		
		if ($conn->query($sql) === TRUE) {
		$queryResult = "Record inserito correttamente";
		} else {
			$queryResult = $conn->error;
		}

		$conn->close();
			
	}
	
}
///

// CREA LA TABLE DEL CONTO
function draw_table(){

	//recupero i dati dell'archivio
	global $config;
	$conn = new mysqli($config["db"]["db1"]["host"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);

	if ($conn->connect_error) {
		die("Errore di connessione: " . $conn->connect_error);
	} 


	$sql = "SELECT a.id_conto, a.nr_conto, a.id_tipo_conto
			FROM t_conto a ";
			
	
	 if(isset($_POST["idArchivio"])){
 		$sql.= " WHERE a.id_archivio = ".$_POST["idArchivio"]." ";
 	}
		
	$sql .= " order by 1";

	$result = $conn->query($sql);

	//Genero la stringa html
	$str_table ="<table class='table table-hover' id='archivioTable'>
				<thead>
					<tr>
						<th width='42%'>Numero Conto</th>
						<th width='42%'>Tipo Conto</th>
						<th width='55px'></th>
						<th width='55px'></th>
					</tr>
				</thead>
				<tbody>";
	
	//Trasformo i risultati della query in associativi
	while ($row = $result->fetch_assoc()) {
		$str_table.= "<tr>
						<td>".$row["nr_conto"]."</td>
					  	<td>".$row["id_tipo_conto"]."</td> 
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

//
function create_dropdown(){
	//recupero i dati dell'archivio
	global $config;
	$conn = new mysqli($config["db"]["db1"]["host"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);

	if ($conn->connect_error) {
		die("Errore di connessione: " . $conn->connect_error);
	} 

	$sql = "SELECT id_tipo_conto, tipo FROM l_tipo_conto"; 

	$result = $conn->query($sql);

	//Genero la stringa html
	$str_select ="<select id='tipoConto' name='tipoConto' class=form-control>
				  <option value=0/>";

	//Trasformo i risultati della query in associativi
	while ($row = $result->fetch_assoc()) {
	$str_select.="<option value='".$row["id_tipo_conto"]."'>".$row["tipo"]."</option>";
	}
	$str_select.= "</select>";

	$conn->close();

	return $str_select;
}
///

require_once ("leftPanel.php");
?>


        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line"><?php echo $lang['CONTO_HEADLINE']; ?></h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="panel panel-default">
                        <div class="panel-heading">
                           <?php echo $lang['CONTO_INSERIMENTO']; ?>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
								<div class="form-group">
									<label><?php echo $lang['CONTO_NUMERO']; ?></label>
									<input class="form-control" type="text" name="numero">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group">
									<label><?php echo $lang['CONTO_TIPO']; ?></label>
									<?php echo create_dropdown(); ?> 
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group">
									<label><?php echo $lang['CONTO_INTESTAZIONE']; ?></label>
									<input class="form-control" type="text" name="intestazione">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group" style="width:66%; margin-right:0px; float:left;">
									<label><?php echo $lang['CONTO_INDIRIZZO']; ?></label>
									<input class="form-control" type="text" name="indirizzo" >
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								
								</div>
								<div class="form-group" style="width:32%; margin-right:0px; float:right;">
 
									<label><?php echo $lang['CONTO_CAP']; ?></label>
									<input class="form-control" type="text" name="cap">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group" style="width:83%; margin-right:0px; float:left;">
									<label><?php echo $lang['CONTO_LOCALITA']; ?></label>
									<input class="form-control" type="text" name="localita">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group" style="width:15%; margin-right:0px; float:right;">
									<label><?php echo $lang['CONTO_PROVINCIA']; ?></label>
									<input class="form-control" type="text" name="provincia">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group" style="width:49%; margin-right:0px; float:left;">
									<label><?php echo $lang['CONTO_DATA_APERTURA']; ?></label>
									<input class="form-control" type="text" name="dataApertura" id="datepicker1">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group" style="width:49%; margin-right:0px; float:right;">
									<label><?php echo $lang['CONTO_DATA_CHIUSURA'] ?></label>
									<input class="form-control" type="text" name="dataChiusura" id="datepicker2">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group">
									<label><?php echo $lang['CONTO_IBAN'] ?></label>
									<input class="form-control" type="text" name="iban">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
								<div class="form-group">
									<label><?php echo $lang['CONTO_VALUTA'] ?></label>
									<input class="form-control" type="text" name="valuta">
<!-- 
									<p class="help-block" style="color:red;"><?php echo $nameErr;?></p>
 -->
								</div>
                                 <div> &nbsp </div>
                                        <button type="submit" name="inserisci" class="btn btn-primary"><?php echo $lang['CONTO_INSERISCI']; ?></button>
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
                            <?php echo $lang['CONTO_LISTA_CONTI']; ?>
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
<?php require_once("footer.php");?>
<!-- CUSTOM SCRIPT PER QUESTA PAGINA -->	
<script>
setActiveMenu("archivio");

$(document).ready(function(){
    $('#archivioTable').DataTable();
    $.datepicker.setDefaults({dateFormat: 'dd/mm/yy'});
	$('#datepicker1').datepicker();
	$('#datepicker2').datepicker();
});

</script>

</body>
</html>
