<?php
if (isset($_POST['username'])&&isset($_POST['password'])) {
			
	$username = secure($_POST['username']);
	$password = codepass(secure($_POST['password']));
			
	if (!empty($username)&&!empty($password)){
		$rows1 = db_select("SELECT `login`, `id` FROM `users` WHERE `login`='$username'");
		$id = $rows1[0]['id'];
		$block = db_select("SELECT `counter` FROM `logi` WHERE `id` = '".$id."'");
		$blocked = $block[0]['counter'];
		$rows = db_select("SELECT `password` FROM `users` WHERE `id`='$id' AND `password` = '$password'");
		if ($rows) {
			$user_id = $id;
			$_SESSION['user_id'] = $id;
			$query = db_query("UPDATE `logi` SET `counter` = 0 WHERE `id` = '".$id."'");
			header('Location: index.php');
		} elseif (!$rows && $blocked < 3) {
			$date = date("Y-m-d H:i:sa");
			$count = db_select("SELECT `counter` FROM `logi` WHERE `id` = '".$id."'");
			$counter = $count[0]['counter'] + 1;
			$query = db_query("UPDATE `logi` SET `date_wrong` = '".$date."', `counter` = '".$counter."' WHERE `id` = '".$id."'");
			echo 'Niepoprawna kombinacja loginu i hasła';
		} else {
			echo 'Twoje konto zostało zablokowane z powodu 3 błędnych logowań';
		}	
	} else {
		echo 'Musisz podać hasło oraz nazwę użytkownika';
	}
}
?>
			<div>
				<form id="general_form" action="<?php echo $current_file; ?>" method="POST">
					<h3>Logowanie</h3>
						<div>
							<label for="username">Nazwa użytkownika:</label>
							<input type="text" id="username" name="username" maxlength="30" value="<?php if (isset($username)) { echo $username; } ?>">
						</div>
						<div>
							<label for="password">Hasło:</label>
							<input type="password" id="password" name="password">
						</div>
						<input type="submit" id="submit" name="submit" value="Zaloguj się"><br/><br/><br/>
						<label for="username">Jeśli nie posiadasz konta:</label>
						<a class="button" href="register.php">Zarejestruj się</a>
				</form>
			</div>
			
			
			