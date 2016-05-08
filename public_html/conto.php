<?php
require_once ("header.php");

// VARIABILI DELLA PAGINA
$queryResult = "";
$numErr = $tipoErr = $dataApErr = $dataChErr = "";
$editPage = false;
$listaConti = $lang['CONTO_LISTA_CONTI'];

// INSERIMENTO O MODIFICA CONTO
if (isset($_POST['inserisci'])) {
    // isEdit = true se è una pagina di modifica
    $isEdit = $_POST['inserisci'];
    
    $numero = trim($_POST["numero"]);
    $intestazione = trim($_POST["intestazione"]);
    $indirizzo = trim($_POST["indirizzo"]);
    $cap = trim($_POST["cap"]);
    $localita = trim($_POST["localita"]);
    $provincia = trim($_POST["provincia"]);
    $dataApertura = trim($_POST["dataApertura"]);
    $dataChiusura = trim($_POST["dataChiusura"]);
    $iban = trim($_POST["iban"]);
    $valuta = trim($_POST["valuta"]);
    $archivio = trim($_POST["arch"]);
    
    $inserisci = true;
    
    if (empty($numero)) {
        $numErr = "Campo Obbligatorio";
        $inserisci = false;
    }
    
    if (isset($_POST["tipoConto"])) {
        $tipo = $_POST["tipoConto"];
        if ($tipo == 0) {
            $tipoErr = "Campo Obbligatorio";
            $inserisci = false;
        }
    } else {
        $tipoErr = "Campo Obbligatorio";
        $inserisci = false;
    }
    
    if (!empty($dataApertura)) {
        if (validate_date($dataApertura)){
            $dataApertura = "STR_TO_DATE('" . $dataApertura ."', '%d/%m/%Y')";
        } else {
            $dataApErr = "Formato sbagliato. Inserire le date come gg/mm/aaaa";
            $inserisci = false;
        }
    } else {
        $dataApErr = "Campo Obbligatorio";
        $inserisci = false;
    }
    
    if (!empty($dataChiusura)) {
        if (validate_date($dataApertura)){
            $dataApertura = "STR_TO_DATE('" . $dataApertura ."', '%d/%m/%Y')";
        } else {
            $dataApErr = "Formato sbagliato. Inserire le date come gg/mm/aaaa";
            $inserisci = false;
        }
        $dataChiusura = "STR_TO_DATE('" . $dataChiusura ."', '%d/%m/%Y')";
    } else {
        $dataChiusura = "null";
    }
    
    // Effettuo l'inserimento se tutti i dati obbligatori sono stati compilati
    if ($inserisci) {
        
        $conn = connetti();
        
        if ($conn->connect_error) {
            die("Errore di connessione: " . $conn->connect_error);
        }
        
        //Inserisco i dati nel DB
        $sql = "";
        if ($isEdit) {
            $sql = "UPDATE t_conto SET 
                    id_archivio = " . $archivio . ",
                    nr_conto = " . $numero . ", 
                    id_tipo_conto = " . $tipo . ",
                    intestazione = '" . $intestazione . "',
                    indirizzo = '" . $indirizzo . "',
                    cap = '" . $cap . "',
                    localita = '" . $localita . "',
                    provincia = '" . $provincia . "',
                    data_apertura = " . $dataApertura . ",
                    data_chiusura = " . $dataChiusura . ",
                    iban = '" . $iban . "',
                    valuta = '" . $valuta . "'
                    WHERE id_CONTO = ".$_POST["idConto"];
         
        } else {
            $sql = "INSERT INTO t_conto (nr_conto, id_tipo_conto, intestazione, indirizzo, 
    		cap, localita, provincia, data_apertura, data_chiusura, iban, valuta, id_archivio)
    		VALUES (" . $numero . "," . $tipo . ",'" . $intestazione . "','" .
                     $indirizzo . "','" . $cap . "','" . $localita . "','" .
                     $provincia . "',".$dataApertura.",".$dataChiusura.",'" . $iban . "','" . $valuta .
                     "', ".$archivio.")";
        }

        if ($conn->query($sql) === TRUE) {
            $queryResult = "Record inserito correttamente";
        } else {
            $queryResult = $conn->error;
        }
        
        $conn->close();
    }
}
// /

