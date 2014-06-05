<?php

class berichtModel extends model
{
        public function getVerkoper($id)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$id."'");
                return $data;
        }
                
}

?>