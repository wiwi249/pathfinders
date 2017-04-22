<?php
class Data {
	public function __construct($func, $arg) {
		if($func == "add") {
			$this->addData();
			return;
		}
		
		if($func == "edit") {
			$this->editData($arg);
			return;
		}
		
		if($func == "view") {
			$this->viewData($arg);
			return;
		}
		
		if($func == "fields") {
			$this->viewFields($arg);
			return;
		}
		
		if($func == "addfield") {
			$this->AddField();
			return;
		}
		
		global $view, $site, $db;
		$view->AppendSiteTitle('Baza danych');
		$view->set('header', "Baza danych");
		$content = $view->getTemplate("data-welcome-index");
		$content .= $view->getTemplate("data-search");
		
		$query = "SELECT id, imie, nazwisko, active, funkcja, druzyna FROM pathfinders";
		
		$queryfields = array(
			0 => "active",
			1 => "imie",
			2 => "nazwisko",
			3 => "wojewodztwo",
			4 => "druzyna",
			5 => "stopien",
			6 => "funkcja"
		);
		
		$and = false;
		
		for($i = 0; $i<=6; $i++) {
			if(isset($_GET[$queryfields[$i]]) && !empty($_GET[$queryfields[$i]])) {
				if($and == false) {
					$query .= " WHERE ";
					$and = true;
				} else {
					$query .= " AND ";
				}
				
				if($queryfields[$i] == "imie" || $queryfields[$i] == "nazwisko") {
					$query .= $queryfields[$i]." LIKE '%".$_GET[$queryfields[$i]]."%'";
				}
				
				else 
					$query .= $queryfields[$i]."='".$_GET[$queryfields[$i]]."'";
			}
		}
		
		if($and == false) {
			$query .= " LIMIT 0,30";
		}
		
		/*for debug
		$content .= $query;
		$content .= navigationButton("GUZIK", $site->siteurl, "", 0);
		*/
		$q = $db->MakeQuery($query);
		if($db->NumRows($q) < 1) {
			$content .="Brak wyników.";
		}
		else {
			$rep['tablecontents'] = "";
			while($r=$db->FetchRes($q)) {
				$rep['tablecontents'] .= $view->getTemplate("data-table-row", $r);
			}
			
			$table = $view->getTemplate("data-table", $rep);
			$content .= $table;
		}
		
		$view->set('content', $content);
		$view->render();
	}
	
	
	//Dodawanie członka Pathfinders do bazy
	public function addData() {
		
		
	}
	
	
	
	
	
	
	
	//Edytowanie członka bazy Pathfinders, tak samo jak wyżej, tylko ładowanie wartości pól
	public function editData($id) {
		
	}
	
	
	
	
	
	
	
	
	//Wyświetlanie informacji o członku bazy Pathfinders
	public function viewData($id) {
		global $db, $view;
		if(!is_numeric($id)) {
			$view->AppendSiteTitle('Nie znaleziono!');
			$view->set('header', 'Błąd!');
			$view->set('content', "Nie znaleziono takiego członka!");
			$view->render();
			
			return;
		}
		
		$content = "";
		$this->LoadDatabaseFieldTypes();
		
		$query = $db->MakeQuery("SELECT * FROM pathfinders WHERE id='".$id."';");
		if($db->NumRows($query) < 1) {
			$view->AppendSiteTitle('Nie znaleziono!');
			$view->set('header', 'Błąd!');
			$view->set('content', "Nie znaleziono użytkownika");
			$view->render();
			
			return;
		}
		$res = $db->FetchRes($query);
		
		$view->AppendSiteTitle("Wyświetl członka");
		$view->set('header', $res['imie']." ".$res['nazwisko']);
		
		$beautynames = $this->loadFieldBeautyNames();
		$fieldtypes = $this->loadFieldTypes();
		
		//TEMPORARY?
		$fieldFunctions = $this->getFieldsFunctions();
		
		foreach($res as $key => $value) {
			if($key === "id")
				continue;

			$func = $fieldFunctions[$fieldtypes[$key]]['load'];
			if(!$func) {
				$func = "FieldTextValue";
			}
				
			$rpl['value'] = $func( !( $fieldtypes[$key] == "text" || $fieldtypes[$key] == "textarea" || $fieldtypes[$key] == "date" ) ? $this->getFieldId($key) : "",$value);
			$rpl['fieldname'] = $beautynames[$key]; 
			
			$content .= $view->getTemplate('dbase-data-row', $rpl);		
		}
		
		$view->set('content', $content);
		$view->render();
		
		
	}
	
	
	
	
	
	
	
