<?php

class registrerenModel extends model
{
        public function register($data)
        {
                $code = substr(sha1(rand()), 0, 10);
                $this->db->fetchQuery("INSERT INTO Gebruiker (  Gebruikersnaam,
                                                                Voornaam,
                                                                Tussenvoegsel,
                                                                Achternaam,
                                                                Adresregel1,
                                                                Adresregel2,
                                                                Postcode,
                                                                Plaatsnaam,
                                                                Land,
                                                                Geboortedag,
                                                                Emailadres,
                                                                Wachtwoord,
                                                                Vraag,
                                                                Antwoordtekst,
                                                                Verkoper,
                                                                Activatiecode,
                                                                IsGeblokkeerd)
                                                VALUES (        '".$data['reg_gebruikersnaam']."',
                                                                '".$data['reg_voornaam']."',
                                                                '".$data['reg_tussenvoegsel']."',
                                                                '".$data['reg_achternaam']."',
                                                                '".$data['reg_straat']."',
                                                                '".$data['reg_huisnummer']."',
                                                                '".$data['reg_postcode']."',
                                                                '".$data['reg_woonplaats']."',
                                                                '".$data['reg_land']."',
                                                                '".$data['reg_geboortedatum']."',
                                                                '".$data['reg_email']."',
                                                                '".$this->encrypt($data['reg_wachtwoord'])."',
                                                                '".$data['reg_vraag']."',
                                                                '".$data['reg_antwoord']."',
                                                                0,
                                                                '".$code."',
                                                                0)");
                
                $this->db->query("INSERT INTO Gebruikerstelefoon (Volgnr, Gebruiker, Telefoontype, Telefoon) VALUES (1, '".$data['reg_gebruikersnaam']."','Thuis', '".$data['reg_tel']."')");
                return $code;
        }
        
        public function getVragen()
        {
                return $this->db->fetchQueryAll("SELECT * FROM Vraag");
        }
        
        public function getUserEmail($email)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Emailadres = '".$email."'");
                return isset($data['Emailadres']);
        }
        
        public function getUserExists($gebruikersnaam)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$gebruikersnaam."'");
                return isset($data['Gebruikersnaam']);
        }
}

?>