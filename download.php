
<?php  
	$file = $_GET['path'];
	$name = $_GET['name'];
	header("Content-disposition: attachment; filename=".$name."");
	header("Content-type: application/pdf");
	readfile($file);
	header('index.php');
?>