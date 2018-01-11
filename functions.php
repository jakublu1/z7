<?php

ob_start();
session_start();


$current_file = $_SERVER['SCRIPT_NAME'];


if (isset($_SERVER['HTTP_REFERER'])&&!empty($_SERVER['HTTP_REFERER'])) {
	$http_referer = $_SERVER['HTTP_REFERER'];
}



//--------------POŁĄCZENIE Z BAZĄ DANYCH-----------------//
class ConnException extends Exception {}

function db_connect() {

    static $connection;
	
	try {
    if(!isset($connection)) {

        $connection = @mysqli_connect("localhost","root","","jakublu1_git");
		
    }
	
    if($connection === false) {
        throw new ConnException('Could not connect to server or database.');
    }
	} catch (ConnException $ex) {
	echo 'Error: '.$ex->getMessage(); 
}
    return $connection;
}



//--------------ZAPYTANIE DO BAZY DANYCH-----------------//
function db_query($query) {

    $connection = db_connect();
	
	mysqli_query($connection, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    $result = mysqli_query($connection,$query);

    return $result;
}



//--------------BŁĄD BAZY DANYCH-----------------//
function db_error() {
    $connection = db_connect();
    return mysqli_error($connection);
}


//--------------SELECT Z BAZY DANYCH-----------------//
function db_select($query) {
    $rows = array();
    $result = db_query($query);

    if($result === false) {
        return false;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
	}
    return $rows;
}



//--------------CZY User ZALOGOWANY-----------------//
function loggedin() {
	if (isset($_SESSION['user_id'])&&!empty($_SESSION['user_id'])) {
		return true;
	} else {
		return false;
	}
}



//--------------UZYSKAJ DANE Usera-----------------//
function getuserfield($field) {
	$query = db_select("SELECT `$field` FROM `users` WHERE `id`='".$_SESSION['user_id']."'");
	if ($query) {
		return $query[0][$field];
	}
}


//--------------ZABEZPIECZENIE DANYCH Z FORMULARZY-----------------//
function secure($value) {
    $connection = db_connect();
    return trim(htmlspecialchars(strip_tags(mysqli_real_escape_string($connection,$value))));
}



//--------------KODOWANIE HASŁA-----------------//
function codepass($password) {
    
    return sha1(md5($password).'#!%Rgd64');
}

?>