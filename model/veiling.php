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
        
        public function getMinBod($id)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Bod WHERE Voorwerp = ".$id." ORDER BY Bodbedrag DESC");
                if(!empty($data))
                {
                        return $data['Bodbedrag'];
                }
                $data = $this->db->fetchQuery("SELECT * FROM Voorwerp WHERE Voorwerpnummer = ".$id);
                return $data['Startprijs'];
        }
        
        public function addBod($voorwerp, $bedrag, $gebruiker)
        {
                $this->db->query("INSERT INTO Bod (Voorwerp, Bodbedrag, Gebruiker, Boddag, Bodtijdstip) VALUES (".$voorwerp.", '".$bedrag."','".$gebruiker."','".date('d/n/Y')."','".date('H:i:s')."')");
        }
        
        public function getUserLastBid($id)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Bod WHERE Voorwerp = ".$id." ORDER BY Bodbedrag DESC");
                return $data['Gebruiker'];
        }
        
        public function getOwner($id)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Voorwerp WHERE Voorwerpnummer = ".$id);
                return $data['Verkoper'];
        }
}

?>