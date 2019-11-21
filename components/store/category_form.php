<?php
chdir('../../');
$directory_in = "../../"; // понеже сме в директория на вътре, сетваме тази променлива, за да включим после стиловете и да работят
?>

<?php
$title = "Категория";
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
	$category_id = $_GET["category"];
	
	$sql = "SELECT category FROM categories WHERE id='$category_id'";
	$result = $db->query($sql);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_array($result)) {
			$category = $row["category"];
		}
	}
} else {
	$category = "";
}
?>

<?php
if(isset($_POST["add"])) {
	$category = trim($_POST["category"]);

	$sql = "INSERT INTO categories (category) VALUES ('$category')";
	
	if($db->query($sql)) {
		$GLOBALS["success_message"] = "Успешно добавяне на категория;";
		$category = "";
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-2)); </script>";
	} else {
		$GLOBALS["error_message"] = "Неуспешно добавяне на категория;";
	}	
}

if(isset($_POST["edit"])) {
	$category = trim($_POST["category"]);

	$sql = "UPDATE categories SET category='". $category ."' WHERE id='". $category_id ."'";
	
	if($db->query($sql)) {
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-2)); </script>";
	} else {
		$GLOBALS["error_message"] = "Неуспешно редактиране;";
	}
}

if(isset($_GET["delete"])) {
	$sql = "DELETE FROM categories WHERE id='". $category_id ."'";
	
	if($db->query($sql)) {
		echo "<script> document.cookie='addedEditedOrDeleted=true;path=/;'; window.location.reload(history.go(-2)); </script>";
	} else {
		$GLOBALS["error_message"] = "Неуспешно изтриване;";
	}
}
?>

<div class="container">

<?php include "components/alert_messages/alert_messages.php"; ?>

<form id="form" method="POST">
	<div class="form-group">
		<label for="category">Категория</label>
		<input type="text" class="form-control" id="category" name="category" value="<?php echo $category ?>">
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
	if ($("#category").val().trim() != "") {
		$("#submit").prop("disabled", false);
	} else {
		$("#submit").prop("disabled", true);
	}
}

</script>

<?php include "layout/end_body.php" ?>

<?php } ?> <!-- проверката за логнат или администратор -->