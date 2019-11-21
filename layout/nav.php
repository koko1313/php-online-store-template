<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	
	<a class="navbar-brand" href="#">Navbar</a>
	
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
	</button>
	
	<div class="collapse navbar-collapse" id="navbarNavDropdown">
		<ul class="navbar-nav mr-auto">
		
			<li class="nav-item ">
				<a class="nav-link" href="index.php">Начало</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link" href="page2.php">Контактна форма</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link" href="page3.php">Магазин</a>
			</li>
				
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown link</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					<a class="dropdown-item" href="#">Something</a>
					<a class="dropdown-item" href="index.php">Пак начало</a>
					<a class="dropdown-item" href="#">Something else here</a>
				</div>
			</li>
			
		</ul>
		
		<ul class="nav navbar-nav navbar-right">
			<?php if(!isset($_SESSION["email"])) { ?>
				<li class="nav-item">
					<a class="nav-link" href="page4.php">Регистрация</a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" href="page5.php">Вход</a>
				</li>
			<?php } else { ?>		
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION["email"] ?></a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<a class="dropdown-item" href="page6.php">Профил</a>
						<a class="dropdown-item" href="components/store/cart.php">Количка</a>
						
						<?php if($_SESSION["role"] == "admin") { ?>
							<a class="dropdown-item" href="components/store/manage_orders.php">Управление на поръчки</a>
						<?php } ?>
						
						<a class="dropdown-item" href="page5.php?logout=1">Изход</a>
					</div>
				</li>
			<?php } ?>
		</ul>
	
	</div>
</nav>

<script>
	var nav_items = $(".nav-item a"); // бутоните от първо ниво
	var page_name = "";
	
	if (document.location.href.match(/[^\/]+$/))
		page_name = document.location.href.match(/[^\/]+$/)[0].split("?")[0];

	// правим бутона активен в зависмост на коя страница се намираме
	if (page_name=="") page_name = "index.php";
	
	for(var i=0; i<nav_items.length; i++) {
		var button_href = nav_items.eq(i).attr("href");
		if(button_href == page_name) {
			nav_items.eq(i).addClass("active");
		}
	}
	
	// при наличие на dropdown меню - првим го active, ако елемент в него е избран
	var dropdown_nav_items = $(".dropdown-menu a");
	for(var i=0; i<dropdown_nav_items.length; i++) {
		if(dropdown_nav_items.eq(i).attr("class").indexOf("active") >= 0) {
			dropdown_nav_items.eq(i).parent().parent().addClass("active");
		}
	}
	
	// взимаме всички линкове от менюто и ако сме директория навътре ги променяме
	var anchors = $("nav a");
	for (var i = 0; i < anchors.length; i++) {
		var href = $(anchors[i]).attr("href");
		$(anchors[i]).attr("href", "<?php if(isset($directory_in)) echo $directory_in ?>" + href)
	}
</script>