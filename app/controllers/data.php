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
		
		//for debug
		$content .= $query;
		$content .= navigationButton("GUZIK", $site->siteurl, "", 0);
		
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
	
	public function editData() {
		
	}
}