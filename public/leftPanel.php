<!-- /. NAV TOP  -->
<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">
			<li>
				<div class="user-img-div">
					<!--<img src="img/user.png" class="img-thumbnail" />-->
					<div class="inner-text">
						Jhon Deo Alex <br /> <small>Last Login : 2 Weeks Ago </small>
					</div>
				</div>

			</li>
			<li><a href="index.php" id="home">
					<i class="fa fa-desktop "></i>Home
				</a>
			</li>
			<li><a href="archivio.php" id="archivio">
					<i class="fa fa-sign-in "></i>Archivio
				</a>
			</li>
		</ul>
	</div>
</nav>

<!-- SCRIPT PER POSIZIONE IL MENU-->
<script>

function setActiveMenu(id){
	var stringID = "#"+id;
	$(stringID).addClass("active-menu");
}
// class="active-menu"
</script>