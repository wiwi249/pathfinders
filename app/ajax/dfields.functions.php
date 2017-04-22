<?php

//serious shit
function FieldOptionAdd() {
	if(!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
		return;
	
	$name = $_POST['name'];
	$field = $_POST['field'];
	
	if(!is_numeric($field))
		exit(0);	
		
	global $db;
	$stname = strtolower($name);
	$stname = rtrim($stname, ' ');
	$stname = ltrim($stname, ' ');
	$stname = iconv('utf-8', 'ascii//TRANSLIT', $stname);
	$stname = preg_replace('/[^A-Za-z0-9\-]/', '', $stname);
	
	$query = $db->MakeQuery("SELECT Count(*) AS num FROM fields_values WHERE field='".$field."';");
	$r = $db->FetchRes($query);
	$ord = $r['num']+1;
	
	$query = $db->MakeQuery("INSERT INTO fields_values (name, value, ord, field) VALUES ('".$name."', '".$stname."', '".$ord."', '".$field."');");
	
	echo ($query) ? $db->LastId() : 0;
}

function FieldOptionsOrder() {
	if(!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
		return;

	global $db;
	$id = $_POST['fid'];
	foreach($_POST['item'] as $ord=>$vid) {
		$db->MakeQuery("UPDATE fields_values SET ord=".($ord+1)." WHERE id='".$vid."';");
	}
}

function FieldOptionsName() {
	if(!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
		return;
		
	global $db;
	$id = $_POST['oid'];
	$name = $_POST['name'];
	$name = rtrim($name, ' ');
	$name = ltrim($name, ' ');
	$stname = strtolower($name);
	$stname = rtrim($stname, ' ');
	$stname = ltrim($stname, ' ');
	
	$stname = iconv('utf-8', 'ascii//TRANSLIT', $stname);
	$stname = preg_replace('/[^A-Za-z0-9\-]/', '', $stname);
	$query = $db->MakeQuery("UPDATE fields_values SET name='".$name."', value='".$stname."' WHERE id='".$id."'");
	
	echo ($query) ? 1 : 0;
}

function FieldsOptionRemove() {
	if(!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
		return;
		
	global $db;
	$id = $_POST['oid'];
	$query = $db->MakeQuery("DELETE FROM fields_values WHERE id='".$id."'");
	
	echo ($query) ? 1 : 0;
}