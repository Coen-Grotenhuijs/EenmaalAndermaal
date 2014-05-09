<?php

class veilingModel extends model
{
        public function getBiedingen($veiling)
        {
                $data = $this->db->fetchQueryAll("SELECT TOP 5 FROM Bod WHERE Voorwerpnummer = ".$veiling);
                return $data;
        }
        
        public function getAlleBiedingen($veiling)
        {
                $data = $this->db->fetchQueryAll("SELECT * FROM Bod WHERE Voorwerpnummer = ".$veiling);
                return $data;
        }
        
        public function getInfo($veiling)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Voorwerp WHERE Voorwerpnummer = ".$veiling);
                return $data;
        }
}

?>