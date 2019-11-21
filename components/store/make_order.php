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

<?php 
if(isset($_POST["order"])) {
	$order_id = random_int(0, 1000000000);
	$user_email = $_SESSION["email"];
	$sql = "INSERT INTO orders VALUES ('$order_id', '$user_email', 'processing')";
	$success_queries = true;
	
	if($db->query($sql)) {
		$sql = "SELECT * FROM cart_view WHERE user_email='". $user_email ."'";
		$cart = mysqli_fetch_array($db->query($sql));
		$product_id_list = explode(",", $cart["product_id_list"]);
		$product_price_list = explode(",", $cart["product_price_list"]);
		$product_quanity_list = explode(",", $cart["product_quanity_list"]);
		
		for($i=0; $i<count($product_id_list); $i++) {
			$sql = "INSERT INTO order_products VALUES ('$order_id', '$product_id_list[$i]', '$product_price_list[$i]', '$product_quanity_list[$i]')";
			if(!$db->query($sql)) {
				$success_queries = false;
			}
		}
		
		$db->query("DELETE FROM cart WHERE user_email='". $user_email ."'");
		if(mysqli_affected_rows($db) == 0) {
			$success_queries = false;
		}
	} else {
		$success_queries = false;
	}
	
	if($success_queries) {
		header("location: cart.php?success_order=1");
	} else {
		$db->query("DELETE FROM orders WHERE id='". $order_id ."'");
		$GLOBALS["error_message"] = "Възникна проблем с поръчката";
	}
}
?>

<div class="container">

<?php include("components/alert_messages/alert_messages.php") ?>

<form id="form" method="POST">
	<div id="tabs">
		<ul>
			<li><a href="#products-tab">Продукти</a></li>
			<li><a class="disabled" href="#delivery-tab">Данни за доставка</a></li>
			<li><a href="#final-tab" onClick="goToFinalTab()">Финализиране на поръчката</a></li>
		</ul>
		<div id="products-tab">
			<?php 
			$order = $db->query("SELECT * FROM cart_view WHERE user_email='". $_SESSION["email"] ."'");
			if($order->num_rows > 0) {
				$order = mysqli_fetch_array($order);
				$product_id_list = explode(",", $order["product_id_list"]);
				$product_title_list = explode(",", $order["product_title_list"]);
				$product_quanity_list = explode(",", $order["product_quanity_list"]);
				$product_price_list = explode(",", $order["product_price_list"]);
				$product_total_price_list = explode(",", $order["product_total_price_list"]);
				$product_grand_total_price = $order["grand_total_price"];
				
				echo '
					<table class="table table-striped">
					<thead>
					<tr>
						<th scope="col">Продукт</th>
						<th scope="col">Единична цена</th>
						<th scope="col">Брой</th>
						<th scope="col">Цена</th>
					</tr>
					</thead>
					<tbody>
				';
				
				for($i=0; $i<count($product_id_list); $i++) {
					echo '
						<tr>
							<td>'. $product_title_list[$i] .'</td>
							<td>'. $product_price_list[$i] .'</td>
							<td>'. $product_quanity_list[$i] .'</td>
							<td>'. $product_total_price_list[$i] .'</td>
						</tr>
					';
				}
				
				echo '
						<td></td>
						<td></td>
						<td><strong>Общо</strong></td>
						<td><strong>'. $product_grand_total_price .'</strong></td>
					</tbody>
					</table>
				';
				
			} else {
				//header("location: cart.php");
			}
			?>
			<button class="btn" type="button" onClick="location.href='cart.php'">Назад към количка</button>
			<button class="btn btn-primary" type="button" onClick="goToDeliveryTab()">Продължи</button>
		</div>
		<div id="delivery-tab">
			<div class="form-group">
				<label for="first_name">Име<font color="red">*</font></label>
				<input class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $_SESSION["first_name"] ?>">
			</div>
			<div class="form-group">
				<label for="last_name">Фамилия<font color="red">*</font></label>
				<input class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $_SESSION["last_name"] ?>">
			</div>
			<div class="form-group">
				<label for="phone">Телефон<font color="red">*</font></label>
				<input class="form-control" type="number" name="phone" id="phone" value="<?php echo $_SESSION["phone"] ?>">
			</div>
			<div class="form-group">
				<label for="address">Адрес за доставка<font color="red">*</font></label>
				<textarea class="form-control" name="address" id="address"><?php echo $_SESSION["address"] ?></textarea>
			</div>
			<div class="form-group">
				<label for="additional_information">Допълнителна информация</label>
				<textarea class="form-control" name="additional_information" id="additional_information"></textarea>
			</div>
			
			<button class="btn btn-primary" type="button" onClick="goToFinalTab();" id="delivery-tab-button" disabled>Продължи</button>
		</div>
		<div id="final-tab">
			<p>Име: <em><span id="final_info_first_name"></span> <span id="final_info_last_name"></span></em></p>
			<p>Телефон: <em><span id="final_info_phone"></span></em></p>
			<p>Адрес: <em><span id="final_info_address"></span></em></p>
			<p>Допълнителна информация: <em><span id="final_info_additional_information"></span></em></p>
			<p><strong>Цена: <?php echo $product_grand_total_price ?></strong></p>
			
			<button class="btn btn-primary" type="submit" id="make_order_button" name="order" disabled>Поръчай</button>
		</div>
	</div>
</form>

</div>

<script>
$("#tabs").tabs({disabled: [1,2]});

function goToDeliveryTab() {
	$("#tabs").tabs("enable", 1);
	$("#tabs").tabs({active: 1});
	validate();
}

function goToFinalTab() {
	$("#tabs").tabs("enable", 2);
	$("#tabs").tabs({active: 2});
	var first_name = $("#first_name").val();
	var last_name = $("#last_name").val();
	var phone = $("#phone").val();
	var address = $("#address").val();
	var additional_information = $("#additional_information").val();
	
	$("#final_info_first_name").html($("#first_name").val());
	$("#final_info_last_name").html($("#last_name").val());
	$("#final_info_phone").html($("#phone").val());
	$("#final_info_address").html($("#address").val());
	$("#final_info_additional_information").html($("#additional_information").val());
}

$("#form").keyup(function() {
	validate();
});

function validate() {
	if (
		$("#first_name").val().trim() != "" &&
		$("#last_name").val().trim() != "" &&
		$("#phone").val().trim() != "" &&
		$("#address").val().trim() != ""
	) {
		$("#delivery-tab-button").prop("disabled", false);
		$("#make_order_button").prop("disabled", false);
	} else {
		$("#delivery-tab-button").prop("disabled", true);
		$("#make_order_button").prop("disabled", true);
	}
}
</script>


<?php include "layout/end_body.php"; ?>

<?php } ?> <!-- проверката за логнат или администратор -->