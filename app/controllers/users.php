<?php
class Users {
	public function __construct($func, $arg) {
		if($func == "delete") {
			$this->DeleteUser($arg);
			return;
		}
		
		if($func == "view") {
			$this->ViewUser($arg);
			return;
		}
		
		if($func == "add") {
			$this->AddUser();
			return;
		}
		
		global $view, $site, $db;
		$view->AppendSiteTitle('Użytkownicy');
		$view->set('header', "Użytkownicy");
		$content = $view->getTemplate("users-headbuttons"); 
		
		
		$query = $db->MakeQuery("SELECT * FROM users");
		if($db->NumRows($query) != 0) {
			$content .= "<div class='table usertable'>";
			while($row = $db->FetchRes($query)) {
				$vals["avatar"] = "<img src='".get_profile_picture($row['id'])."' />";
				$vals["name"] = $row['imie'];
				$vals["surname"] = $row['nazwisko'];
				$vals["nick"] = $row['nick'];
				$vals["url"] = $site->siteurl."users/view/".$row['id'];
				
				$vals["options"] = "<a href=\"".$site->siteurl."users/edit/".$row['id']."\">";
				$vals["options"] .= $view->getTemplate("icon-dark-edit")."</a>";
				$vals["options"] .= "<a href=\"".$site->siteurl."users/delete/".$row['id']."\">";
				$vals["options"] .= $view->getTemplate("icon-dark-delete")."</a>";
				
				$content .= $view->getTemplate("users-table-row", $vals);
			}
			$content .= "</div>";
			
		}
		else {
			echo "Brak użytkowników w bazie danych.";
		}
		
		
		$view->set('content', $content);
		$view->render();
	}
	
	private function DeleteUser($id) {
		global $db, $site;
		if(!is_numeric($id)) {
			$_SESSION['notie'] = "Nieprawidłowy użytkownik!";
			$_SESSION['notietype'] = 3;
			header("Location:".$site->siteurl."users");
			return;
		}
		
		if($id == 1) {
			$_SESSION['notie'] = "Nie można usunąć użytkownika z uprawnieniami roota!";
			$_SESSION['notietype'] = 3;
			header("Location:".$site->siteurl."users");
			return;
		}
		
		$query = $db->MakeQuery("DELETE FROM users WHERE id='".$id."';");
		$_SESSION['notie'] = "Pomyślnie usunięto użytkownika!";
		$_SESSION['notietype'] = 1;
		header("Location:".$site->siteurl."users");
	}
	
	function ViewUser($id) {
		if(!is_numeric($id) || empty($id)) {
			$_SESSION['notie'] = "Nieprawidłowy użytkownik!";
			$_SESSION['notietype'] = 3;
			header("Location:".$site->siteurl."users");
			return;
		}
		global $db, $site, $view;
		$view->AppendSiteTitle('Profil użytkownika');
		$content = "";
		$content .= $view->getTemplate("users-profile-buttons");
		
		$query = $db->MakeQuery("SELECT * FROM users WHERE id='".$id."';");
		$results = $db->FetchRes($query);
		$vals["avatar"] = "<img src='".get_profile_picture($results['id'])."' />";
		$vals["name"] = $results['imie'];
		$vals["surname"] = $results['nazwisko'];
		$vals["nick"] = $results['nick'];
		$vals["lastlogin"] = "NULL";
		
		$view->set("header", "Informacje o ".$results['nick']);
		
		$content .= $view->getTemplate("users-profile", $vals);
		$view->set("content", $content);
		$view->render();
	}
	
	function AddUser() {
		global $view, $db, $site;
		if(isset($_POST['nick']) && isset($_POST['pass'])) {
			if(strlen($_POST['nick']) == 0 || strlen($_POST['pass'])== 0 || strlen($_POST['pass2'])==0 || strlen($_POST['imie']) == 0 || strlen($_POST['nazwisko']) == 0 || strlen($_POST['email']) == 0) {
				$_SESSION['notie'] = "Uzupełnij wszystkie dane!";
				$_SESSION['notietype'] = 3;
				header("Location:".$site->siteurl."users/add");
				return;
			}
			if($_POST['pass'] != $_POST['pass2']) {
				$_SESSION['notie'] = "Podane hasła nie są jednakowe!";
				$_SESSION['notietype'] = 3;
				header("Location:".$site->siteurl."users/add");
				return;
			}
			$query = $db->MakeQuery("SELECT * FROM users WHERE nick ='".$_POST['nick']."' OR email='".$_POST['email']."';");
			if($db->NumRows($query) > 0) {
				$_SESSION['notie'] = "Istnieje już użytkownik o takich danych!";
				$_SESSION['notietype'] = 3;
				header("Location:".$site->siteurl."users");
				return;
			}
			
			$query = $db->MakeQuery("INSERT INTO users (`nick`, `imie`, `nazwisko`, `hash`, `email`, `group`) VALUES ('".$_POST['nick']."', '".$_POST['imie']."', '".$_POST['nazwisko']."', '".sha1($_POST['pass'])."', '".$_POST['email']."', ".$_POST['group'].");");
			$_SESSION['notie'] = "Dodano użytkownika do bazy danych!";
			$_SESSION['notietype'] = 1;
			header("Location:".$site->siteurl."users");
			return;
		}
		
		$view->AppendSiteTitle('Dodaj użytkownika');
		$content = "";
		$content .= $view->getTemplate("users-add-form");
		 
		$view->set("header", "Dodaj użytkownika");
		$view->set("content", $content);
		$view->render();
		
	}
}