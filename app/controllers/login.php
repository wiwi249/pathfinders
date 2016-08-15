<?php
class Login extends Controller {
	public function __construct() {
		global $db, $view, $site;
		if($_SESSION['logged']) {
			header("Location:".$site->siteurl);
		}
		
		$view->AppendSiteTitle('Logowanie');
		
		$view->set('lgmsg', "");
		if(isset($_POST['login']) && isset($_POST['password']) && !isset($_SESSION['logged'])) {
			$query = $db->MakeQuery("SELECT * FROM users WHERE nick='".$_POST['login']."' AND hash='".sha1($_POST['password'])."';");
			if($db->NumRows($query) < 1) {
				$view->set('lgmsg', "Nieprawidłowy nick lub hasło użytkownika!");
			} else if($db->NumRows($query) > 1) {
				$view->set('lgmsg', "W bazie istnieje duplikat użytkownika. Nie można zalogować...");
			} else {
				$results = $db->FetchRes($query);
				$_SESSION['id'] = $results['id'];
				$_SESSION['logged'] = true;
				header('Location:'.$site->siteurl.'');
			}
		}
	
		$view->render('login');
	}

}
?>