<?php

class activerenModel extends model
{
        
        public function getCode($user)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$user."'");
                echo $data['Activatiecode'];
        }
        
        public function getUserCode($id, $code)
        {
                $user = $this->getUser($id);
                if($user['Activatiecode']==$code) return true;
                return false;
        }
        
        public function activeer($user)
        {
                $this->db->fetchQuery("UPDATE Gebruiker SET Activatiecode = NULL WHERE Gebruikersnaam = '".$user."'");
        }
                
}

?>