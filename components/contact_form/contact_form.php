<?php
ini_set("SMTP","ssl://smtp.gmail.com"); // SMTP на пощата
ini_set("smtp_port","465"); // порт на пощата
$to = "kaloyanvelchkov@gmail.com"; // до кой ще се изпраща съобщението
$subject = "Сайта"; // тема на съобщението
?>

<?php
if(isset($_POST["submit"])) {
	$name = trim($_POST["name"]);
	$phone = trim($_POST["phone"]);
	$email = trim($_POST["email"]);
	$comment = trim($_POST["comment"]);
	
	if(strlen($name)==0 || strlen($phone)==0 || strlen($email)==0 || strlen($comment)==0) {
		$GLOBALS["error_message"] = "Моля попълнете всички полета;";
	} else {
		$GLOBALS["success_message"] = "Благодарим, че се свързахте с нас;";
		$message = "Име: ".$name." \r\nТелефон: ".$phone."\r\n\r\nСъобщение: \r\n" . $comment;
		send_mail($message, "From: " . $email);
	}
}

function send_mail($message, $headers) {
	mail($GLOBALS["to"], $GLOBALS["subject"], $message, $headers);
}
?>

<?php include "components/alert_messages/alert_messages.php"; ?>

<form method="post">
	<div class="form-group">
		<label for="name">Име и фамилия</label>
		<input type="text" class="form-control" name="name" id="name" placeholder="Име и фамилия">
	</div>
	
	<div class="form-group">
		<label for="phone">Телефон</label>
		<input type="text" class="form-control" name="phone" id="phone" maxlength="10" placeholder="0812345678">
	</div>
	
	<div class="form-group">
		<label for="email">E-mail</label>
		<input type="email" class="form-control" name="email" id="email" placeholder="your@mail.com">
	</div>
	
	<div class="form-group">
		<label for="comment">Съобщение</label>
		<textarea class="form-control" rows="5" name="comment" id="comment" placeholder="Здравейте ..."></textarea>
	</div>
	
	<button type="submit" name="submit" class="btn btn-default">Изпрати</button>
</form>