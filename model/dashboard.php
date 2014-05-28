<?php

class dashboardModel extends model
{
        public function getBiedingen()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(3) Voorwerp.Voorwerpnummer, Voorwerp.Startprijs, Voorwerp.Titel, Voorwerp.Looptijdbegintijdstip, Voorwerp.Looptijdeindedag FROM Voorwerp INNER JOIN Bod ON Voorwerp.Voorwerpnummer = Bod.Voorwerp WHERE Bod.Gebruiker = '".$this->getCurrentUser()."' GROUP BY Voorwerp.Voorwerpnummer, Voorwerp.Startprijs, Voorwerp.Titel, Voorwerp.Looptijdbegintijdstip, Voorwerp.Looptijdeindedag");
                return $data;
        }
        
        public function getVoorwerpen()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(3) * FROM Voorwerp WHERE Verkoper = '".$this->getCurrentUser()."'");
                return $data;
        }
        
        public function getSuggesties()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(5) * FROM Voorwerp");
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