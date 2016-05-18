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
				<ul class="nav nav-tabs">
					<li role="presentation" class="active"><a href="#">Interessi creditori</a></li>
					<li role="presentation"><a href="#">Interessi debitori</a></li>
					<li role="presentation"><a href="#">Spese varie ed oneri positivi</a></li>
					<li role="presentation"><a href="#">Commissioni Max sospetto ed extra fidi</a></li>
					<li role="presentation"><a href="#">Altre opzioni</a></li>
				</ul>


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
