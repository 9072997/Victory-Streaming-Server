<?php
	require_once('/usr/script/db.inc.php');
	if (isset($_POST['editPassword']) && db1('SELECT 1 AS return FROM configuration WHERE var=\'editPassword\' and contents=?', $_POST['editPassword'])) {
		$userPassword = $_POST['editPassword'];
		setcookie('editPassword', $userPassword, time()+3600);
	} else {
		$userPassword = $_COOKIE['editPassword'];
	}
	if(!db1('SELECT 1 AS return FROM configuration WHERE var=\'editPassword\' and contents=?', $userPassword)->return) {
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login to Edit Victory Service Archives</title>
	</head>
	<body>
		<form method="post">
			Enter
			<label for="editPassword">Edit Password:</label>
			<input type="password" name="editPassword" id="editPassword" />
			<input type="submit" />
		</form>
	</body>
</html>
<?php
		die();
	}
?>
