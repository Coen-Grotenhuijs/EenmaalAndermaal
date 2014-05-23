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
                                                                Activatiecode)
                                                VALUES (        '".$data['reg_gebruikersnaam']."',
                                                                '".$data['reg_voornaam']."',
                                                                '".$data['reg_tussenvoegsel']."',
                                                                '".$data['reg_achternaam']."',
                                                                '".$data['reg_straat']." ".$data['reg_huisnummer']."',
                                                                '',
                                                                '".$data['reg_postcode']."',
                                                                '".$data['reg_plaatsnaam']."',
                                                                '".$data['reg_land']."',
                                                                '".$data['reg_geboortedatum']."',
                                                                '".$data['reg_email']."',
                                                                '".$data['reg_wachtwoord']."',
                                                                1,
                                                                '".$data['reg_antwoord']."',
                                                                0,
                                                                '".$code."')");
        }
        
        public function getVragen()
        {
                return $this->db->fetchQueryAll("SELECT * FROM Vraag");
        }
}

?>