<?php

class model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = new database();
	}
	
	public function encrypt($string)
	{
		return md5($string);
	}

	public function getUser($user)
	{
		return $this->db->fetchquery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$user."'");
	}
	
	public function getUserPass($user, $pass)
	{
		return $this->db->fetchquery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$user."' AND Wachtwoord = '".$pass."'");
	}

	public function validateSession($data)
	{
		$result = $this->db->fetchquery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$data['user']."' AND Wachtwoord = '".$data['pass']."'");
		if(!empty($result)) return true;
		return false;
	}        
}

?>