	//Pobierz Identyfikator pola bazy danych na podstawie nazwy
	public function getFieldId($key) {
		global $db;
		$query = $db->MakeQuery("SELECT id FROM fields_def WHERE name='".$key."'");
		if($db->NumRows($query) < 1) {
			return "";
		}
		
		$row = $db->FetchRes($query);
		return $row['id'];
	}
	
	
	
	
	
	
	
	
	//Wyświetl pola bazy danych lub konkretne pole (Wraz z edycją)
	public function viewFields($id) {
		global $db, $view, $site;
		
		if(isset($_POST['fbname'])) {
			if(!is_numeric($id)) {
				exit("Hacking attempt...");
				//log?
			}
			
			$query = "UPDATE fields_def SET name='".$_POST['fname']."', beautyname='".$_POST['fbname']."', type='".$_POST['fieldtype']."' WHERE id='".$id."';";
			
			
			if($db->MakeQuery($query)) {
				$_SESSION['notie'] = "Pomyślnie zedytowano pole!";
				$_SESSION['notietype'] = 1;
			}
			else {
				$_SESSION['notie'] = "Wystąpił błąd przy edycji pola...";
				$_SESSION['notietype'] = 3;
			}
			
			header("Location:".$site->siteurl."data/fields/".$id);
			return;
		}
		
		if(isset($id) && is_numeric($id) && $id>0) {
			//add secondary navigation!
			$query = $db->MakeQuery("SELECT name, beautyname, type FROM fields_def WHERE id='".$id."'");
			$query2 = $db->MakeQuery("SELECT name, value, id, ord type FROM fields_values WHERE field='".$id."' ORDER BY ord ASC");
			
			//what next?
			$content = "";
			
			$row = $db->FetchRes($query);
			$view->AppendSiteTitle('Pole "'.$row['beautyname'].'"');
			$view->set('header', 'Pole "'.$row['beautyname'].'"');
			
			if($this->FieldHasOptions($row['type'])) {
				if($db->NumRows($query2) > 0) {
					while($row2 = $db->FetchRes($query2)) {
						$op['options'] .= $view->getTemplate('dfields-options-row', $row2);
					}
				} else $op['options'] = " ";
				
				$op['fieldid'] = $id;
				$row['options'] = $view->getTemplate('dfields-options', $op);
			}
			
			if(!isset($row['options'])) 
				$row['options'] = "";
			
			$ftypesarr = $this->getFieldsTypeBtName();
			foreach($ftypesarr as $field) {
				if(!$field['name'])
					continue;
					
				$selected = ($field['name'] == $row['type']) ? "selected" : "";
				$row['fieldtypes'] .= "<option value='".$field['name']."' ".$selected.">".$field['btname']."</option>";
			}
			
			$content .= $view->getTemplate('dfields-edit-form', $row);
			
			$view->set('content', $content);
			$view->render();
			
			return;
		}
		
		$view->AppendSiteTitle('Pola bazy');
		$view->set('header', 'Pola bazy danych');
		$content = "";
		
		$content .= makeNavigation(navigationButton("Dodaj nowe", "{\$siteurl}data/addfield"));
		$btnames = $this->getFieldsTypeBtName();
		
		$query2 = $db->MakeQuery("SELECT id, name FROM fields_categories ORDER BY ord");
		while($r = $db->FetchRes($query2)) {
			$query = $db->MakeQuery("SELECT id, beautyname, type FROM fields_def WHERE cat='".$r['id']."';");
			/*if($db->NumRows($query) < 1) {
				$view->set('content', "Błąd! Nie znaleziono żadnych pól danych...");
				$view->render();
				return;
			}*/
		
			$tc['rows'] = "";
		
			while($row = $db->FetchRes($query)) {
				$row['url'] = $site->siteurl."data/fields/".$row['id'];
				$row['fieldbt'] = $btnames[$row['type']]['btname'];
				$tc['rows'].= $view->getTemplate('ftypes-table-row', $row);
			}	
			
			$tc['name'] = $r['name'];
			
			$rep['tables'] .= $view->getTemplate('ftypes-table', $tc);
		}

		$content .= $view->getTemplate('ftypes-page', $rep);
	
		$view->set('content', $content);
		$view->render();
	}
	
	
	
	
	
	
	
	//Dodawanie nowego pola do bazy danych
	public function AddField() {
		global $db, $view, $site;
		if(!isset($_POST['fbname'])) {
			$view->AppendSiteTitle("Dodaj pole");
			$view->set("header", "Dodaj Pole");
			$content = "";
			
			$ftypesarr = $this->getFieldsTypeBtName();
			foreach($ftypesarr as $field) {
				if(!$field['name'])
					continue;
					
				$selected = ($field['name'] == $row['type']) ? "selected" : "";
				$row['fieldtypes'] .= "<option value='".$field['name']."' ".$selected.">".$field['btname']."</option>";
			}
			
			$content .= $view->getTemplate('dfields-add-form', $row);
			$view->set("content", $content);
			$view->render();
			
			return;
		}
		
		$name = $_POST['fbname'];
		
		$stname = strtolower($name);
		$stname = rtrim($stname, ' ');
		$stname = ltrim($stname, ' ');
		$stname = iconv('utf-8', 'ascii//TRANSLIT', $stname);
		$stname = preg_replace('/[^A-Za-z0-9\-]/', '', $stname);
		
		$query = $db->MakeQuery("SELECT COUNT(*) AS num FROM fields_def WHERE name='".$stname."' OR beautyname='".$name."';");
		$r = $db->FetchRes($query);
		if($r['num'] > 0) {
			$_SESSION['notie'] = "W bazie danych jest już pole o takiej nazwie!";
			$_SESSION['notietype'] = 3;
			header("Location:".$site->siteurl."data/addfield");
			
			return;
		} 
		
		$query = $db->MakeQuery("INSERT INTO fields_def (name, beautyname, type) VALUES ('".$stname."', '".$name."', '".$_POST['fieldtype']."');");
		
		//EDYTUJ GLOWNA BAZE - BARDZO WAŻNE!!! ALTER TABLE pathfinders
		
		if($query) {
			$_SESSION['notie'] = "Pomyślnie dodano nowe pole do bazy danych! Teraz możesz je edytować.";
			$_SESSION['notietype'] = 1;
			header("Location:".$site->siteurl."data/fields/".$db->LastId());
		}
	}
	
