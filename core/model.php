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
		return sha1($string."konijn123");
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
        
        public function getLoggedIn()
        {
                return !empty($_SESSION['user']);
        }
        
        public function getCurrentUser()
        {
                if(!empty($_SESSION['user']))
                {
                        return $_SESSION['user'];
                }
                return;
        }
        
        public function getVerkoper()
        {
                $data = $this->getUser($this->getCurrentUser());
                if($data['Verkoper']==1) return true;
                return false;
        }
}

?>