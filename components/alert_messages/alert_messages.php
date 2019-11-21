<?php
if(isset($GLOBALS["error_message"])) {
echo "<div id='error_message' class='alert alert-danger alert-dismissible'>";
	echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"; // хиксчето за затваряне
	$errors = explode(";", $GLOBALS["error_message"]);
	echo "<ul>";
	foreach($errors as $error) {
		if($error != "") echo "<li>". $error . "</li>";
	}
	echo "</ul>";

echo "</div>";
echo "<script>$('#error_message').delay(3000).hide(1000, 'swing');</script>"; // скрива div-а
}

if(isset($GLOBALS["success_message"])) {
echo "<div id='success_message' class='alert alert-success alert-dismissible'>";
	echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
	$successes = explode(";", $GLOBALS["success_message"]);
	echo "<ul>";
	foreach($successes as $success) {
		if($success != "") echo "<li>". $success . "</li>";
	}
	echo "</ul>";

echo "</div>";
echo "<script>$('#success_message').delay(3000).hide(1000, 'swing');</script>";
}
?>