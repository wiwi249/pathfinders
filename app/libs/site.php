<?php
class Site {
	private $siteinfo = array();
	public $siteurl = "http://localhost:81/pathfinders/";
		
	public function __construct($title) {
		$this->siteinfo['title'] = $title;
		$this->siteinfo['meta-charset'] = 'utf-8';
	}
	
	public function get_site_info($info = 'all') {
		if($info != 'all')
			return $this->siteinfo[$info];

		return $this->siteinfo;
	}
	
	public function loadFunctions() {
		$dir = scandir("functions");
		foreach($dir as $nom) {			
			if($nom != ".." && $nom != "." && !is_dir("functions/".$nom))
				include_once("functions/".$nom);
		}
	}
	
	public function loadFunctionFile($type, $func) {
		@include_once("functions/".$type."/".$func);
	}
}