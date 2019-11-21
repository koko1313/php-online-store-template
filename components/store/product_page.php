<script src="edited_or_deleted_product.js"></script>

<?php
chdir('../../');
$directory_in = "../../"; // понеже сме в директория на вътре, сетваме тази променлива, за да включим после стиловете и да работят
?>

<?php
	if(isset($_GET['product'])) {
		$product = $_GET['product'];
	}
?>

<!-- Връзваме към базата и взимаме продукта със съответното id -->
<?php include "components/db_config.php"; ?>
<?php 
	$sql = "SELECT * FROM products_view WHERE id=". $product;
	$row = mysqli_fetch_array($db->query($sql));
	// след изтриване от страницата на продукта, като се отвори страницата и няма вече такова id, пренасочва назад
	if (!isset($row)) { 
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-1)); </script>";
	}
?>

<?php
$title = $row["title"];
include "layout/head.php";
?>

<?php 
if(isset($_POST["addToCart"])) {
	if(isset($_SESSION["email"])) {
		$product_id = $_POST["product_id"];
		$quanity = $_POST["quanity"];
		$user_email = $_SESSION["email"];
		
		$sql = "INSERT INTO cart VALUES ('$user_email', '$product_id', '$quanity')";
		if($db->query($sql)) {
			$GLOBALS["success_message"] = "Добавено в количката;";
		} else 
		if (mysqli_errno($db) == 1062) { // ако вече го има продукта в количката
			$sql = "UPDATE cart SET quanity=quanity+1 WHERE user_email='". $user_email ."' AND product_id='". $product_id ."'";
			if($db->query($sql)) {
				$GLOBALS["success_message"] = "Добавихте продукта още веднъж в количката;";
			} else {
				$GLOBALS["error_message"] = "Неуспешно добавяне в количката;";
			}
		} else {
			$GLOBALS["error_message"] = "Неуспешно добавяне в количката;";
		}
		
	} else {
		$GLOBALS["error_message"] = "Влезте в профила си, за да пазарувате;";
	}
}
?>

<div class="container">
	<div class="row">
		<div class="product-image-gallery col-md-4">
			<?php
				echo '<img class="product-img" src="data:image/jpeg;base64,'.base64_encode($row["image"]).'" onClick="showBigImageModal(this.src)">' 
			?>

			<?php
			$sql_images = "SELECT * FROM images WHERE product_id=".$row["id"];
			$result_images = $db->query($sql_images);
			if ($result_images->num_rows > 0) {
				while($row_images = mysqli_fetch_array($result_images)) {
					echo '
					<div class="thumbnail">
					';	
						
					if(isset($_SESSION["email"]) && $_SESSION["role"] == "admin") {						
						echo '
							<a href="#" onClick="if(confirm(\'Изтриване?\')) location.href=\'product_form.php?delete_img='. $row_images["name"] .'\'"><i class="fas fa-ban remove-img-icon" id="'. $row_images["name"] .'"></i></a>
						';
					}
					
					echo '
						<img class="img-thumbnail" src="data:image/jpeg;base64,'.base64_encode($row_images["image"]).'" onClick="showBigImageModal(this.src)">
					</div>
					';
				}
			}
			?>
		</div>
		
		<div class="product-buy-information col-md-8">
			<?php include "components/alert_messages/alert_messages.php" ?>
			
			<h3><?php echo $row["title"] ?></h3>
			<p>Артикул номер: <?php echo $row["id"] ?></p>
			<p class="product-price"><?php echo $row["price"] ?> лв</p>
			<p>Категория: <?php echo $row["category"] ?></p>
			<form method="POST">
				<input type="hidden" name="product_id" value="<?php echo $row["id"] ?>">
				<div class="form-group">
					<label for="quanity">Количество</label>
					<input class="form-control" id="quanity" name="quanity" type="number" value="1" min="1">
				</div>
				<button class="btn btn-primary" type="submit" name="addToCart" id="<?php echo $row["id"] ?>">Добави в количката</button>
				
				<?php if(isset($_SESSION["email"]) && $_SESSION["role"] == "admin") { ?>
					<a href="product_form.php?edit=1&product=<?php echo $row["id"] ?>" class="btn btn-warning" id="product1">Редактирай</a>
					<a href="#" class="btn btn-danger" onClick="if(confirm('Изтриване?')) location.href='product_form.php?delete=1&product= <?php echo $row["id"] ?> '">Изтрий</a>
				<?php } ?>
				
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md">
			<h3>Описание</h3>
			<p class="product-description">
				<?php echo htmlspecialchars_decode($row["description"]) ?>
			</p>
		</div>
	</div>
</div>


<div id="bigImageModal" class="modal">
	<span class="close">&times;</span>
	<img class="modal-content" id="modalImg">
</div>

<script>
// Get the modal
var modal = $('#bigImageModal');
var modalImg = $("#modalImg");

function showBigImageModal(src) {
	modal.css("display", "block");
    modalImg.attr("src", src);
}

$("#bigImageModal").click(function() {
	modal.css("display", "none");
});
</script>


<style>
.product-img {
	width: 100%;
	cursor: pointer;
}
.thumbnail {
	position: relative;
	display: inline-block;
	width: 100px;
	height: 100px;
	margin: 2px;
}
.img-thumbnail {
	width: 100%;
	height: 100%;
}
.img-thumbnail:hover {
	border-color: blue;
	cursor: pointer;
}
.remove-img-icon {
	position: absolute;
	top: 2px;
	right: 2px;
	color: red;
	background-color: white;
	border-radius: 10px;
}



/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 100; /* Sit on top */
	left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (Image) */
.modal-content {
    max-width: 100%;
	max-height: 100vh;
	width: auto;
	height: auto;
	position: absolute;
	top: 50%; left: 50%;
	transform: translate(-50%,-50%);
}


/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
	z-index: 101;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}
</style>

<?php include "layout/end_body.php"; ?>