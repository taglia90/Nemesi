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
					<i class="fa fa-desktop "></i><?php echo $lang['LEFT_MENU_HOME']; ?>
				</a>
			</li>
			<li><a href="archivio.php" id="archivio">
					<i class="fa fa-sign-in "></i><?php echo $lang['LEFT_MENU_ARCHIVIO']; ?>
				</a>
			</li>
			 <li>
                        <a href="#"><i class="fa fa-yelp "></i><?php echo $lang['LEFT_MENU_LINGUA']; ?> <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li>
                                <a href="index.php?lang=it"><i class="fa fa-coffee"></i>Italiano</a>
                            </li>
                            <li>
                                <a href="index.php?lang=en"><i class="fa fa-flash "></i>English</a>
                            </li>
                             <li>
                                <a href="index.php?lang=ru"><i class="fa fa-key "></i>Pусский</a>
                            </li>
                           
                        </ul>
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