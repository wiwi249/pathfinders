<?php
class test extends Controller {
	function __construct() {
		global $view;
		$view->set('content', 'Testowy kontroler.');
		$view->render();
	}
	public function test1($param){
	
	}
}