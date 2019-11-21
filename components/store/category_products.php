<?php include "components/db_config.php" ?>

<?php
$sql = "SELECT * FROM products_view WHERE category_id=". $category;
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
		echo '
			<div class="product" name="'. $row["title"] .'">
				<img src="data:image/jpeg;base64,'.base64_encode($row["image"]).'">
				
				<div>
					<h5>'. $row["title"] .'</h5>
					<p>'. $row["price"] .' лв</p>
					<a href="components/store/product_page.php?product='. $row["id"] .'" class="btn btn-primary">Отвори</a>
		';
		
		if(isset($_SESSION["email"]) && $_SESSION["role"] == "admin") {
			echo '
				<a href="components/store/product_form.php?edit=1&product='. $row["id"] .'" class="btn btn-warning">Редактирай</a>
				<a href="#" class="btn btn-danger" onClick="if(confirm(\'Изтриване?\')) location.href=\'components/store/product_form.php?delete=1&product='. $row["id"] .'\'">Изтрий</a>
			';
		}
		
		echo '
				</div>
			</div>
		';
    }
}
?>