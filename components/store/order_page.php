<?php
chdir('../../');
$directory_in = "../../"; // понеже сме в директория на вътре, сетваме тази променлива, за да включим после стиловете и да работят
?>

<!-- Връзваме към базата и взимаме продукта със съответното id -->
<?php include "components/db_config.php"; ?>

<?php
$title = "Преглед на поръчка";
include "layout/head.php";
?>

<?php 
if(!isset($_SESSION["email"]) || isset($_SESSION["email"]) && $_SESSION["role"] != "admin") {
	header("location: ../../index.php");
} else {
?>

<div class="container">

<?php
if(isset($_GET["order_id"])) {
	$order_id = $_GET["order_id"];
	$sql = "SELECT * FROM orders_view WHERE order_id='". $order_id ."' AND status!='cart'";
	$order = $db->query($sql);
	if($order->num_rows > 0) {
		$order = mysqli_fetch_array($db->query($sql));
	} else {
		echo "<script>window.location.reload(history.go(-1))</script>";
	}

	echo '
		<p>Поръчка номер: <em>'. $order["order_id"] .'</em></p>
		<p>Име: <em>'. $order["user_name"] .'</em></p>
		<p>Телефон: <em>'. $order["user_phone"] .'</em></p>
		<p>E-mail: <em>'. $order["user_email"] .'</em></p>
		<p>Статус на поръчката: <em>'. $order["status"] .'</em></p>
	';
	
	if(isset($order)) {
		echo '
			<table class="table table-striped">
			<thead>
			<tr>
				<th scope="col">Продукт</th>
				<th scope="col">Количество</th>
				<th scope="col">Цена</th>
				<th scope="col">Действия</th>
			</tr>
			</thead>
			<tbody>
		';
		
		$product_id_list = explode(",", $order["product_id_list"]);
		$product_title_list = explode(",", $order["product_title_list"]);
		$quanity_list = explode(",", $order["quanity_list"]);
		$price_list = explode(",", $order["price_list"]);
		$total_price_list = explode(",", $order["total_price_list"]);
		$grand_total_price = $order["grand_total_price"];
		
		for($i=0; $i<count($product_id_list); $i++) {
			echo '
				<tr>
					<td><a href="product_page.php?product='. $product_id_list[$i] .'">'. $product_title_list[$i] .'</a></td>
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
				<td style="text-align: right"><strong>Общо</strong></td>
				<td><strong>'. $grand_total_price .' лв</strong></td>
				<td></td>
			</tr>
		';
		
		echo '
			</tbody>
			</table>
			<a href="manage_orders.php?finish_order='. $order["order_id"] .'">Завършена</a>
		';
	} else {
		echo 'Няма избрана поръчка';
	}

	if(isset($_GET["remove"])) {
		$product_id = $_GET["remove"];
		$sql = "DELETE FROM order_products WHERE order_id='". $order_id ."' AND product_id='". $product_id ."'";
		$db->query($sql);
		echo "<script>location.href=location.href.split('?')[0]</script>";
	}

	if(isset($_POST["change_quanity"])) {
		$product_id = $_POST["product_id"];
		$quanity = $_POST["quanity"];
		
		$sql = "UPDATE order_products SET quanity='". $quanity ."' WHERE order_id='". $order_id ."' AND product_id='". $product_id ."'";
		$db->query($sql);
		echo "<script>location.href=location.href</script>";
	}
} else {
	echo "<script>window.location.reload(history.go(-1))</script>";
}
?>

</div>


<?php include "layout/end_body.php"; ?>

<?php } ?> <!-- проверката за логнат или администратор -->