<?php

class sessionModel extends model
{
	public function getUser($user)
	{
		return $this->db->fetchquery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$user."'");
	}
	
	public function getUserPass($user, $pass)
	{
		return $this->db->fetchquery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$user."' AND Wachtwoord = '".$this->encrypt($pass)."'");
	}

	public function validateSession($data)
	{
		$result = $this->db->fetchquery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$data['user']."'");
                if($result['Wachtwoord']==$data['pass']) return true;
		return false;
	}
        
        public function getBlocked($user)
        {
                $result = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$user."'");
                if($result['IsGeblokkeerd']==0) return false;
                return true;
        }
        
        public function getActivated($user)
        {
                $result = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$user."'");
                if(empty($result['Activatiecode'])) return true;
                return false;
        }
                
}

?>