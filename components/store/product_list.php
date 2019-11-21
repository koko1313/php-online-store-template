<script src="components/store/edited_or_deleted_product.js"></script>

<?php include "components/db_config.php" ?>
<div class="container">
<div class="row">

	<ul class="list-group list-group-flush col-md-2">
		<?php	
		$sql = "SELECT * FROM categories";
		$result = $db->query($sql);

		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_array($result)) {
				// в id се пише името на категорията, както е името на файла с категорията
				echo '
					<li class="list-group-item">
						<a href="#" class="category-button" id="'. $row["id"] .'">'. $row["category"] .'</a>
				';
				
				if(isset($_SESSION["email"]) && $_SESSION["role"] == "admin") {
					echo '
						<a href="components/store/category_form.php?edit=1&category='. $row["id"] .'"><i class="fas fa-edit"></i></a>
						<a href="#" id="product1" onClick="if(confirm(\'Изтриване?\')) location.href=\'components/store/category_form.php?delete=1&category='. $row["id"] .'\'"><i class="fas fa-trash-alt"></i></a>
					';
				}
				
				echo '	
					</li> 
				';
				 
			}
		}
		?>
	</ul>
	


	<div class="store-grid col-md-10">
		
		<div class="row">
			<div class="col-md-8">
				<!-- ако е администратор -->
				<?php if(isset($_SESSION["email"]) && $_SESSION["role"] == "admin") { ?>
					<a href="components/store/category_form.php">Добави Категория</a> |
					<a href="components/store/product_form.php
						<?php
							if(isset($_GET["category"])) echo "?category=". $_GET["category"];
						?>
					">Добави продукт</a>
				<?php } ?> <!-- край на ако е администратор -->
			</div>
			
			<div class="input-group col-md-4">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="fas fa-search"></i></div>
				</div>
				<input type="text" id="search" class="form-control" placeholder="Търси в категорията...">
			</div>
		</div>
		
		<div class="row">
			<div class="col-md">
			<?php 
				// взима параметъра category и include-ва съответната категория
				if(isset($_GET['category'])) {
					$category = $_GET['category'];
					include "category_products.php";
				} else {
					include "featured.php";
				}
			?>
			</div>
		</div>
	
	</div>

</div>
</div>

<script>
// задава параметър в URL за категорията
function setCategoryParameter(category, button) {
	location.href = location.href.split("?")[0] + "?category=" + category;
}

var category_buttons = $(".category-button");

// обхожда всички бутони и им слага допълнителни атрибути
for (var i=0; i<category_buttons.length; i++) {
	var button = $(category_buttons[i]);
	button.attr("onClick", "setCategoryParameter(this.id)");
}

// търсене
$("#search").on('keyup', function() {
	var search = $("#search").val();
	var products = $(".product");
	
	if (search != "") {
		for(var i=0; i<products.length; i++) {
			var keywords = $(products[i]).attr("name");
			
			if(keywords.toLowerCase().includes(search.toLowerCase())) {
				//$(products[i]).css("display", "inline-block");
				$(products[i]).fadeIn();
			} else {
				//$(products[i]).css("display", "none");
				$(products[i]).fadeOut();
			}
		}
	} else {
		for(var i=0; i<products.length; i++) {
			$(products[i]).fadeIn();	
		}
	}
});
</script>

<style>
.store-grid {
	text-align: center;
}

.product {
	position: relative;
	display: inline-block;
	width: 288px;
	height: 400px;
	overflow: hidden;
	margin: 5px;
	border: 1px solid gray;
	border-radius: 5px;
	padding: 2px;
	box-shadow: 3px 3px 5px grey;
}

.product img {
	max-width: 100%;
	max-height: 250px;
	width: auto;
	height: auto;
}

.product div {
	position: absolute;
	bottom: 5px;
	width: 100%;
}
</style>