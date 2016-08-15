<?php
function get_user_data($id, $type = "nick") {
	global $db;
	
	$query = $db->MakeQuery("SELECT ".$type." FROM users WHERE id=".$id.";");
	if($db->NumRows($query) == 0)
		return 0;
		
	$res = $db->FetchRes($query);
	return $res[$type];

}

function get_profile_picture($uid) {
	global $site;
	$file = $site->siteurl."public/uploads/userprofiles/".$uid.".png";
	$handle = @fopen($file, 'r');
	if($handle)
		return $file;
		
	else
		return $site->siteurl."public/uploads/userprofiles/default.png";	
}