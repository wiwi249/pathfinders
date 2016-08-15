<?php
class Database {
	// database object
	protected $pdo;
	
	// holds number of queries made during page load
	public $queries = 0;
	
	//boolean whether it's connected or not
	var $connected = false;
	
	//Begin SQL connection
	public function __construct() {
		global $database;
		try { 
			$this->pdo = new PDO('mysql:host='.$database['host'].';dbname='.$database['dbase'].';charset=utf8', $database['login'], $database['pass']);
			$this->connected = true;
		}	
		catch(PDOException $ex) {
   			echo "Blad! Nie udalo sie polaczyc z baza danych..."; //DODAĆ WIADOMOŚĆ BŁĘDU!
			$this->connected = false;
			exit;
		}
	}
	
	/* Make a simple SQL query.
	*
	* $query - [string] SQL query
	*
	*/
	public function MakeQuery($query) {
		$this->queries++;
		$ret = $this->pdo->prepare($query);
		$ret->execute();
		return $ret;
	}
	
	/* Fetch a SQL query.
	*
	* $res - result of MakeQuery(), query resource.
	* $type - [PDO] type of fetching (PDO), default PDO::FETCH_ASSOC 
	*
	*/
	public function FetchRes($res, $type = PDO::FETCH_ASSOC) {
		return $res->fetch($type);
	}
	
	/* Totally useless function checking whether the database connection is up or not.
	*
	*/
	public function IsConnected() {
		return $this->connected;
	}
	
	/* Returns a number of rows in SELECT query.
	*
	* $query - result of MakeQuery(), query resource.
	*
	*/
	public function NumRows($query) {
		return $query->rowCount();
	}
	
	public function Esc($string) {
		return addslashes($string);
	}
}