<?php

class dashboardModel extends model
{
        public function getBiedingen()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP 3 FROM Voorwerp INNER JOIN Bod ON Voorwerp.Voorwerpnummer = Bod.Voorwerpnummer WHERE Bod.Gebruiker = '".$this->getCurrentUser()."'");
                return $data;
        }
        
        public function getVoorwerpen()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP 3 FROM Voorwerp WHERE Verkoper = '".$this->getCurrentUser()."'");
                return $data;
        }
        
        public function getSuggesties()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP 5 FROM Voorwerp");
        }
        
        public function getProfielgegevens()
        {
                $data = $this->db->fetchQuery("SELECT * FROM Gebruiker WHERE Gebruikersnaam = '".$this->getCurrentUser()."'");
                return $data;
        }
        
        public function getScore()
        {
                $data = $this->db->fetchQueryAll("SELECT Feedbacksoort, SUM(*) FROM Feedback WHERE Gebruikersnaam = '".$this->getCurrentUser()."' GROUP BY Feedbacksoort");
                return $data;
        }
}

?>