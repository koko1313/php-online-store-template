<?php 
if(!isset($_SESSION["email"])) {
	header("location: index.php");
} else {
?>

<?php include("server.php") ?>

<div class="container">

	<h1><?php echo $_SESSION["first_name"] . ' ' . $_SESSION["last_name"] ?></h1>
	
	<?php include "components/alert_messages/alert_messages.php" ?>
	
	<table id="user-info-table">
		<tr>
			<td><strong>E-mail</strong></td>
			<td><?php echo $_SESSION["email"] ?></td>
			<td><button type="button" class="btn btn-link" data-toggle="modal" data-target="#emailModal">[Редактирай]</button></td>
		</tr>
		<tr>
			<td><strong>Телефон</strong></td>
			<td><?php echo $_SESSION["phone"] ?></td>
			<td><button type="button" class="btn btn-link" data-toggle="modal" data-target="#phoneModal">[Редактирай]</button></td>
		</tr>
		<tr>
			<td><strong>Адрес</strong></td>
			<td><?php echo $_SESSION["address"] ?></td>
			<td><button type="button" class="btn btn-link" data-toggle="modal" data-target="#addressModal">[Редактирай]</button></td>
		</tr>
		<tr>
			<td><strong>Парола</strong></td>
			<td><button type="button" class="btn btn-link" data-toggle="modal" data-target="#passwordModal">[Редактирай]</button></td>
			<td></td>
		</tr>
	</table>

</div>


<form method="POST">
	<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Смяна на E-mail</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<div class="form-gorup">
					<label for="email">E-mail</label>
					<input class="form-control" type="email" name="email" id="email" value="<?php echo $_SESSION["email"] ?>">
				</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
			<button type="submit" name="edit_email" class="btn btn-warning">Редактирай</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Смяна на телефон</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<div class="form-gorup">
					<label for="phone">Телефон</label>
					<input class="form-control" type="number" name="phone" id="phone" value="<?php echo $_SESSION["phone"] ?>">
				</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
			<button type="submit" name="edit_phone" class="btn btn-warning">Редактирай</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Смяна на адрес</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<div class="form-gorup">
					<label for="address">Адрес</label>
					<textarea class="form-control" name="address" id="address"><?php echo $_SESSION["address"] ?></textarea>
				</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
			<button type="submit" name="edit_address" class="btn btn-warning">Редактирай</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Смяна на парола</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<div class="form-gorup">
					<label for="old_password">Стара парола</label>
					<input class="form-control" type="password" name="old_password" id="old_password">
				</div>
				<div class="form-gorup">
					<label for="password">Нова парола</label>
					<input class="form-control" type="password" name="password" id="password">
				</div>
				<div class="form-gorup">
					<label for="password_confirm">Повторете новата парола</label>
					<input class="form-control" type="password" name="password_confirm" id="password_confirm">
				</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
			<button type="submit" name="edit_password" class="btn btn-warning">Редактирай</button>
		  </div>
		</div>
	  </div>
	</div>
</form>

<style>
#user-info-table td {
	padding-left: 5px;
	padding-right: 5px;
}
</style>

<?php } ?> <!-- проверката за логнат или администратор -->