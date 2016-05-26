<?php
require_once ("header.php");

// VARIABILI DELLA PAGINA
$listaFidi = $lang['FIDO_LISTA_FIDI'];
$queryResult = "";

// INSERIMENTO O MODIFICA FIDO
if (isset($_POST['inserisci'])) {}
// /

// CREA LA TABLE DEL FIDO
function draw_table ()
{}
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
								<label><?php echo $lang['FIDO_DATA']; ?></label> <input
									class="form-control" type="text" id="dataAffidamento"
									name="dataAffidamento">
								<!-- <p class="help-block" style="color:red;"><?php echo $dataApErr;?></p>  -->
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
