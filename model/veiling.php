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
        
        public function getBreadcrumb($veiling)
        {
                $string = '';
                
                // 1 Voorwerp kan in meerdere rubrieken zitten, hoe lossen we dit op?
                $rubriek = $this->db->fetchQuery("SELECT * FROM Voorwerpinrubriek WHERE Voorwerp = ".$veiling);
                $parent = $this->db->fetchQuery("SELECT * FROM Rubriek WHERE Rubrieknummer = ".$rubriek['Rubriekoplaagsteniveau']);
                do
                {
                        $string = $parent['Rubrieknaam'].' > '.$string;
                        $parent = $this->db->fetchQuery("SELECT * FROM Rubriek WHERE Rubrieknummer = ".$parent['Rubriek']);
                } while($parent['Rubriek']!=0);
                $string = $parent['Rubrieknaam'].' > '.$string;
                return $string;
        }
}

?>