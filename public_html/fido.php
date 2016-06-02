<?php
require_once ("header.php");

// VARIABILI DELLA PAGINA
$listaFidi = $lang['FIDO_LISTA_FIDI'];
$queryResult = "";
$contoErr = $dataAffErr ="";

// INSERIMENTO O MODIFICA FIDO
if (isset($_POST['inserisci'])) {
    // isEdit = true se è una pagina di modifica
    $isEdit = $_POST['inserisci'];
    
    $numero = trim($_POST["conto"]);
    $dataAffidamento = trim($_POST["dataAffidamento"]);
    
    $inserisci = true;
    
    if (isset($_POST["conto"])) {
        $conto = $_POST["conto"];
        if ($conto == 0) {
            $contoErr = "Campo Obbligatorio";
            $inserisci = false;
        }
    } else {
        $contoErr = "Campo Obbligatorio";
        $inserisci = false;
    }
    
    if (! empty($dataAffidamento)) {
        if (validate_date($dataAffidamento)) {
            $dataAffidamento = "STR_TO_DATE('" . $dataAffidamento . "', '%d/%m/%Y')";
        } else {
            $dataAffErr = "Formato sbagliato. Inserire le date come gg/mm/aaaa";
            $inserisci = false;
        }
    } else {
        $dataAffErr = "Campo Obbligatorio";
        $inserisci = false;
    }
    
    // Effettuo l'inserimento se tutti i dati obbligatori sono stati compilati
    if ($inserisci) {
        
        $conn = connetti();
        
        if ($conn->connect_error) {
            die("Errore di connessione: " . $conn->connect_error);
        }
        
        // Inserisco i dati nel DB
        $sql = "";
        if ($isEdit) {
            $sql = "UPDATE t_fido SET 
                    id_conto = " . $conto . ",
                    data = " . $dataAffidamento . "
                    WHERE id_fido = " . $_POST["idFido"];
        } else {
            $sql = "INSERT INTO t_fido (data, id_conto)
    		VALUES (" .
                     $dataAffidamento . "," . $conto . ")";
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


// CREA LA TABLE DEL FIDO
function draw_table ()
{
    // recupero i dati del conto
    $conn = connetti();
    
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "SELECT a.id_conto, a.nr_conto, b.nome_cliente, f.data, f.id_fido FROM t_fido f LEFT JOIN t_conto a 
            ON f.id_conto = a.id_conto LEFT JOIN t_archivio b 
            ON a.id_archivio = b.id_archivio ";
    if (isset($_POST["idConto"])) {
        $sql .= " WHERE a.id_conto = " . $_POST["idConto"] . " ";
    }
    $sql .= " order by 1";
    
    $result = $conn->query($sql);
    
    // Genero la stringa html
    $str_table = "<table class='table table-hover' id='fidoTable'>
				<thead>
					<tr>
						<th width='auto'>Conto</th>
						<th width='auto'>Data Affidamento</th>
						<th width='55px'></th>
						<th width='55px'></th>
					</tr>
				</thead>
				<tbody>";
    
    // Trasformo i risultati della query in associativi
    while ($row = $result->fetch_assoc()) {
        $str_table .= "<tr>
						<td>" . $row["nome_cliente"] . " - ".$row["nr_conto"]."</td>
					  	<td>" . $row["data"] . "</td> 
						<td><form method='post' action='fido.php'>
					  	   <button  type='submit' title='Modifica Fido' name='editFido'
					  	   class='btn btn-primary' value='" .
                 $row["id_fido"] . "'>
							<i class='fa fa-wrench'></i>
						  </button></form></td>
						<td><form method='post' action='fido.php'>
					  	   <button type='submit' title='Elimina Fido' name='deleteRow'
					  	        class='btn btn-primary' value='" . $row["id_fido"] . "'>
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
function create_dropdown_conto ()
{
    // setto gli attributi nel caso l'archivio sia stato selezionato
    $sqlWhere = "";
    $option0 = "<option value='0'/>";

    if (isset($_POST["idConto"])) {
        $sqlWhere = "WHERE a.id_conto = " . $_POST["idArchivio"];
        // $disabled = "disabled";
        $option0 = "";
    }

    // recupero i dati dell'archivio
    $conn = connetti();

    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }

    $sql = "SELECT a.id_conto, a.nr_conto, b.nome_cliente FROM t_conto a LEFT JOIN t_archivio b 
            ON a.id_archivio = b.id_archivio " . $sqlWhere;

            $result = $conn->query($sql);

            // Genero la stringa html
            $str_select = "<select id='conto' name='conto' class=form-control>";
            $str_select .= $option0;

            // Trasformo i risultati della query in associativi
            while ($row = $result->fetch_assoc()) {
                $str_select .= "<option value='" . $row["id_conto"] . "'>" .
                        $row["nome_cliente"] . " - " . $row["nr_conto"] . "</option>";

                       /* if (isset($_POST["idArchivio"])) {
                            global $listaConti, $lang;
                            $listaConti .= $lang['CONTO_DELL_ARCHIVIO'] . $row["nome_cliente"] .
                            " - " . $row["nome"];
                        }*/
            }
            $str_select .= "</select>";

            $conn->close();

            return $str_select;
}
// /

require_once ("leftPanel.php");
?>



<!-- /. NAV SIDE  -->
<div id="page-wrapper">
	<div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<h1 class="page-head-line"><?php echo $lang['FIDO_HEADLINE']; ?></h1>
			</div>
		</div>
		<!-- /. ROW  -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
                           <?php echo $lang['FIDO_INSERIMENTO']; ?>
                        </div>
					<div class="panel-body">
						<form method="post"
							action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<input type="hidden" name="idFido" id="idFido">
							
							<div class="form-group">
								<label><?php echo $lang['FIDO_CONTO']; ?></label>
								<?php echo create_dropdown_conto(); ?> 
								<p class="help-block" style="color: red;"><?php echo $contoErr;?></p>

							</div>
							
							<div class="form-group">
								<label><?php echo $lang['FIDO_DATA']; ?></label> <input
									class="form-control" type="text" id="dataAffidamento"
									name="dataAffidamento">
								 <p class="help-block" style="color:red;"><?php echo $dataAffErr;?></p>  
							</div>
							<div id="scaglioneContainer" style="overflow: hidden; margin-bottom: 15px;">
							</div>
							<button type="submit" id="insertEdit" name="inserisci" value="0"
								class="btn btn-primary"><?php echo $lang['BUTTON_INSERISCI']; ?></button>
							<button id="aggiungiSca" name="aggiungiSca"
								class="btn btn-primary" value=0 onClick="javascript:return aggiungiScaglione();"><?php echo $lang['BUTTON_AGGIUNGI_SCAGLIONE']; ?></button>
							<button id="rimuoviSca" name="rimuoviSca"
								class="btn btn-primary" onClick="javascript:return rimuoviScaglione();"><?php echo $lang['BUTTON_RIMUOVI_SCAGLIONE']; ?></button>
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
                            <?php echo $listaFidi; ?>
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
setActiveMenu("fido");

$(document).ready(function(){
	
    $.datepicker.setDefaults({dateFormat: 'dd/mm/yy'});
	$('#dataAffidamento').datepicker();
	$('#rimuoviSca').css("display","none");
});

function aggiungiScaglione(){
	var num = parseInt($('#aggiungiSca').val()) + 1;
	var elementToAppend = '<div id="scaglioneElement'+num+'"> '
	+'<div style="width: 55%; margin-left: 122px; margin-right: 0px; float: left;"> '
	+'	<label><?php echo $lang['FIDO_IMPORTO']; ?></label> '
	+' </div> '
	+' <div style="width: 30%; margin-right: 0px; float: right;"> '
	+' 	<label><?php echo $lang['FIDO_DIF']; ?></label> '
	+' </div> '
	+' <label style="float: left; width: 120px"><?php echo $lang['FIDO_SCAGLIONE'];?>' + num+'</label> '
	+' <input style="width: 55%; margin-right: 0px; float: left;" '
	+' 	class="form-control" type="number" id="importo'+num+'" name="importo'+num+'"> '
	+' <input style="width: 30%; margin-right: 0px; float: right;" '
	+' 	class="form-control" type="number" id="dif'+num+'" name="dif'+num+'"> '
	+'</div>';

	$("#scaglioneContainer").append(elementToAppend);
	$('#aggiungiSca').val(num);

	$('#rimuoviSca').css("display",true);
	return false;
}

function rimuoviScaglione(){
	var num = parseInt($('#aggiungiSca').val());
	if(num > 0){
		$("#scaglioneElement" + num).remove();
		$('#aggiungiSca').val(--num);
	} 

	if (num == 0) {
		$('#rimuoviSca').css("display","none");
	}
	return false;
}

</script>
</body>
</html>
