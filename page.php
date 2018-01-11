
<?php
	$id = getuserfield('id');
	$wronglog = db_select("SELECT date_wrong FROM logi WHERE id = '".$id."'");
	$wrong = $wronglog[0]['date_wrong'];
	$login = db_select("SELECT login FROM users WHERE id = '".$id."'");
	$log = $login[0]['login'];
	
?>
<p>Ostatnie błędne logowanie nastąpiło: <?php echo $wrong; ?></p>

<h1>Twoje katalogi i pliki</h2>
<p>Kliknij na wybrany plik aby go pobrać</p>
<?php

function listFolderFiles($dir){
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    if (count($ffs) < 1)
        return;

    echo '<ol>';
    foreach($ffs as $ff){
        echo '<li><a href="download.php?path='.$dir.'/'.$ff.'&&name='.$ff.'">'.$ff.'</a>';
        if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
        echo '</li>';
    }
    echo '</ol>';
}

listFolderFiles('katalogi/'.$log.'');



?>

<h2>Dodaj plik</h2>
<form action="index.php" method="POST" ENCTYPE="multipart/form-data"> 
	<input type="file" name="plik"/> 
	<label for="path">Podaj ścieżkę do katalogu:</label>
	<?php echo $log.'/'; ?><input type="text" id="path" name="path" maxlength="30">
	<input type="submit" value="Wyślij plik"/> 
</form>
<?php

if (isset($_POST['path'])) {
	$path = $log.'/'.$_POST['path'].'/';
} else {
	$path = $log;
}
if(isset($_FILES["plik"]["tmp_name"])){
	$folder = 'katalogi/'.$path.'';
	if (is_uploaded_file($_FILES['plik']['tmp_name'])) 
	{ 
		echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>'; 
		echo $path; echo '</br>';
		echo $folder;
		move_uploaded_file($_FILES['plik']['tmp_name'], 
		$folder.$_FILES['plik']['name']); 
	} else {
		echo 'Błąd przy przesyłaniu danych!';
	} 
}


?>

<h2>Dodaj nowy katalog</h2>
<form action="index.php" method="POST" ENCTYPE="multipart/form-data"> 
	<label for="fol">Podaj nazwę katalogu:</label>
	<input type="text" id="fol" name="fol" maxlength="30">
	<input type="submit" value="Stwórz"/> 
</form>
<?php
if (isset($_POST['fol'])) {
	if (!file_exists('katalogi/'.$log.'/'.$_POST['fol'].'')) {
		mkdir('katalogi/'.$log.'/'.$_POST['fol'].'', 0777, true);
	} else {
		echo 'Katalog o podanej nazwie już istnieje.';
	}
}

?>
</br>
</br>
<a href="logout.php">Wyloguj się</a>