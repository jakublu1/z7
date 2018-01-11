
<?php
	$id = getuserfield('id');
	$wronglog = db_select("SELECT date_wrong FROM logi WHERE id = '".$id."'");
	$wrong = $wronglog[0]['date_wrong'];
	
?>
<p>Ostatnie błędne logowanie nastąpiło: <?php echo $wrong; ?></p>

<h1>Twoje katalogi i pliki</h2>

<?php

function listFolderFiles($dir){
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    if (count($ffs) < 1)
        return;

    echo '<ol>';
    foreach($ffs as $ff){
        echo '<li>'.$ff;
        if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
        echo '</li>';
    }
    echo '</ol>';
}

listFolderFiles('katalogi/kuba123');


	
?>
pliki - linki do downloadu
<h2>Dodaj plik</h2>
<form action="index.php" method="POST" ENCTYPE="multipart/form-data"> 
	<input type="file" name="plik"/> 
	
	<input type="submit" value="Wyślij plik"/> 
</form>
<?php

if(isset($_FILES["plik"]["tmp_name"])){
	$folder = 'katalogi/kuba123/';
	if (is_uploaded_file($_FILES['plik']['tmp_name'])) 
	{ 
		echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>'; 
		move_uploaded_file($_FILES['plik']['tmp_name'], 
		$folder.$_FILES['plik']['name']); 
	} else {
		echo 'Błąd przy przesyłaniu danych!';
	} 
}

?>

<h2>Dodaj nowy katalog</h2>
tworzenie katalogow - formularz

</br>
</br>
<a href="logout.php">Wyloguj się</a>