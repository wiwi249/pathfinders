<?php

function GetProfilePicture($nick) {
	global $db;

	$result = 0;
	if(!empty($nick)) {
		$query = $db->MakeQuery("SELECT id FROM users WHERE nick='".$nick."';");
		if($db->NumRows($query) != 0 ) {
			$res = $db->FetchRes($query);
			$result = $res['id'];
		}
	}
	
	echo $result;
	
	
}
?>