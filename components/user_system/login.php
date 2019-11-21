<?php include "server.php"?>

<div class="container">

<?php include "components/alert_messages/alert_messages.php"; ?>

<form method="POST">
	<div class="form-group">
		<label for="email">E-mail</label>
		<input class="form-control" type="email" name="email" id="email" value="<?php echo $email; ?>">
	</div>
	
	<div class="form-group">
		<label for="password">Парола</label>
		<input class="form-control" type="password" name="password" id="password">
	</div>
	
	<button class="btn btn-primary form-control" type="submit" name="login">Вход</button>
</form>

</div>