<?php

class veilingModel extends model
{
        public function getBiedingen($veiling)
        {
                $data = $this->db->fetchQueryAll("SELECT TOP(5) * FROM Bod WHERE Voorwerp = ".$veiling." ORDER BY Bodbedrag DESC");
                return $data;
        }
        
        public function getAlleBiedingen($veiling)
        {
                $data = $this->db->fetchQueryAll("SELECT * FROM Bod WHERE Voorwerp = ".$veiling." ORDER BY Bodbedrag DESC");
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
                $parent = $this->db->fetchQuery("SELECT * FROM Rubriek WHERE Rubrieknummer = ".$rubriek['RubriekOpLaagsteNiveau']);
                do
                {
                        if(!empty($string)) $string = '<a href="zoek?rubriek='.$parent['Rubrieknummer'].'">'.$parent['Rubrieknaam'].'</a> > '.$string;
                        else $string = '<a href="zoek?rubriek='.$parent['Rubrieknummer'].'">'.$parent['Rubrieknaam'].'</a>';
                        $parent = $this->db->fetchQuery("SELECT * FROM Rubriek WHERE Rubrieknummer = ".$parent['Rubriek']);
                } while($parent['Rubriek']!=0);
                $string = $string = '<a href="zoek?rubriek='.$parent['Rubrieknummer'].'">'.$parent['Rubrieknaam'].'</a> > '.$string;
                return $string;
        }
        
        public function getVeiling($id)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Voorwerp INNER JOIN Gebruiker ON Gebruiker.Gebruikersnaam = Voorwerp.Verkoper LEFT JOIN Bestand ON Bestand.Voorwerp = Voorwerp.Voorwerpnummer LEFT JOIN Gebruikerstelefoon ON Gebruikerstelefoon.Gebruiker = Voorwerp.Verkoper WHERE Voorwerpnummer = ".$id);
                return $data;
        }
}

?>