<html>
<head>
	<title>Rejestracja</title>
</head>
<body>
		
<?php

require_once('functions.php');

if (!loggedin()) {
	
	if (isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['password_again'])&&isset($_POST['firstname'])&&isset($_POST['surname'])) {
		$username = secure($_POST['username']);
		$password = secure($_POST['password']);
		$password_again = secure($_POST['password_again']);
		$firstname = secure($_POST['firstname']);
		$surname = secure($_POST['surname']);
		
		if (!empty($username)&&!empty($password)&&!empty($password_again)&&!empty($firstname)&&!empty($surname)) {
			if (strlen($username)>30) {
				echo 'Zbyt długa nazwa użytkownika - max. 30 znaków.';
			} elseif (strlen($username)<5) {
				echo 'Podana nazwa użytkownika jest za krótka - min. 5 znaków.';
			} elseif (!ctype_alnum($username)) {
				echo 'Nazwa użytkownika musi składać się tylko z liter oraz cyfr.';
			} elseif (strlen($firstname)>40) {
				echo 'Zbyt długie imię - max. 40 znaków.';
			} elseif (!ctype_alpha($firstname)) {
				echo 'Imię musi składać się wyłącznie z liter.';
			} elseif (strlen($surname)>40) {
				echo 'Zbyt długie nazwisko - max. 40 znaków.';
			} elseif (!ctype_alpha($surname)) {
				echo 'Nazwisko musi składać się wyłącznie z liter.';
			} elseif (strlen($password)<5) {
				echo 'Podane hasło jest za krótkie - min. 5 znaków.';
			} elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z]{5,20}$/', $password)){
				 echo 'Podano nieprawidłowe hasło, musi ono zawierać przynajmniej jedną wielką<br />literę i jedną cyfrę.';
			} elseif ($password!=$password_again){
				echo 'Podane hasła różnią się.';
			} else {
				$password = codepass($password);
				$result_name = db_select("SELECT `login` FROM `users` WHERE `login`='$username'");
				if ($username == @$result_name[0]['login']) {
					echo 'Użytkownik o podanej nazwie już istnieje.';
				} else {
					$query = db_query("INSERT INTO `users` (`id`,`firstname`,`surname`,`login`,`password`) VALUES ('','".$firstname."','".$surname."','".$username."','".$password."')");
					if ($query === false){
						echo 'Rejestracja nie powiodła się - spróbuj jeszcze raz.';
					} else {
						if (!file_exists('katalogi/'.$username.'')) {
							mkdir('katalogi/'.$username.'', 0777, true);
						}
						$idu = db_select("SELECT `id` FROM `users` WHERE `login` = '".$username."'");
						$iduu = $idu[0]['id'];
						$query1 = db_query("INSERT INTO `logi` (`id`,`date_wrong`,`counter`) VALUES ('".$iduu."','',0)");
						echo 'Rejestracja przebiegła pomyślnie.</br>';
						echo '<a href="index.php">Zaloguj się</a></br></br>';
					}
				}
			}
		} else {
			echo 'Wszystkie pola są wymagane.';
		}	
		
	}
?>
	
	<form id="general_form" action="register.php" method="POST">
		<h3>Rejestracja konta użytkownika:</h3>
		<div>
			<label for="username">Nazwa użytkownika:</label>
			<input type="text" id="username" name="username" maxlength="40" value="<?php if (isset($username)) { echo $username; } ?>">
			<p>Musi składać się z minimum 5 znaków i maksymalnie 40 oraz zawierać tylko litery i cyfry.</p>
		</div>
		<div>
			<label for="password">Hasło:</label>
			<input type="password" id="password" name="password">
			<p>Musi składać się z minimum 5 znaków oraz zawierać przynajmniej jedną wielką literę i jedną cyfrę.</p>
		</div>
		<div>
			<label for="password_again">Powtórz hasło:</label>
			<input type="password" id="password_again" name="password_again">
			</br>
			</br>
		</div>
		<div>
			<label for="firstname">Imię:</label>
			<input type="text" id ="firstname" name="firstname" maxlength="40" value="<?php if (isset($firstname)) { echo $firstname; } ?>">
			<p>Maksymalnie 40 znaków.</p>
		</div>
		<div>
			<label for="surname">Nazwisko:</label>
			<input type="text" id="surname" name="surname" maxlength="40" value="<?php if (isset($surname)) { echo $surname; } ?>">
			<p>Maksymalnie 40 znaków.</p>
		</div>
		<input type="submit" id="submit" name="submit" value="Zarejestruj się"><br/><br/><br/>
		<a href="index.php">Anuluj</a><br /><br />
	</form>

<?php
	
} else {
	header('Location: index.php');
}

?>


</body>
</html>