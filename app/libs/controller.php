<?php
class Controller {
	protected $rendermode = 'index';
	
	function render($rendermode) {
		echo $rendermode;
	}
}