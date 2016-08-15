<?php

function get_template_element($element) {
	if(empty($element))
		return;
		
	global $view, $site;
	$e = $site->siteurl."content/themes/".$view->getCurrentTemplateName()."/".$element.".php";
	if(file_exists($e));
		$view->render($element);
}