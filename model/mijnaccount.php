<?php

class mijnaccountModel extends model
{
        public function getProfielgegevens()
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$this->getCurrentUser()."'");
                return $data;
        }
        
        public function update($data)
        {
                $this->db->fetchQuery("UPDATE Gebruiker SET Voornaam = '".$data['voornaam']."',
                                                            Tussenvoegsel = '".$data['tussenvoegsel']."',
                                                            Achternaam = '".$data['achternaam']."',
                                                            Adresregel1 = '".$data['adresregel1']."',
                                                            Adresregel2 = '".$data['adresregel2']."',
                                                            Postcode = '".$data['postcode']."',
                                                            Plaatsnaam = '".$data['plaatsnaam']."',
                                                            Land = '".$data['land']."',
                                                            Geboortedag = '".$data['geboortedag']."'
                                                        WHERE Gebruikersnaam = '".$this->getCurrentUser()."'");
                
                $this->db->query("UPDATE Gebruikerstelefoon SET Telefoon = '".$data['tel']."' WHERE Gebruiker = '".$this->getCurrentUser()."'");
        }
        
        public function updatePass($data)
        {
                $this->db->fetchQuery("UPDATE Gebruiker SET Wachtwoord = '".$this->encrypt($data['wachtwoord'])."' WHERE Gebruikersnaam = '".$this->getCurrentUser()."'");
        }
        
        public function getPass()
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$this->getCurrentUser()."'");
                return $data['Wachtwoord'];
        }
        
        public function getTelefoonnummer()
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruikerstelefoon WHERE Gebruiker = '".$this->getCurrentUser()."'");
                return $data['Telefoon'];
        }
}

?>