	public function ShowCategories($id) {
		global $db, $view, $site;
		if($id != 0 && is_numeric($id)) {
			//EDIT A CATEGORY
			//GET A CATEGORY NAME FIRST!!!
			$view->AppendSiteTitle("Kategorie Pól");
			$view->set('header', "Kategorie Pól");
		
		
			$content = "";
		
		
		
			$view->set("content", $content);
			return;
		}
		
		$view->AppendSiteTitle("Kategorie Pól");
		$view->set('header', "Kategorie Pól");
		
		$content = "";
		
		
		
		
		
		
		
		
		$view->set("content", $content);
		
	}
	
	
	
	
	
	
	
	//Sprawdzenie czy pole ma opcje (np. lista rozwijana), atrybut "values" w JSON
	public function FieldHasOptions($fld) {
		$dir = scandir("functions/datatypes/json");
		$ret = array();
		
		foreach($dir as $nom) {
			if($nom != ".." && $nom != "." && !is_dir("functions/datatypes/json/".$nom)) {
				$cont = file_get_contents("functions/datatypes/json/".$nom, true);
				$obj = json_decode($cont);
				
				if($obj === NULL) {
					//error in JSON ?
					//do something - log it
					
					return 0;
				}
					
				foreach($obj->field as $fd) {
					$ret[$fd->name]['values'] = $fd->values;
				}
			}	
		}
		
		return $ret[$fld]['values'];
	}
	
	
	
	
	
	
	
	//Pobierz nazwy i piękne nazwy wszystkich pól z bazy danych
	public function getFieldsTypeBtName() {
		$dir = scandir("functions/datatypes/json");
		$i = 0;
		
		$ret = array();
		
		foreach($dir as $nom) {
			if($nom != ".." && $nom != "." && !is_dir("functions/datatypes/json/".$nom)) {
				$cont = file_get_contents("functions/datatypes/json/".$nom, true);
				$obj = json_decode($cont);
				foreach($obj->field as $fd) {
					$ret[$fd->name]['name'] = $fd->name;
					$ret[$fd->name]['btname'] = $fd->btname;
				}
				$i++;
			}	
		}
		if($i==0)
			exit("<b>Internal critical program error!</b><br />No fields configuration found...");
		
		return $ret;
	}
	
	
	
	
	//Pobierz nazwy funkcji dodawania i wyświetlania pól bazy danych
	public function getFieldsFunctions() {
		$dir = scandir("functions/datatypes/json");
		$i=0;
		
		$ret = array();
		
		foreach($dir as $nom) {
			if($nom != ".." && $nom != "." && !is_dir("functions/datatypes/json/".$nom)) {
				$cont = file_get_contents("functions/datatypes/json/".$nom, true);
				$obj = json_decode($cont);
				foreach($obj->field as $fd) {
					$ret[$fd->name]['load'] = $fd->load;
					$ret[$fd->name]['input'] = $fd->input;
				}
				$i++;
			}	
		}
		
		if($i==0)
			exit("<b>Internal critical program error!</b><br />No fields configuration found...");

		return $ret;
	}
	
	
	
	
	
	//Pobierz nazwę i typ pól bazy danych
	public function loadFieldTypes() {
		global $db;
		$arr = array();
		$query = $db->MakeQuery("SELECT name, type FROM fields_def");
		while($res = $db->FetchRes($query)) {
			$arr[$res['name']] = $res['type'];
		}
		
		return $arr;
	}
	
	
	
	
	//Załaduj beautyname (Piękne nazwy) pól
	public function loadFieldBeautyNames() {
		global $db;
		$arr = array();
		$query = $db->MakeQuery("SELECT name, beautyname FROM fields_def");
		while($res = $db->FetchRes($query)) {
			$arr[$res['name']] = $res['beautyname'];
		}
		
		return $arr;
	}
	
	
	
	
	
	//Załaduj do programu wszystkie funkcje pól bazy danych
	public function LoadDatabaseFieldTypes() {
		$dir = scandir("functions/datatypes");
		foreach($dir as $nom) {
			if($nom != ".." && $nom != "." && !is_dir("functions/datatypes/".$nom))
				include("functions/datatypes/".$nom);
		}
	}
}

