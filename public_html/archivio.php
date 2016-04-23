<?php
require_once ("header.php");

// VARIABILI DELLA PAGINA
$nameErr = $bancaErr = "";
$queryResult = "";
$editPage = false;

// INSERIMENTO O MODIFICA ARCHIVIO
if (isset($_POST['inserisci'])) {
    // isEdit = true se è una pagina di modifica
    $isEdit = $_POST['inserisci'];
    
    $name = trim($_POST["nome_cliente"]);
    $nuovaBanca = trim($_POST["nuovaBanca"]);
    $descrizione = trim($_POST["descrizione"]);
    if (isset($_POST["banca"])) { // questo è necessario per la select disabled
        $banca = $_POST["banca"];
    } else {
        $banca = 0;
    }
    $inserisci = true;
    
    if (empty($name)) {
        $nameErr = "Campo Obbligatorio";
        $inserisci = false;
    }
    
    if ($banca == 0 && empty($nuovaBanca)) {
        $bancaErr = "Campo Obbligatorio";
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
        $sql2 = "";
        
        if ($banca != 0) { // Caso in cui la banca esiste
            
            if ($isEdit) {
                $sql = "UPDATE t_archivio SET 
                        nome_cliente = '" . $name . "',
                        banca = '" . $banca . "', 
                        descrizione = '" . $descrizione . "'
                        WHERE id_archivio = ".$_POST["idArc"];
            } else {
            
                $sql = "INSERT INTO t_archivio (nome_cliente, banca, descrizione)
    			VALUES ('" . $name . "','" .
                         $banca . "','" . $descrizione . "')";
            }
            
            if ($conn->query($sql) === TRUE) {
                $queryResult = "Record inserito correttamente";
            } else {
                $queryResult = $conn->error;
            }
            
            $conn->close();
        } else {
            // caso nuova banca: inserisco la nuova banca e l'archivio
            $sql = "INSERT INTO l_banca (nome)
			VALUES ('" . $nuovaBanca . "')";
            
            if ($conn->query($sql) === TRUE) {
                $queryResult = "Record inserito correttamente";
            } else {
                $queryResult = "Errore inserimento: " . $conn->error;
            }
            $conn->close();
            
            $conn = connetti();
            if ($conn->connect_error) {
                die("Errore di connessione: " . $conn->connect_error);
            }
            
            if ($isEdit) {
                $sql2 = "UPDATE t_archivio SET
                        nome_cliente = '" . $name . "',
                        banca = (SELECT id_banca FROM l_banca
    			                 WHERE nome = '" . $nuovaBanca . "'),
                        descrizione = '" . $descrizione . "'
                        WHERE id_archivio = ".$_POST["idArc"];
            } else {
            
                $sql2 = "INSERT INTO t_archivio (nome_cliente, banca, descrizione)
    			SELECT '" . $name . "',id_banca,'" .
                         $descrizione . "'
    			FROM l_banca
    			where nome = '" . $nuovaBanca . "'";
            }
            if ($conn->query($sql2) === TRUE) {
                $queryResult = "Record inserito correttamente";
            } else {
                $queryResult = "Errore inserimento: " . $conn->error;
            }
            $conn->close();
        }
    }
}
// /

// CREA LA TABLE DELL'ARCHIVIO
function draw_table ()
{
    
    // recupero i dati dell'archivio
    $conn = connetti();
    
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "SELECT a.id_archivio, a.nome_cliente, b.nome, a.descrizione
			FROM t_archivio a
			LEFT JOIN l_banca b 
			ON a.banca = b.id_banca 
			order by 1";
    $result = $conn->query($sql);
    
    // Genero la stringa html
    $str_table = "<table class='table table-hover' id='archivioTable'>
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
    
    // Trasformo i risultati della query in associativi
    while ($row = $result->fetch_assoc()) {
        $str_table .= "<tr>
						<td>" . $row["id_archivio"] . "</td>
					  	<td>" . $row["nome_cliente"] . "</td> 
						<td>" . $row["nome"] . "</td>
						<td>" . $row["descrizione"] . "</td>
						<td><form method='post' action='conto.php'>
							<button type='submit' name='idArchivio' value='" .
                 $row["id_archivio"] . "'
							class='btn btn-primary'>
							<i class='fa fa-search-plus'></i>
						  </button></form></td>
						<td><form method='post' action='archivio.php'>
							<button type='submit'  name='editArchivio'
							class='btn btn-primary' value='" .
                 $row["id_archivio"] ."'>
							<i class='fa fa-wrench'></i>
						  </button></form></td>
						<td><form method='post' action='archivio.php'>
							<button type='submit' name='deleteRow'
							class='btn btn-primary' value='" .
                 $row["id_archivio"] . "'><i class='fa fa-trash'></i>
						  </button></form></td>
					  </tr>";
    }
    $str_table .= "</tbody></table>";
    
    $conn->close();
    
    return $str_table;
}
// /
function create_dropdown ()
{
    // recupero i dati dell'archivio
    $conn = connetti();
    
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "SELECT id_banca, nome FROM l_banca";
    $result = $conn->query($sql);
    
    // Genero la stringa html
    $str_select = "<select id='banca' name='banca' class=form-control>
				  <option value=0/>";
    
    // Trasformo i risultati della query in associativi
    while ($row = $result->fetch_assoc()) {
        $str_select .= "<option value='" . $row["id_banca"] . "'>" . $row["nome"] .
                 "</option>";
    }
    $str_select .= "</select>";
    
    $conn->close();
    
    return $str_select;
}

if (isset($_POST['deleteRow'])) {
    
    $conn = connetti();
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "DELETE FROM t_archivio
			WHERE id_archivio =" . $_POST['deleteRow'] . " ";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Eliminazione effettuata correttamente');</script>";
    } else {
        echo "<script>alert('Errore eliminazione: " . $conn->connect_error .
                 "');</script>";
    }
    
    $conn->close();
}

