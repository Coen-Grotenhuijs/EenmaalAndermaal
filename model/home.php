<?php

class homeModel extends model
{
        public function getAdvertenties()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(5) * FROM Voorwerp LEFT JOIN Bestand ON Bestand.Filenaam = (SELECT MIN(Bestand.Filenaam) FROM Bestand WHERE Bestand.Voorwerp = Voorwerp.Voorwerpnummer)");
                shuffle($data);
                return array_slice($data, 0, 4);
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