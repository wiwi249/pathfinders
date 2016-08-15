<?php 
class Index extends Controller {
	function __construct() {
		global $view, $site, $db;
		$view->AppendSiteTitle('Strona główna');
		$view->set('header', "Strona główna");
		
		$query = $db->MakeQuery("SELECT val FROM misc WHERE k='adminnote'");
		$r = $db->FetchRes($query);
		$rep['adminnote'] = $r['val'];
		$content = "";
		$ct['adminnote'] = $view->getTemplate('index-adminnote', $rep);
		
		$usersonline['content'] = "";
		$query = $db->MakeQuery("SELECT user FROM usersessions WHERE time>".(time()-(5*60)).";");
		if($db->NumRows($query) < 1) {
			$usersonline['content'] = "Brak! ".time()."   ".$_SESSION['id'];
		}
		else{
			while($r = $db->FetchRes($query)) {
				$uodata['avatar'] = get_profile_picture($r['user']);
			
			//DODAĆ JOIN I POBIERANIE NICKU
				$usersonline['content'] .= $view->getTemplate('usersonline-row', $uodata);
			}	
		}
		$ct['usersonline'] = $view->getTemplate('usersonline', $usersonline);
		$content = $view->getTemplate('indexpage', $ct);
		
		
		
		$view->set('content', $content);
		$view->render();
	}
}