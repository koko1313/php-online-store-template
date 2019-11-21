<?php include "components/db_config.php" ?>

<?php
$first_name = $last_name = $phone = $email = $password = $password_confirm = $address = "";

if(isset($_POST["register"])) {
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$address = $_POST["address"];
	$password = $_POST["password"];
	$password_confirm = $_POST["password_confirm"];
	
	if(
		$first_name == "" ||
		$last_name == "" ||
		$phone == "" ||
		$email == "" ||
		$password == "" ||
		$password_confirm == ""
	) {
		$GLOBALS["error_message"] = "Моля попълнете всички полета;";
	} else 
	if($password != $password_confirm) {
		$GLOBALS["error_message"] = "Паролите не съвпадат;";
	} else
	if($password = $password_confirm) {
		$password = password_hash($password, PASSWORD_BCRYPT);
		
		$sql = "INSERT INTO users VALUES ('$email', '$first_name', '$last_name', '$phone', '$password', '$address', '2')";
		if($db->query($sql)) {
			$GLOBALS["success_message"] = "Регистрирахте се успешно;";
			$first_name = $last_name = $phone = $email = $password = $password_confirm = $address = "";
		} else {
			if(mysqli_errno($db) == 1062) { // duplicated values error
				$GLOBALS["error_message"] = "Този e-mail вече е регистриран в нашата система;";
			} else {
				$GLOBALS["error_message"] = "Регистрацията не беше успешна;";
			}
		}
	}
}

if(isset($_POST["login"])) {
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	$sql = "SELECT * FROM users_view WHERE email='$email'";
	$row = mysqli_fetch_array($db->query($sql));
	if(isset($row) && password_verify($password, $row["password"]) || ($row["email"]=="admin@admin.com" && $password==$row["password"] && $password=="password")) {
		$_SESSION["first_name"] = $row["first_name"];
		$_SESSION["last_name"] = $row["last_name"];
		$_SESSION["phone"] = $row["phone"];
		$_SESSION["email"] = $row["email"];
		$_SESSION["address"] = $row["address"];
		$_SESSION["role"] = $row["role"];
		header("location: index.php");
		$first_name = $last_name = $phone = $email = $password = $password_confirm = $address = "";
	} else {
		$GLOBALS["error_message"] = "Невалиден e-mail или парола";
	}
}

if(isset($_GET["logout"])) {
	session_destroy();
	header("location: index.php");
}

if(isset($_POST["edit_email"])) {
	$email = $_POST["email"];
	$sql = "UPDATE users SET email='".$email."' WHERE email='". $_SESSION["email"] ."'";
	
	if($db->query($sql)) {
		$_SESSION["email"] = $email;
		$GLOBALS["success_message"] = "E-mail-а беше успешно редактиран;";
	} else {
		if(mysqli_errno($db) == 1062) {
			$GLOBALS["error_message"] = "Този e-mail вече е зает от друг потребител;";
		} else {
			$GLOBALS["error_message"] = "E-mail-а не беше редактиран успешно;";
		}
	}
}

if(isset($_POST["edit_phone"])) {
	$phone = $_POST["phone"];
	$sql = "UPDATE users SET phone='".$phone."' WHERE email='". $_SESSION["email"] ."'";
	
	if($db->query($sql)) {
		$_SESSION["phone"] = $phone;
		$GLOBALS["success_message"] = "Телефона беше успешно редактиран;";
	} else {
		$GLOBALS["error_message"] = "Телефона не беше редактиран успешно;";
	}
}

if(isset($_POST["edit_address"])) {
	$address = $_POST["address"];
	$sql = "UPDATE users SET address='".$address."' WHERE email='". $_SESSION["email"] ."'";
	
	if($db->query($sql)) {
		$_SESSION["address"] = $address;
		$GLOBALS["success_message"] = "Адреса беше успешно редактиран;";
	} else {
		$GLOBALS["error_message"] = "Адреса не беше редактиран успешно;";
	}
}

if(isset($_POST["edit_password"])) {
	$old_password = $_POST["old_password"];
	$password = $_POST["password"];
	$password_confirm = $_POST["password_confirm"];
	
	$session_password = mysqli_fetch_array($db->query("SELECT password FROM users WHERE email='". $_SESSION["email"] ."'"))["password"];
	
	if(password_verify($old_password, $session_password) || $old_password == $session_password && $session_password == "password") {
		if($password == $password_confirm) {
			$password = password_hash($password, PASSWORD_BCRYPT);
			$sql = "UPDATE users SET password='".$password."' WHERE email='". $_SESSION["email"] ."'";
			
			if($db->query($sql)) {
				$GLOBALS["success_message"] = "Паролата беше успешно редактирана;";
			} else {
				$GLOBALS["error_message"] = "Паролата не беше редактирана успешно;";
			}
		} else {
			$GLOBALS["error_message"] = "Новите пароли не съвпадат; Неуспешно редактиране;";
		}
	} else {
		$GLOBALS["error_message"] = "Некоректна стара парола; Неуспешно редактиране;";
	}
}

?>