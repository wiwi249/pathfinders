<?php
session_start();
//While sending an AJAX query, use GET func for to specify the function you want to call based on the database queries
//the GET arg argument is used to send the function argument (if any)
$func = $_POST['func'];
$arg = $_POST['arg'];

//obviously they might be needed :)
require('config/dbase.php');
require('libs/database.php');
$db = new Database;


$query = $db->MakeQuery("SELECT * FROM ajax_functions WHERE name='".$func."';");
if(!$db->NumRows($query)) {
	exit;
}

$res = $db->FetchRes($query);


if(!file_exists('ajax/'.$res['filename'].'.functions.php'))
	exit;
	
require_once('/ajax/'.$res['filename'].'.functions.php');
@$res['func']($arg);