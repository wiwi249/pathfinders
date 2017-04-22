<?php
class View {
	private $templateset = array();
	private $templateInfo = array();
	
	function __construct() {
		// Check if there are any templates in folder
		$dirs = 0;
		$dir = scandir("../content/themes");
		foreach($dir as $nom) {			
			if(is_dir("../content/themes/$nom") && ($nom != '..' && $nom != '.'))
				$dirs++;
		}
		if(!$dirs) {
			echo "ERROR! No templates were found. Exiting.";
			exit;
		}
		
		// Load default site content
		global $site, $user;
		$this->templateInfo['currentTemplate'] = 'template1';
		$this->templateInfo['currentTemplatePath'] = $site->siteurl.'content/themes/'.$this->templateInfo['currentTemplate'].'/';
		$this->set('title', $site->get_site_info(title));
		$header['meta-charset'] = $site->get_site_info('meta-charset');
		$header['siteurl'] = $site->siteurl;
		$this->set('siteurl', $site->siteurl);
		$this->set('headerinclude', $this->getTemplate('headerinclude', $header));
		$this->set('imie', $user->GetUserName());
		$this->set('me', $user->GetUserNick());
		$this->set('nazwisko', $user->GetUserSurname());
		$this->set('CURTEMPLATE_PATH', $site->siteurl.'content/themes/'.$this->templateInfo['currentTemplate'].'/');
	}
	
	public function AppendSiteTitle($title) {
		if(empty($title))
			return;
		
		global $site;
		$this->set('title', $title.' | '.$site->get_site_info('title'));
	}
	
	public function render($template = 'index') {
		global $view, $db, $site;
		$render = file_get_contents("../content/themes/".$this->templateInfo['currentTemplate']."/".$template.".php");
		$render = $this->renderTemplate($render, $this->templateset);
		eval("?> ".$render." <?php ");
	}
	
	public function set($key, $value) {
		$this->templateset[$key] = $value;
	}
	
	function getTemplate($template, $values = array(), $renderDefaults = true) {
		global $db, $content, $site;
		if($renderDefaults) {		
			$values['siteurl'] = $site->siteurl;
			$values['CURTEMPLATE_PATH'] = $this->templateInfo['currentTemplatePath'];
		}
		$query = $db->MakeQuery("SELECT * FROM templates WHERE name='".$template."';");
		$results = $db->FetchRes($query);
		
		return $this->renderTemplate($results['content'], $values);
	}
	
	function renderTemplate($template, $values=array()) {
		foreach($values as $key=>$value) {
			$replace = "{\$$key}";
			$template = str_replace($replace, $value, $template);
		}
		return $template;
	}
	
	function getCurrentTemplateName() {
		return $this->templateInfo['currentTemplate'];
	}
}