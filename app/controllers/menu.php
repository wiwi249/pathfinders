<?php
class Menu {
	public:
		function __construct($func, $arg) {
			global $db, $site, $user;
			
			$query = $db->MakeQuery("SELECT menu FROM menus WHERE parent IS NULL");
			
		}	
};