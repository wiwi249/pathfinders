<?php
class test extends Controller {
	function __construct() {
		global $view;
		$view->set('header', 'Testowy kontroler');	
		$view->set('content', 'Testowy kontroler. To znaczy, że program działa!');
		$view->render();
	}
	public function test1($param){
	
	}
}