// CREA LA TABLE DEL CONTO
function draw_table ()
{
    
    // recupero i dati del conto
    $conn = connetti();
    
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "SELECT a.id_conto, a.nr_conto, a.id_tipo_conto
			FROM t_conto a ";
    if (isset($_POST["idArchivio"])) {
        $sql .= " WHERE a.id_archivio = " . $_POST["idArchivio"] . " ";
    }
    $sql .= " order by 1";
    
    $result = $conn->query($sql);
    
    // Genero la stringa html
    $str_table = "<table class='table table-hover' id='contoTable'>
				<thead>
					<tr>
						<th width='42%'>Numero Conto</th>
						<th width='42%'>Tipo Conto</th>
						<th width='55px'></th>
						<th width='55px'></th>
					</tr>
				</thead>
				<tbody>";
    
    // Trasformo i risultati della query in associativi
    while ($row = $result->fetch_assoc()) {
        $str_table .= "<tr>
						<td>" . $row["nr_conto"] . "</td>
					  	<td>" . $row["id_tipo_conto"] . "</td> 
						<td><form method='post' action='conto.php'>
					  	   <button  type='submit' title='Modifica Conto' name='editConto'
					  	   class='btn btn-primary' value='" . $row["id_conto"] ."'>
							<i class='fa fa-wrench'></i>
						  </button></form></td>
						<td><form method='post' action='conto.php'>
					  	   <button type='submit' title='Elimina Conto' name='deleteRow'
					  	        class='btn btn-primary' value='" . 
					  	        $row["id_conto"] . "'>
					  	    <i class='fa fa-trash'></i>
						  </button></form></td>
					  </tr>";
    }
    $str_table .= "</tbody></table>";
    
    $conn->close();
    
    return $str_table;
}
// /

//
function create_dropdown_tipo ()
{
    // recupero i dati dell'archivio
    $conn = connetti();
    
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "SELECT id_tipo_conto, tipo FROM l_tipo_conto";
    
    $result = $conn->query($sql);
    
    // Genero la stringa html
    $str_select = "<select id='tipoConto' name='tipoConto' class=form-control>
				  <option value='0' />";
    
    // Trasformo i risultati della query in associativi
    while ($row = $result->fetch_assoc()) {
        $str_select .= "<option value='" . $row["id_tipo_conto"] . "'>" .
                 $row["tipo"] . "</option>";
    }
    $str_select .= "</select>";
    
    $conn->close();
    
    return $str_select;
}
// /

//
function create_dropdown_archivio ()
{
    //setto gli attributi nel caso l'archivio sia stato selezionato
    $sqlWhere = "";
    $option0 = "<option value='0'/>";
  
    if (isset($_POST["idArchivio"])) {
        $sqlWhere = "WHERE a.id_archivio = " . $_POST["idArchivio"];
        //$disabled = "disabled";
        $option0 = "";
    }

    // recupero i dati dell'archivio
    $conn = connetti();
    
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "SELECT a.id_archivio, a.nome_cliente, b.nome FROM t_archivio a 
            LEFT JOIN l_banca b ON a.banca = b.id_banca " . $sqlWhere;
    
    $result = $conn->query($sql);
    
    // Genero la stringa html
    $str_select = "<select id='arch' name='arch' class=form-control>";
    $str_select.= $option0;
        
    // Trasformo i risultati della query in associativi
    while ($row = $result->fetch_assoc()) {
        $str_select .= "<option value='" . $row["id_archivio"] . "'>" .
                 $row["nome_cliente"] . " - " . $row["nome"] . "</option>";
         
         if (isset($_POST["idArchivio"])) {
             global $listaConti, $lang; 
             $listaConti.= $lang['CONTO_DELL_ARCHIVIO'].$row["nome_cliente"] . " - " . $row["nome"];
         }
    }
    $str_select .= "</select>";
    
    $conn->close();
    
    return $str_select;
}
// /

if (isset($_POST['deleteRow'])) {

    $conn = connetti();
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }

    $sql = "DELETE FROM t_conto
			WHERE id_conto =" . $_POST['deleteRow'] . " ";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Eliminazione effettuata correttamente');</script>";
    } else {
        echo "<script>alert('Errore eliminazione: " . $conn->connect_error .
        "');</script>";
    }

    $conn->close();
}

