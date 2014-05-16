<?php

class rubriekenModel extends model
{
        public function getRubrieken()
        {
                $data = $this->rubriek(0,0);
                return $data;
        }
        
        public function rubriek($rubriek, $diepte)
        {
                $array = array();
                $subs = $this->db->fetchQueryAll("SELECT * FROM Rubriek WHERE Rubriek = ".$rubriek);
                if(empty($subs))
                {
                        return;
                }
                else
                {
                        foreach($subs as $key=>$value)
                        {
                                $array[] = array('Naam'=>str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $diepte).$value['Rubrieknaam'],'Nummer'=>$value['Rubrieknummer']);
                                $subsub = $this->rubriek($value['Rubrieknummer'], $diepte+1);
                                if(empty($subsub)) continue;
                                foreach($subsub as $subkey=>$subvalue)
                                {
                                        $array[] = $subvalue;
                                }
                        }
                }
                return $array;
        }
        
        public function getRubriekenInRubriek($rubriek)
        {
                $data = $this->rubriekInRubriek($rubriek);
                return $data;
        }
        
        public function rubriekInRubriek($rubriek)
        {
                $array = array();
                $subs = $this->db->fetchQueryAll("SELECT * FROM Rubriek WHERE Rubriek = ".$rubriek." ORDER BY Volgnr ASC");
                if(empty($subs))
                {
                        return array($rubriek);
                }
                else
                {
                        foreach($subs as $key=>$value)
                        {
                                $subsub = $this->rubriekInRubriek($value['Rubrieknummer']);
                                if(empty($subsub)) continue;
                                foreach($subsub as $subkey=>$subvalue)
                                {
                                        $array[] = $subvalue;
                                }
                        }
                }
                return $array;
        }
        
        public function zoek($text, $rubriek)
        {
                $rubrieken = $this->getRubriekenInRubriek($rubriek);
                
                $rubriekenString = implode(",",$rubrieken);
                
                $data = $this->db->fetchQueryAll("SELECT *, VoorwerpInRubriek.Voorwerp AS Voorwerpnummer FROM VoorwerpInRubriek INNER JOIN Voorwerp on VoorwerpInRubriek.Voorwerp = Voorwerp.Voorwerpnummer LEFT JOIN Suggesties ON Suggesties.Voorwerpnummer = Voorwerp.Voorwerpnummer WHERE RubriekOpLaagsteNiveau IN (".$rubriekenString.") AND (Titel LIKE '%".$text."%' OR Beschrijving LIKE '%".$text."%') AND Veilinggesloten = 0 AND (Suggesties.Gebruikersnaam = '".$this->getCurrentUser()."' OR Suggesties.Gebruikersnaam IS NULL)");
                
                return $data;
 
        }
        
        public function getHoogsteBod($veiling)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Bod WHERE Voorwerp = ".$veiling." ORDER BY Bodbedrag DESC");
                return $data['Bodbedrag'];
        }
        
        public function getRubriekenArray()
        {
                $data = $this->rubriekArray(0);
                return $data;
        }
        
        public function rubriekArray($rubriek)
        {
                $array = array();
                $subs = $this->db->fetchQueryAll("SELECT * FROM Rubriek WHERE Rubriek = ".$rubriek);
                if(empty($subs))
                {
                        return;
                }
                else
                {
                        foreach($subs as $key=>$value)
                        {
                                $subsub = $this->rubriekArray($value['Rubrieknummer']);
                                $array[] = array('Naam'=>$value['Rubrieknaam'],'Nummer'=>$value['Rubrieknummer'], 'Subs'=>$subsub);
                        }
                }
                return $array;
        }

}


?>