<?php
function SaveAdminNote() {
	$note = $_POST['arg'];
	
	if(!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
		return;
	
	global $db;
	$db->MakeQuery("UPDATE misc SET val='".$db->Esc($note)."' WHERE k='adminnote';");
	
	echo $note;
}