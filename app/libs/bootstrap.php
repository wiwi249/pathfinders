<?php
class Bootstrap {
	 function __construct() {
	 	$url = isset($_GET['url']) ? $_GET['url'] : null;
	 	$url = rtrim($url, '/');
	 	$url = explode('/', $url);
		
		global $db, $site, $view;
		
		//Check user session / regenerate session id.
		if($url[0] != 'login') {
			if(!isset($_SESSION['auth'])) {
				session_regenerate_id();
				$_SESSION['auth'] = true;
			}
			//if user is claimed as logged
			if(isset($_SESSION['logged'])) {
				$query = $db->MakeQuery("SELECT * FROM users where id=".$_SESSION['id'].";");
				if($db->NumRows($query) < 1) {
					header("Location: ".$site->siteurl."logout");
					return;
				}
			}
			
			else {
				header("Location: ".$site->siteurl."login");
			}			
		}
		
		$db->MakeQuery("UPDATE usersessions SET time='".time()."' WHERE user=".$_SESSION['id'].";");
		
		if($url[0] == 'login') {
			require_once('controllers/login.php');
			$controller = new Login;
			return;
		}
		
		if($url[0] == 'logout') {
			require_once('controllers/logout.php');
			$controller = new Logout;
			return;
		}
		
		if(empty($url[0])) {
			require_once('controllers/index.php');
			$controller = new Index;
			return;
		}
		
		if(isset($_SESSION['notie'])) {
			$view->set('lgmsg',$_SESSION['notie']);
			if(isset($_SESSION['notietype']))
				$view->set('lgmsgtype', $_SESSION['notietype']);
			
			else 
				$view->set('lgmsgtype', "4");
				
			unset($_SESSION['notie']);
			unset($_SESSION['notietype']);
		}
		else {
			$view->set('lgmsg',"");
			$view->set('lgmsgtype', "4");
		}
		
		$path = 'controllers/'.$url[0].'.php';
		if(!file_exists($path)) {
			require_once('controllers/error.php');
			$controller = new Error;
			return;
		}
		else {
			require_once($path);
			if(isset($url[1])) {
				if(isset($url[2]))
					$controller = new $url[0]($url[1], $url[2]);
				else
					$controller = new $url[0]($url[1]);
			} else {
				$controller = new $url[0];
			}

			return;
		}
	}
	 
	private function get_controller_data($controller) {
		global $db;
		$query = $db->MakeQuery("SELECT * FROM sys_controllers WHERE url='".$controller."';");
		$num = $db->NumRows($query);
		if($num == 0) {
			return $num;
		}
		$results = $db->FetchRes($query);
		return $results;
	}
}