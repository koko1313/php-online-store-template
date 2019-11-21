<?php
chdir('../../');
$directory_in = "../../"; // понеже сме в директория на вътре, сетваме тази променлива, за да включим после стиловете и да работят
?>

<?php
$title = "Продукт";
include "layout/head.php";
?>

<?php 
if(!isset($_SESSION["email"]) || isset($_SESSION["email"]) && $_SESSION["role"] != "admin") {
	header("location: ../../index.php");
} else {
?>

<?php include "components/db_config.php"; ?>

<?php
if (isset($_GET["edit"]) || isset($_GET["delete"])) {
	$product = $_GET["product"];
	
	$sql = "SELECT * FROM products_view WHERE id='$product'";
	$result = $db->query($sql);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_array($result)) {
			$id = $row["id"];
			$title = $row["title"];
			$price = $row["price"];
			$description = $row["description"];
			$category_id = $row["category_id"];
		}
	}
} else {
	$id = "";
	$title = "";
	$price = "";
	$description = "";
	$category_id = 1;
}
?>

<?php
if(isset($_POST["add"])) {
	$id = trim($_POST["id"]);
	$title = trim($_POST["title"]);
	$price = trim($_POST["price"]);
	$description = trim($_POST["description"]);
	$category_id = $_POST["category_id"];
	
	if(file_exists($_FILES["img_main"]["tmp_name"])) {
		$tmpname = $_FILES["img_main"]["tmp_name"];
		$tmp = addslashes(file_get_contents($tmpname));
	} else {
		$tmp = "";
	}
	
	$sql = "INSERT INTO products VALUES ('$id', '$title', '$price', '$description', '$category_id', '$tmp')";
	
	
	if($db->query($sql)) {
		if(file_exists($_FILES["img"]["tmp_name"][0])) {
			$filename = $_FILES["img"]["name"];
			$tmpname = $_FILES["img"]["tmp_name"];
			
			for ($i=0; $i<count($tmpname); $i++) {
				$name = addslashes($filename[$i]);
				$tmp = addslashes(file_get_contents($tmpname[$i]));
				$sql = "INSERT INTO images (product_id, name, image) VALUES ('$id', '$name', '$tmp')";
				$db->query($sql);
			}
		}
		
		$id = $title = $price = $description = ""; // нулираме променливите
		$category_id = 1;
		$GLOBALS["success_message"] = "Успешно добавяне на продукт;";
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-2)); </script>";
	} else {
		$GLOBALS["error_message"] = "Неуспешно добавяне на продукт;";
	}
}

if(isset($_POST["edit"])) {
	$id = trim($_POST["id"]);
	$title = trim($_POST["title"]);
	$price = trim($_POST["price"]);
	$description = trim($_POST["description"]);
	$category_id = $_POST["category_id"];
	
	if(file_exists($_FILES["img_main"]["tmp_name"])) {
		$tmpname = $_FILES["img_main"]["tmp_name"];
		$tmp = addslashes(file_get_contents($tmpname));
		$sql = "UPDATE products SET id='".$id."', title='".$title."', price='".$price."', description='".$description."', category_id='".$category_id."', image='". $tmp ."' WHERE id='". $product ."'";
	} else
	
	$sql = "UPDATE products SET id='".$id."', title='".$title."', price='".$price."', description='".$description."', category_id='".$category_id."' WHERE id='". $product ."'";
	
	if($db->query($sql)) {
		if(file_exists($_FILES["img"]["tmp_name"][0])) {
			$filename = $_FILES["img"]["name"];
			$tmpname = $_FILES["img"]["tmp_name"];
			
			for ($i=0; $i<count($tmpname); $i++) {
				$name = addslashes($filename[$i]);
				$tmp = addslashes(file_get_contents($tmpname[$i]));
				$sql = "INSERT INTO images (product_id, name, image) VALUES ('$id', '$name', '$tmp')";
				$db->query($sql);
			}
		}
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-2)); </script>";
	} else {
		$GLOBALS["error_message"] = "Неуспешно редактиране;";
	}
}

if(isset($_GET["delete"])) {
	$sql = "DELETE FROM products WHERE id=". $product;
	
	if($db->query($sql)) {
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-1)); </script>";
	} else {
		$GLOBALS["error_message"] = "Неуспешно изтриване;";
	}
}

if(isset($_GET["delete_img"])) {
	$img_name = $_GET["delete_img"];
	$sql = "DELETE FROM images WHERE name='". $img_name. "'";
	
	if($db->query($sql)) {
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-1)); </script>";
	} else {
		$GLOBALS["error_message"] = "Неуспешно изтриване;";
	}
}
?>

<div class="container">

<?php include "components/alert_messages/alert_messages.php"; ?>

<form id="form" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label for="id">Артикул номер</label>
		<input type="text" class="form-control" id="id" name="id" value="<?php echo $id ?>">
	</div>
	
	<div class="form-group">
		<label for="title">Име на продукта</label>
		<input type="text" class="form-control" id="title" name="title" value="<?php echo $title ?>">
	</div>
	
	<div class="form-group">
		<label for="price">Цена</label>
		<div class="input-group">
			<input type="text" class="form-control" id="price" name="price" value="<?php echo $price ?>">
			<div class="input-group-append">
				<span class="input-group-text" id="basic-addon2">лв.</span>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label for="description">Описание</label>
		<textarea class="form-control" id="description" name="description"><?php echo $description ?></textarea>
		<!--
		<script>
		$('#description').froalaEditor();
		</script>
		-->
	</div>
	
	<div class="form-group">
		<label for="category_id">Категория</label>
		<select class="form-control" id="category_id" name="category_id">
			<?php	
			$sql = "SELECT * FROM categories";
			$result = $db->query($sql);
			
			if ($result->num_rows > 0) {
				while($row = mysqli_fetch_array($result)) {
					
					$selected = "";
					if($row["id"] == $category_id) $selected = "selected";
					
					echo '<option value="'. $row["id"] .'"'. $selected .'>'. $row["category"] .'</option>';
				}
			}
			?>
		</select>
	</div>
	
	<div class="form-group">
		<label for="main_picture">Главна снимка</label>
		<input type="file" class="form-control-file" id="main_picture" name="img_main">
	</div>
	
	<div class="form-group">
		<label for="pictures">Снимки</label>
		<input type="file" class="form-control-file" id="pictures" name="img[]" multiple>
	</div>
	
	<?php
	if (isset($_GET["edit"])) {
		echo '<button class="form-control btn btn-warning" id="submit" type="submit" name="edit" disabled>Редактирай</button>';
	} else {
		echo '<button class="form-control btn btn-primary" id="submit" type="submit" name="add" disabled>Добави</button>';
	}
	?>
	
</form>

</div>

<script>
validate();

$("#form").keyup(function() {
	validate();
});

function validate() {
	if (
		$("#id").val().trim() != "" &&
		$("#title").val().trim() != "" &&
		$("#price").val().trim() != "" &&
		$("#description").val().trim() != ""
	) 
	{
		$("#submit").prop("disabled", false);
	} else {
		$("#submit").prop("disabled", true);
	}
}

// когато се зареди документа, селектира съответната категория ако има подаден параметър
$(document).ready(function() {
	var url = new URL(location.href);
	var category = url.searchParams.get("category");
	if(category) $("#category_id").val(category);
});
</script>

<?php include "layout/end_body.php" ?>

<?php } ?> <!-- проверката за логнат или администратор -->