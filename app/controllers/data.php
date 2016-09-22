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
		
		global $view, $site, $db;
		$view->AppendSiteTitle('Baza danych');
		$view->set('header', "Baza danych");
		$content = $view->getTemplate("data-welcome-index");
		$content .= $view->getTemplate("data-search");
		
		$query = "SELECT id,imie, nazwisko, active, funkcja, druzyna FROM pathfinders";
		
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
				//$r['url'] = $site->siteurl."data/view/".r['id'];
				$rep['tablecontents'] .= $view->getTemplate("data-table-row", $r);
			}
			
			$table = $view->getTemplate("data-table", $rep);
			$content .= $table;
		}
		
		$view->set('content', $content);
		$view->render();
	}
	
	public function addData() {
		
		
	}
	
	public function editData($id) {
		
	}
	
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
		
		foreach($res as $key => $value) {
			if($key === "id")
				continue;

			$func = $this->getDatabaseFieldType($key);
			$rpl['value'] = $func($value);
			$rpl['fieldname'] = $beautynames[$key]; 
			
			$content .= $view->getTemplate('dbase-data-row', $rpl);		
		}
		
		$view->set('content', $content);
		$view->render();
		
		
	}
	
	public function getDatabaseFieldType($field) {
		return "FieldTextValue"; //temporary
	}
	
	public function loadFieldBeautyNames() {
		global $db;
		$arr = array();
		$query = $db->MakeQuery("SELECT name, beautyname FROM fields_def");
		while($res = $db->FetchRes($query)) {
			$arr[$res['name']] = $res['beautyname'];
		}
		
		return $arr;
	}
	
	public function LoadDatabaseFieldTypes() {
		$dir = scandir("functions/datatypes");
		foreach($dir as $nom) {
			if($nom != ".." && $nom != "." && !is_dir("functions/datatypes/".$nom))
				include_once("functions/datatypes/".$nom);
		}
	}
}

