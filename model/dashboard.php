<?php

class dashboardModel extends model
{
        public function getBiedingen()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(3) Bestand.Filenaam, Voorwerp.Voorwerpnummer, Voorwerp.Startprijs, Voorwerp.Titel, Voorwerp.Looptijdbegintijdstip, Voorwerp.Looptijdeindedag FROM Voorwerp LEFT JOIN Bestand ON Bestand.Filenaam = (SELECT MIN(Bestand.Filenaam) FROM Bestand WHERE Bestand.Voorwerp = Voorwerp.Voorwerpnummer) INNER JOIN Bod ON Voorwerp.Voorwerpnummer = Bod.Voorwerp WHERE Bod.Gebruiker = '".$this->getCurrentUser()."' GROUP BY Voorwerp.Voorwerpnummer, Voorwerp.Startprijs, Voorwerp.Titel, Voorwerp.Looptijdbegintijdstip, Voorwerp.Looptijdeindedag, Bestand.Filenaam");
                return $data;
        }
        
        public function getVoorwerpen()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(3) * FROM Voorwerp LEFT JOIN Bestand ON Bestand.Filenaam = (SELECT MIN(Bestand.Filenaam) FROM Bestand WHERE Bestand.Voorwerp = Voorwerp.Voorwerpnummer) WHERE Verkoper = '".$this->getCurrentUser()."'");
                return $data;
        }
        
        public function getSuggesties()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(5) * FROM Voorwerp LEFT JOIN Bestand ON Bestand.Filenaam = (SELECT MIN(Bestand.Filenaam) FROM Bestand WHERE Bestand.Voorwerp = Voorwerp.Voorwerpnummer)");
                return $data;
        }
        
        public function getProfielgegevens()
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$this->getCurrentUser()."'");
                return $data;
        }
        
        public function getScore()
        {
                $data = $this->db->fetchQueryAll("SELECT Feedbacksoort, COUNT(*) AS Aantal FROM Voorwerp INNER JOIN Feedback ON Voorwerp.Voorwerpnummer = Feedback.Voorwerp WHERE Voorwerp.Verkoper = '".$this->getCurrentUser()."' GROUP BY Feedbacksoort");
                return $data;
        }
        
        public function getHoogsteBod($veiling)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Bod WHERE Voorwerp = ".$veiling." ORDER BY Bodbedrag DESC");
                return $data['Bodbedrag'];
        }
}

?>