// PASSA I CAMPI DA INIZIALIZZARE
if (isset($_POST['editArchivio'])) {
    
    $conn = connetti();
    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM t_archivio
			WHERE id_archivio =" . $_POST['editArchivio'] .
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
				<h1 class="page-head-line"><?php echo $lang['ARCHIVIO_HEADLINE']; ?></h1>
			</div>
		</div>
		<!-- /. ROW  -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
                           <?php echo $lang['ARCHIVIO_INSERIMENTO']; ?>
                        </div>
					<div class="panel-body">
						<form method="post"
							action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<input type="hidden" name="idArc" id="idArc">
							<div class="form-group">
								<label><?php echo $lang['ARCHIVIO_NOME_CLIENTE']; ?></label> <input
									class="form-control" type="text" name="nome_cliente"
									id="nome_cliente">
								<p class="help-block" style="color: red;"><?php echo $nameErr;?></p>
							</div>
							<div>
								<label><?php echo $lang['ARCHIVIO_BANCA']; ?></label>
							</div>
							<div class="input-group">									
									<?php echo create_dropdown(); ?> 
									 <span class="form-group input-group-btn">
									<button type="button" class="btn btn-primary"
										onClick="showNuovaBanca();">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</span>
							</div>
							<div>
								<p class="help-block" style="color: red;"><?php echo $bancaErr;?></p>
							</div>
							<div id="nuovaBancaDiv" style="display: none;">
								<div>
									<label><?php echo $lang['ARCHIVIO_NUOVA_BANCA']; ?></label>
								</div>
								<div class="input-group">
									<input class="form-control" type="text" name="nuovaBanca"
										id="nuovaBanca"> <span class="form-group input-group-btn">
										<button type="button" class="btn btn-primary"
											onClick="hideNuovaBanca();">
											<i class="glyphicon glyphicon-minus"></i>
										</button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label><?php echo $lang['ARCHIVIO_DESCRIZIONE']; ?></label> <input
									class="form-control" type="text" name="descrizione"
									id="descrizione">
							</div>
							<div>&nbsp;</div>
							<button type="submit" id="insertEdit" name="inserisci"
								value="false" class="btn btn-primary"><?php echo $lang['BUTTON_INSERISCI']; ?></button>
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
                            <?php echo $lang['ARCHIVIO_LISTA_ARCHIVIO']; ?>
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
    <?php
    // INIZIALIZZO I CAMPI SE SONO IN EDIT
    if ($editPage) {
        $row = $result->fetch_assoc();
        echo "$('#nome_cliente').val('" . $row["nome_cliente"] . "');
	              $('#banca').val(" . $row["banca"] . ");   
	              $('#descrizione').val('" .
                 $row["descrizione"] . "');
	              $('#idArc').val('" . $row["id_archivio"] . "');
	              $('#insertEdit').val('true');
	              $('#insertEdit').html('" .
                 $lang['BUTTON_MODIFICA'] . "');";
    }
    ?>
});


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
