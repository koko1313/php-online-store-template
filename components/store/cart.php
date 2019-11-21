<?php
chdir('../../');
$directory_in = "../../"; // понеже сме в директория на вътре, сетваме тази променлива, за да включим после стиловете и да работят
?>

<!-- Връзваме към базата и взимаме продукта със съответното id -->
<?php include "components/db_config.php"; ?>

<?php
$title = "Количка";
include "layout/head.php";
?>

<?php 
if(!isset($_SESSION["email"])) {
	header("location: ../../index.php");
} else {
?>

<div class="container">

<?php
if(isset($_GET["success_order"])){
	$GLOBALS["success_message"] = "Поръчката беше направена успешно;";
}
?>

<?php include "components/alert_messages/alert_messages.php" ?>

<?php
$sql = "SELECT * FROM cart_view WHERE user_email='". $_SESSION["email"] ."'";
$order = mysqli_fetch_array($db->query($sql));

if(isset($order)) {
	echo '
		<table class="table table-striped">
		<thead>
		<tr>
			<th scope="col">Продукт</th>
			<th scope="col">Единична цена</th>
			<th scope="col">Количество</th>
			<th scope="col">Цена</th>
			<th scope="col">Действия</th>
		</tr>
		</thead>
		<tbody>
	';
	
	$product_id_list = explode(",", $order["product_id_list"]);
	$product_title_list = explode(",", $order["product_title_list"]);
	$quanity_list = explode(",", $order["product_quanity_list"]);
	$price_list = explode(",", $order["product_price_list"]);
	$total_price_list = explode(",", $order["product_total_price_list"]);
	$grand_total_price = $order["grand_total_price"];
	
	for($i=0; $i<count($product_id_list); $i++) {
		echo '
			<tr>
				<td><a href="product_page.php?product='. $product_id_list[$i] .'">'. $product_title_list[$i] .'</a></td>
				<td>'. $price_list[$i] .' лв</td>
				<td>
					<form method="POST">
					<input type="hidden" name="product_id" value="'. $product_id_list[$i] .'">
					<input type="number" name="quanity" size="2" min="1" value='. $quanity_list[$i] .'>
					<button class="btn btn-link" type="submit" name="change_quanity">Промени</button>
					</form>
				</td>
				<td>'. $total_price_list[$i] .' лв</td>
				<td><a href="?remove='. $product_id_list[$i] .'">Премахни</а></td>
			</tr>
		';
	}
	
	echo '
		<tr>
			<td></td>
			<td></td>
			<td style="text-align: right"><strong>Общо</strong></td>
			<td><strong>'. $grand_total_price .' лв</strong></td>
			<td></td>
		</tr>
	';
	
	echo '
		</tbody>
		</table>
		<a href="make_order.php" class="btn btn-primary">Поръчай</a>
	';
} else {
	echo 'Нямате продукти в количката';
}

if(isset($_GET["remove"])) {
	$product_id = $_GET["remove"];
	$sql = "DELETE FROM cart WHERE product_id='". $product_id ."' AND user_email='". $_SESSION["email"] ."'";
	$db->query($sql);
	echo "<script>location.href=location.href.split('?')[0]</script>";
}

if(isset($_POST["change_quanity"])) {
	$product_id = $_POST["product_id"];
	$quanity = $_POST["quanity"];
	
	$sql = "UPDATE cart SET quanity='". $quanity ."' WHERE product_id='". $product_id ."' AND user_email='". $_SESSION["email"] ."'";
	$db->query($sql);
	echo "<script>location.href=location.href</script>";
}
?>

</div>


<?php include "layout/end_body.php"; ?>

<?php } ?> <!-- проверката за логнат или администратор -->