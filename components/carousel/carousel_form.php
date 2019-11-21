<?php 
	chdir("../../");
	$directory_in = "../../";
?>
<?php $title = "Слайдшоу" ?>
<?php include "layout/head.php" ?>

<?php 
if(!isset($_SESSION["email"]) || isset($_SESSION["email"]) && $_SESSION["role"] != "admin") {
	header("location: ../../index.php");
} else {
?>

<?php include "components/db_config.php" ?>

<?php
if(isset($_POST["add"])) {
	$title = trim($_POST["title"]);
	$description = trim($_POST["description"]);
	
	$target_dir = "components/carousel/images/";
	$target_file = $target_dir . basename($_FILES["image"]["name"]);

	if (file_exists($target_file)) {
		$GLOBALS["error_message"] = "Файл с такова име вече съществува;";
	} else {
		if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
			$image = $_FILES["image"]["name"];
			
			$sql = "INSERT INTO carousel (title, description, image) VALUES ('$title', '$description', '$image')";
	
			if($db->query($sql)) {
				header("location: ../../index.php");
			} else {
				$GLOBALS["error_message"] = "Неуспешно добавяне на снимка;";
			}
		} else {
			$GLOBALS["error_message"] = "Неуспешно качване на файла;";
		}
	}
}

if(isset($_GET["edit"])) {
	$image = mysqli_fetch_array($db->query("SELECT * FROM carousel WHERE id='". $_GET["id"] ."'"));
	$img_title = $image["title"];
	$description = $image["description"];
}

if(isset($_GET["delete"])) {
	$image = mysqli_fetch_array($db->query("SELECT * FROM carousel WHERE id='". $_GET["id"] ."'"))["image"];
	unlink("components/carousel/images/". $image);
	
	$sql = "DELETE FROM carousel WHERE id='". $_GET["id"] ."'";
	$db->query($sql);
	header("location: ../../index.php");
}

if(isset($_POST["edit"])) {
	$title = trim($_POST["title"]);
	$description = trim($_POST["description"]);
	
	$sql = "UPDATE carousel SET title='$title', description='$description'";

	if($db->query($sql)) {
		header("location: ../../index.php");
	} else {
		$GLOBALS["error_message"] = "Неуспешно редактиране на снимка;";
	}
}
?>

<div class="container content">
	<h1>Изображение за слайдшоуто</h1>

	<?php include "components/alert_messages/alert_messages.php" ?>
	
	<form method="POST" enctype="multipart/form-data">
	
		<?php if(!isset($_GET["edit"])) { ?>
			<div class="form-group">
				<label for="image">Снимка</label>
				<input type="file" class="form-control-file" id="image" name="image">
			</div>
		<?php } ?>
		
		<div class="form-group">
			<label for="title">Заглавие</label>
			<input type="text" class="form-control" id="title" name="title" value="<?php if(isset($img_title)) echo $img_title ?>">
		</div>
		
		<div class="form-group">
			<label for="description">Кратко описание</label>
			<input type="text" class="form-control" id="description" name="description" value="<?php if(isset($description)) echo $description ?>">
		</div>
		
		<?php 
			if(isset($_GET["edit"])) { 
				echo '<button class="form-control btn btn-warning" name="edit">Редактирай</button>';
			} else {
				echo '<button class="form-control btn btn-primary" name="add">Добави</button>';
			}
		?>
		
	</form>
</div>

<?php include "layout/end_body.php" ?>

<?php } ?> <!-- проверката за логнат или администратор -->