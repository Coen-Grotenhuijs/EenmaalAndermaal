<?php

class rubriekenModel extends model
{
        public function getRubrieken()
        {
                $rubrieken = $this->db->fetchQueryAll("SELECT * FROM Rubriek");
                $data = $this->rubriek(-1,0, $rubrieken);
                return $data;
        }
        
        public function rubriek($rubriek, $diepte, $rubrieken)
        {
                $array = array();
                $subs = $this->getSubs($rubriek, $rubrieken);
                if(empty($subs))
                {
                        return;
                }
                else
                {
                        foreach($subs as $key=>$value)
                        {
                                $array[] = array('Naam'=>str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $diepte).$value['Rubrieknaam'],'Nummer'=>$value['Rubrieknummer']);
                                $subsub = $this->rubriek($value['Rubrieknummer'], $diepte+1, $rubrieken);
                                if(empty($subsub)) continue;
                                foreach($subsub as $subkey=>$subvalue)
                                {
                                        $array[] = $subvalue;
                                }
                        }
                }
                return $array;
        }
        
        public function getSubs($rubriek, $rubrieken)
        {
                $data = array();
                foreach($rubrieken as $key=>$value)
                {
                        if($value['Rubriek']==$rubriek)
                        {
                                $data[] = $value;
                        }
                }
                return $data;
        }
        
        public function getRubriekenInRubriek($rubriek)
        {
                $rubrieken = $this->db->fetchQueryAll("SELECT * FROM Rubriek ORDER BY Volgnr ASC");
                $data = $this->rubriekInRubriek($rubriek, $rubrieken);
                return $data;
        }
        
        public function rubriekInRubriek($rubriek, $rubrieken)
        {
                $array = array();
                $subs = $this->getSubs($rubriek, $rubrieken);
                if(empty($subs))
                {
                        return array($rubriek);
                }
                else
                {
                        foreach($subs as $key=>$value)
                        {
                                $subsub = $this->rubriekInRubriek($value['Rubrieknummer'], $rubrieken);
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
                
                $data = $this->db->fetchQueryAll("SELECT *, Suggesties.Factor AS Factor, VoorwerpInRubriek.Voorwerp AS Voorwerpnummer FROM VoorwerpInRubriek INNER JOIN Voorwerp on VoorwerpInRubriek.Voorwerp = Voorwerp.Voorwerpnummer LEFT JOIN Suggesties ON Suggesties.Voorwerpnummer = Voorwerp.Voorwerpnummer WHERE VoorwerpInRubriek.RubriekOpLaagsteNiveau IN (".$rubriekenString.") AND Titel LIKE '%".$text."%'");// AND (Titel LIKE '%".$text."%' OR Beschrijving LIKE '%".$text."%') AND Veilinggesloten = 0");
                
                return $data;
 
        }
        
        public function getHoogsteBod($veiling)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Bod WHERE Voorwerp = ".$veiling." ORDER BY Bodbedrag DESC");
                return $data['Bodbedrag'];
        }
        
        public function getRubriekenArray()
        {
                $rubrieken = $this->db->fetchQueryAll("SELECT * FROM Rubriek");
                $data = $this->rubriekArray(-1, $rubrieken);
                return $data;
        }
        
        public function rubriekArray($rubriek, $rubrieken)
        {
                $array = array();
                $subs = $this->getSubs($rubriek, $rubrieken);
                if(empty($subs))
                {
                        return;
                }
                else
                {
                        foreach($subs as $key=>$value)
                        {
                                $subsub = $this->rubriekArray($value['Rubrieknummer'], $rubrieken);
                                $array[] = array('Naam'=>$value['Rubrieknaam'],'Nummer'=>$value['Rubrieknummer'], 'Subs'=>$subsub);
                        }
                }
                return $array;
        }

}


?>