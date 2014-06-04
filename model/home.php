<?php

class homeModel extends model
{
        public function getAdvertenties()
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(5) * FROM Voorwerp");
                shuffle($data);
                return array_slice($data, 0, 4);
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