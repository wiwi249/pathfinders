<?php
function loadMenu($menu) {
	if(!$menu)
		return;
		
	global $view,$db,$site;
	$query = $db->MakeQuery("SELECT * FROM menus WHERE menu='$menu' AND parent IS NULL ORDER BY ord;");
	if(!$query) {
		echo "Could not load menu";
		return;
	}
	
	echo "<ul id='menu-".$menu."'>";
	while($row = $db->FetchRes($query)) {
		$r = $row;
		$sub = "";
		$query2 = $db->MakeQuery("SELECT * FROM menus WHERE menu='$menu' AND parent='".$r['id']." ORDER BY ord';");
		if($db->NumRows($query2)) {
			$sub = "<ul>";
			while($row2 = $db->FetchRes($query2)) {
				$r2 = $row2;
				$sub .= "<li><a href='".$r2['url']."'>".$r2['title']."</a></li>";
			}
			$sub .="</ul>";
		}
		
		$ins['title'] = $r['title'];
		$ins['url'] = str_replace("{\$siteurl}", $site->siteurl, $r['url']);
		$ins['sub'] = $sub;
		
		echo $view->getTemplate("menu-$menu-row", $ins);
			
	}
	echo "</ul>";
}

function navigationButton($name, $url, $style, $access) {
	if(is_numeric($access)) {
		if($access != -1) {
			global $user;
			
			if($user->getUserGroup() > $access)
				return;
		
			/*przesunięcie bitowe?
			if(!($access & (1<<($user->getUserGroup()))))
				return;*/
		}
	} else {
		//do zrobienia grupowy dostęp
	}
	global $view;
	
	$data['url'] = $url;
	$data['name'] = $name;
	$data['style'] = $style;
	
	return $view->getTemplate('navigation-button', $data);
}

function makeNavigation($buttons) {
	global $view;
	$rep['content'] = $buttons;
	return $view->getTemplate('navigation', $rep);
}