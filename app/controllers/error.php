<?php
class Error extends Controller {
	function __construct() {
		global $view;
		$view->AppendSiteTitle('Błąd!');
		$view->set('header', 'Błąd!');
		$view->set('content', '<h4>Błąd - nie znaleziono strony!</h4><p>Strona której szukasz nie została niestety odnaleziona. Upewnij się, że podany przez Ciebie adres jest prawidłowy.');
		$view->render();
	}
}