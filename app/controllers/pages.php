<?php
class Page extends Controller {
	public function __construct($page) {
		global $db, $view;
		$query = $db->MakeQuery("SELECT * FROM cms_pages WHERE url='".$page."';");
		$results = $db->FetchRes($query);
		
		$view->set('content', $view->getTemplate('page_singlepage', $results));
		$view->render();
	}
}