<?php

class User {
	private $imie;
	private $nazwisko;
	private $nick;
	private $group;
	
	public function __construct() {
		if(!$_SESSION['id'] || !$_SESSION['logged'])
			return;
			
		global $db;
		$query = $db->MakeQuery("SELECT * FROM users WHERE id=".$_SESSION['id'].";");
		if($db->NumRows($query) == 0)
			return;
			
		$res = $db->FetchRes($query);
		
		$this->imie = $res['imie'];
		$this->nick = $res['nick'];
		$this->nazwisko = $res['nazwisko'];
		$this->group = $res['group'];
	}
	
	public function getUserName() {
		return $this->imie;
	}
	
	public function getUserSurname() {
		return $this->nazwisko;
	}
	
	public function getUserNick() {
		return $this->nick;
	}
	
	public function getUserGroup() {
		return $this->group;
	}
}