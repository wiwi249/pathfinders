<?php
class Templates {
		function __construct($func, $arg) {
			//Other functions settings
			if($func == "edit") {
				$this->edit($arg);
				return;
			}

			global $view, $db, $site;
			$view->AppendSiteTitle("Szablony");
			
			$view->set("header", "Szablony");
			$content = "";
			$content .= navigationButton("Dodaj nowy", $site->siteurl."templates/add", "button-new", 0);
			$query = $db->MakeQuery("SELECT category FROM templates GROUP BY category;");

			if($db->NumRows($query) < 1) {
				$content = "Niedobrze! Brak wyników... a te powinny tu być. <b>Pilnie skontaktuj się z administratorem.</b>";
			}
			
			else {
				$tablecontents = "";
				while($row = $db->FetchRes($query)) {
					$replace["name"] = $row["category"];
					$replace["templates"] = "";
					
					$query2 = $db->MakeQuery("SELECT * FROM templates WHERE category='".$row['category']."';");
					while($row2 = $db->FetchRes($query2)) {
						$replace2["name"] = $row2["name"];
						$replace2["url"] = $site->siteurl."templates/edit/".$row2["id"];
						
						$replace["templates"] .= $view->getTemplate("templates-table-row", $replace2);
					}
					$tablecontents .= $view->getTemplate("templates-table-category", $replace);
				}	
				$replace["tablecontents"] = $tablecontents;
				$content .= $view->getTemplate("templates-table", $replace);
			}
			
			
			
			$view->set('content', $content);
			$view->render();
		}
		
		function edit($id) {
			global $view, $db, $site;
			if(isset($_POST['content'])) {
				if(!is_numeric($id)) {
					$_SESSION['notie'] = "Nieprawidłowy identyfikator szablonu!";
					$_SESSION['notietype'] = 3;
					header("Location:".$site->siteurl."templates");
					return;
				}
				if($db->MakeQuery("UPDATE templates SET content='".$db->Esc($_POST['content'])."' WHERE id='".$id."';")) {
					$_SESSION['notie'] = "Pomyślnie edytowano szablon!";
					$_SESSION['notietype'] = 1;
				}
				else {
					$_SESSION['notie'] = "Wystąpił błąd przy zapisywaniu...";
					$_SESSION['notietype'] = 3;
				}
				
				header("Location:".$site->siteurl."templates");
				return;
			}
			$view->AppendSiteTitle("Edytuj szablon");
			
			$view->set("header", "Edytuj szablon");
			
			$query = $db->MakeQuery("SELECT content FROM templates WHERE id='".$id."';");
			$row = $db->FetchRes($query);
			$replace["content"] = htmlentities($row["content"]);
			$content = $view->getTemplate("templates-edit", $replace);
			
			$view->set('content', $content);
			$view->render();
		}
}