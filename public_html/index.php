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
				<h1 class="page-head-line">HOMEPAGE</h1>
				<h1 class="page-subhead-line"><?php echo $lang['HOME_PROVA']; ?></h1>

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