// PASSA I CAMPI DA INIZIALIZZARE
if (isset($_POST['editConto'])) {

    $conn = connetti();
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM t_conto
			WHERE id_conto = " . $_POST['editConto'] .
			" ";
    
    $result = $conn->query($sql);

    $conn->close();

    $editPage = true;
}
// /

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
						<form method="post"
							action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<input type="hidden" name="idConto" id="idConto">
							<div class="form-group">
								<label><?php echo $lang['CONTO_ARCHIVIO']; ?></label>
								<?php echo create_dropdown_archivio(); ?> 

									<p class="help-block" style="color: red;"><?php echo $tipoErr;?></p>

							</div>
							<div class="form-group">
								<label><?php echo $lang['CONTO_NUMERO']; ?></label> <input
									class="form-control" type="number" id="numero" name="numero">

								<p class="help-block" style="color: red;"><?php echo $numErr;?></p>

							</div>
							<div class="form-group">
								<label><?php echo $lang['CONTO_TIPO']; ?></label>
									<?php echo create_dropdown_tipo(); ?> 

									<p class="help-block" style="color: red;"><?php echo $tipoErr;?></p>

							</div>
							<div class="form-group">
								<label><?php echo $lang['CONTO_INTESTAZIONE']; ?></label> <input
									class="form-control" type="text" id="intestazione" name="intestazione">
							</div>
							<div class="form-group"
								style="width: 66%; margin-right: 0px; float: left;">
								<label><?php echo $lang['CONTO_INDIRIZZO']; ?></label> <input
									class="form-control" type="text" id="indirizzo" name="indirizzo">
							</div>
							<div class="form-group"
								style="width: 32%; margin-right: 0px; float: right;">

								<label><?php echo $lang['CONTO_CAP']; ?></label> <input
									class="form-control" type="number" id="cap" name="cap">
							</div>
							<div class="form-group"
								style="width: 83%; margin-right: 0px; float: left;">
								<label><?php echo $lang['CONTO_LOCALITA']; ?></label> <input
									class="form-control" type="text" id="localita" name="localita">
							</div>
							<div class="form-group"
								style="width: 15%; margin-right: 0px; float: right;">
								<label><?php echo $lang['CONTO_PROVINCIA']; ?></label> <input
									class="form-control" type="text" id="provincia" name="provincia" maxlength="2">

							</div>
							<div class="form-group"
								style="width: 49%; margin-right: 0px; float: left;">
								<label><?php echo $lang['CONTO_DATA_APERTURA']; ?></label> <input
									class="form-control" type="text" id="dataApertura" name="dataApertura">
									<p class="help-block" style="color:red;"><?php echo $dataApErr;?></p>
							</div>
							<div class="form-group"
								style="width: 49%; margin-right: 0px; float: right;">
								<label><?php echo $lang['CONTO_DATA_CHIUSURA'] ?></label> <input
									class="form-control" type="text" id="dataChiusura" name="dataChiusura">
									<p class="help-block" style="color:red;"><?php echo $dataChErr;?></p>
							</div> 
							<div class="form-group">
								<label><?php echo $lang['CONTO_IBAN'] ?></label> <input
									class="form-control" type="text" id="iban" name="iban" maxlength="28">
							</div>
							<div class="form-group">
								<label><?php echo $lang['CONTO_VALUTA'] ?></label> <input
									class="form-control" type="text" id="valuta" name="valuta">
							</div>
							<div>&nbsp;</div>
							<button type="submit" id="insertEdit" name="inserisci" 
								value="0" class="btn btn-primary"><?php echo $lang['BUTTON_INSERISCI']; ?></button>
							<p class="help-block" style="color: green;"><?php echo $queryResult;?></p>
						</form>
					</div>
				</div>
			</div>
			<!--/.ROW-->
		</div>
		<!--    Hover Rows  -->
		<div class="panel panel-default">
			<div class="panel-heading">
                            <?php echo $listaConti; ?>
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

<?php require_once("footer.php"); ?>
<!-- CUSTOM SCRIPT PER QUESTA PAGINA -->
<script>
setActiveMenu("conto");

$(document).ready(function(){
	
    $('#contoTable').DataTable();
    $.datepicker.setDefaults({dateFormat: 'dd/mm/yy'});
	$('#dataApertura').datepicker();
	$('#dataChiusura').datepicker();
	<?php
    // INIZIALIZZO I CAMPI SE SONO IN EDIT
    if ($editPage) {
         $row = $result->fetch_assoc();
//         echo "alert('". date("d/m/Y", strtotime($row["data_apertura"])). " UUUU');";
         echo "$('#arch').val(". $row["id_archivio"] .");
               $('#numero').val(" . $row["nr_conto"] . ");   
               $('#tipoConto').val('" . $row["id_tipo_conto"] . "');
               $('#intestazione').val('" . $row["intestazione"] . "');
               $('#indirizzo').val('" . $row["indirizzo"] . "');
               $('#cap').val('" . $row["cap"] . "');
               $('#localita').val('" . $row["localita"] . "');
               $('#provincia').val('" . $row["provincia"] . "');
               $('#dataApertura').val('" . date("d/m/Y", strtotime($row["data_apertura"])). "');
               $('#dataChiusura').val('" . date("d/m/Y", strtotime($row["data_chiusura"])). "');
               $('#iban').val('" . $row["iban"] . "');
               $('#valuta').val('" . $row["valuta"] . "');
               $('#idConto').val('" . $row["id_conto"] . "');
               $('#insertEdit').val(1);
               $('#insertEdit').html('" .
               $lang['BUTTON_MODIFICA'] . "');";
    }
    ?>
});

</script>
</body>
</html>
