<?php
class Logout extends Controller {
	public function __construct() {
		global $site;
		if($_SESSION['logged']) {
			unset($_SESSION['logged']);
			unset($_SESSION['id']);
			unset($_SESSION['auth']);
			unset($_SESSION['notie']);
			unset($_SESSION['notietype']);
			
			session_destroy();
			header('Location:'.$site->siteurl.'');
		}
	}
}