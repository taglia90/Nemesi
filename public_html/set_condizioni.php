<?php

// load up your config file
// require_once("/path/to/resources/config.php");
// require_once(TEMPLATES_PATH . "/header.php");
require_once ("header.php");

// VARIABILI DELLA PAGINA
$setCondizioni = $lang['SET_CONDIZIONI'];
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
				<h1 class="page-head-line"><?php echo $lang['SET_HEADLINE']; ?></h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#sectionA">Interessi
							creditori</a></li>
					<li><a data-toggle="tab" href="#sectionB">Interessi debitori</a></li>
					<li><a data-toggle="tab" href="#sectionC">Spese varie ed oneri
							positivi</a></li>
					<li><a data-toggle="tab" href="#sectionD">Commissioni Max sospetto
							ed extra fidi</a></li>
					<li><a data-toggle="tab" href="#sectionE">Altre opzioni</a></li>
					<!-- <li class="dropdown"><a data-toggle="dropdown"
						class="dropdown-toggle" href="#">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a data-toggle="tab" href="#dropdown1">Dropdown 1</a></li>
							<li><a data-toggle="tab" href="#dropdown2">Dropdown 2</a></li>
						</ul></li> -->
				</ul>

				<!-- Interessi creditori -->
				<div class="tab-content">
					<div id="sectionA" class="tab-pane fade in active">

						<!-- /. ROW  -->
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-heading">
                           <?php echo $lang['CRED_INSERIMENTO']; ?>
                        </div>
									<div class="panel-body">
										<form method="post"
											action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<input type="hidden" name="idCondizione" id="idCondizione">

											<div class="form-group">
												<label><?php echo $lang['CRED_DATA']; ?></label> <input
													class="form-control" type="text" id="dataDecorrenza"
													name="dataDecorrenza">
												<!-- <p class="help-block" style="color:red;"><?php echo $dataApErr;?></p>  -->
											</div>


											<!-- Tasso 1 -->
											<div
												style="width: 55%; margin-left: 122px; margin-right: 0px; float: left;">
												<label><?php echo $lang['CRED_PERCENT']; ?></label>
											</div>
											<div style="width: 30%; margin-right: 0px; float: right;">

												<label><?php echo $lang['CRED_VAL_SCAGLIONE']; ?></label>
											</div>
											<label style="float: left; width: 120px"><?php echo $lang['CRED_TASSO']." "."1"; ?></label>

											<input style="width: 55%; margin-right: 0px; float: left;"
												class="form-control" type="number" id="perc1" name="perc1">
											<input style="width: 30%; margin-right: 0px; float: right;"
												class="form-control" type="number" id="valScaglione1"
												name="valScaglione1"> <br /> <br /> <br />

											<!-- Tasso 2 -->
											<div
												style="width: 55%; margin-left: 122px; margin-right: 0px; float: left;">
											</div>
											<div style="width: 30%; margin-right: 0px; float: right;"></div>
											<label style="float: left; width: 120px"><?php echo $lang['CRED_TASSO']." "."2"; ?></label>

											<input style="width: 55%; margin-right: 0px; float: left;"
												class="form-control" type="number" id="perc2" name="perc2">
											<input style="width: 30%; margin-right: 0px; float: right;"
												class="form-control" type="number" id="valScaglione2"
												name="valScaglione2"> <br /> <br />

											<!-- Tasso 3 -->
											<div
												style="width: 55%; margin-left: 122px; margin-right: 0px; float: left;">
											</div>
											<div style="width: 30%; margin-right: 0px; float: right;"></div>
											<label style="float: left; width: 120px"><?php echo $lang['CRED_TASSO']." "."3"; ?></label>

											<input style="width: 55%; margin-right: 0px; float: left;"
												class="form-control" type="number" id="perc3" name="perc3">
											<input style="width: 30%; margin-right: 0px; float: right;"
												class="form-control" type="number" id="valScaglione3"
												name="valScaglione3"> <br /> <br />

											<!-- Tasso oltre -->
											<div
												style="width: 55%; margin-left: 122px; margin-right: 0px; float: left;">
											</div>
											<div style="width: 30%; margin-right: 0px; float: right;"></div>
											<label style="float: left; width: 120px"><?php echo $lang['CRED_TASSO']." "."oltre"; ?></label>

											<input
												style="width: 55%; margin-right: 0px; float: left; display: none"
												class="form-control" type="number" id="percOltre"
												name="percOltre"> <input
												style="width: 30%; margin-right: 0px; float: right;"
												class="form-control" type="number" id="valScaglioneOltre"
												name="valScaglioneOltre"> <br /> <br /> <br />

											<!--  fine tassi -->
											<div>&nbsp;</div>
											<button type="submit" id="insertEdit" name="inserisci"
												value="0" class="btn btn-primary"><?php echo $lang['BUTTON_INSERISCI']; ?></button>
											<p class="help-block" style="color: green;"><?php echo $queryResult;?></p>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Interessi debitori -->
					<div id="sectionB" class="tab-pane fade">
						<p>Section B content…</p>
					</div>

					<!-- Spese varie ed oneri positivi -->
					<div id="sectionC" class="tab-pane fade">
						<p>Section C content…</p>
					</div>

					<!-- Commissioni Max sospetto ed extra fidi -->
					<div id="sectionD" class="tab-pane fade">
						<p>Section D content…</p>
					</div>

					<!-- Altre opzioni -->
					<div id="sectionE" class="tab-pane fade">
						<p>Section E content…</p>
					</div>

					<!-- <div id="dropdown1" class="tab-pane fade">
						<p>Dropdown 1 content…</p>
					</div>
					<div id="dropdown2" class="tab-pane fade">
						<p>Dropdown 2 content…</p>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
<?php require_once("footer.php");?>
<script>
	setActiveMenu("home");
</script>

</body>
</html>
