<?php
chdir('../../');
$directory_in = "../../"; // понеже сме в директория на вътре, сетваме тази променлива, за да включим после стиловете и да работят
?>

<!-- Връзваме към базата и взимаме продукта със съответното id -->
<?php include "components/db_config.php"; ?>

<?php
$title = "Поръчки";
include "layout/head.php";
?>

<?php 
if(!isset($_SESSION["email"]) || isset($_SESSION["email"]) && $_SESSION["role"] != "admin") {
	header("location: ../../index.php");
} else {
?>

<div class="container">

<?php 
if(isset($_GET["finish_order"])) {
	$order_id = $_GET["finish_order"];
	$sql = "UPDATE orders SET status='finished' WHERE id='". $order_id ."'";
	$db->query($sql);
	echo "<script>location.href=location.href.split('?')[0]</script>";
}

if(isset($_GET["restore_order"])) {
	$order_id = $_GET["restore_order"];
	$sql = "UPDATE orders SET status='processing' WHERE id='". $order_id ."'";
	$db->query($sql);
	echo "<script>location.href=location.href.split('?')[0]</script>";
}

if(isset($_GET["delete_order"])) {
	$order_id = $_GET["delete_order"];
	$sql = "DELETE FROM orders WHERE id='". $order_id ."'";
	$db->query($sql);
	echo "<script>location.href=location.href.split('?')[0]</script>";
}
?>

<div id="order-tabs">
	<ul>
		<li><a href="#awaiting-delivery-tab">Очакват обработка</a></li>
		<li><a href="#finished-tab">Завършени</a></li>
	</ul>
	<div id="awaiting-delivery-tab">
		<?php
		$sql = "SELECT * FROM orders_view WHERE status='processing'";
		$orders = $db->query($sql);
		if($orders->num_rows > 0) {
			echo '
				<table class="table table-striped">
				<thead>
				<tr>
					<th scope="col">Поръчка</th>
					<th scope="col">Клиент</th>
					<th scope="col">Телефон</th>
					<th scope="col">E-mail</th>
					<th scope="col">Действия</th>
				</tr>
				</thead>
				<tbody>
			';
			
			while($row = mysqli_fetch_array($orders)) {
				echo '
					<tr>
						<td>'. $row["order_id"] .'</td>
						<td>'. $row["user_name"] .'</td>
						<td>'. $row["user_phone"] .'</td>
						<td>'. $row["user_email"] .'</td>
						<td>
							<a href="order_page.php?order_id='. $row["order_id"] .'">Прегледай</a> |
							<a href="?finish_order='. $row["order_id"] .'">Завършена</a> |
							<a href="?delete_order='. $row["order_id"] .'">Нулиране</a>
						</td>
					</tr>
				';
			}
			
			echo '
				</tbody>
				</table>
			';
			
		} else {
			echo "Няма направени поръчки";
		}
		?>
	</div>
	<div id="finished-tab">
		<?php
		$sql = "SELECT * FROM orders_view WHERE status='finished'";
		$orders = $db->query($sql);
		if($orders->num_rows > 0) {
			echo '
				<table class="table table-striped">
				<thead>
				<tr>
					<th scope="col">Поръчка</th>
					<th scope="col">Клиент</th>
					<th scope="col">E-mail</th>
					<th scope="col">Действия</th>
				</tr>
				</thead>
				<tbody>
			';
			
			while($row = mysqli_fetch_array($orders)) {
				echo '
					<tr>
						<td>'. $row["order_id"] .'</td>
						<td>'. $row["user_name"] .'</td>
						<td>'. $row["user_email"] .'</td>
						<td>
							<a href="order_page.php?order_id='. $row["order_id"] .'">Прегледай</a> |
							<a href="?restore_order='. $row["order_id"] .'">Възстанови</a>
						</td>
					</tr>
				';
			}
			
			echo '
				</tbody>
				</table>
			';
			
		}
		?>
	</div>
</div>

<script>$("#order-tabs").tabs()</script>

</div>


<?php include "layout/end_body.php"; ?>

<?php } ?> <!-- проверката за логнат или администратор -->