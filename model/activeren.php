<?php

class activerenModel extends model
{
        public function getUserCode($id, $code)
        {
                $user = $this->getUser($user);
                if($user['Activatiecode']==$code) return true;
                return false;
        }
        
        public function activeer($user)
        {
                $this->db->fetchQuery("UPDATE Gebruiker SET Activatiecode = NULL WHERE Gebruikersnaam = '".$user."'");
        }
                
}

?>