<?php

// load up your config file
// require_once("/path/to/resources/config.php");
// require_once(TEMPLATES_PATH . "/header.php");
require_once ("header.php");
require_once ("leftPanel.php");
?>

<!-- /. NAV SIDE  -->
<div id="page-wrapper">
	<div id="page-inner">
		<div class="row">
			<div class="col-md-12">


				<!-- <ul class="nav nav-tabs">
					<li role="presentation" class="active"><a href="#">Interessi
							creditori</a></li>
					<li role="presentation"><a href="#">Interessi debitori</a></li>
					<li role="presentation"><a href="#">Spese varie ed oneri positivi</a></li>
					<li role="presentation"><a href="#">Commissioni Max sospetto ed
							extra fidi</a></li>
					<li role="presentation"><a href="#">Altre opzioni</a></li>
				</ul> -->




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

				<div class="tab-content">

					<div id="sectionA" class="tab-pane fade in active">

						<p>Section A content…</p>

					</div>

					<div id="sectionB" class="tab-pane fade">

						<p>Section B content…</p>

					</div>

					<div id="sectionC" class="tab-pane fade">

						<p>Section C content…</p>

					</div>


					<div id="sectionD" class="tab-pane fade">

						<p>Section D content…</p>

					</div>

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
