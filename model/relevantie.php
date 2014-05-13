<?php

class relevantieModel extends model
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
        
        public function zoek($text, $rubriek, $page, $perPage)
        {
                $rubrieken = $this->getRubriekenInRubriek($rubriek);
                
                $rubriekenString = implode(",",$rubrieken);
                
                $data = $this->db->fetchQueryAll("SELECT * FROM VoorwerpInRubriek INNER JOIN Voorwerp on VoorwerpInRubriek.Voorwerp = Voorwerp.Voorwerpnummer WHERE RubriekOpLaagsteNiveau IN (".$rubriekenString.") AND Titel LIKE '%".$text."%' AND Veilinggesloten = 0");
                
                $return = array();
                
                for($i=($page-1)*$perPage;$i<$page*$perPage && !empty($data[$i]); $i++)
                {
                        $return[] = $data[$i];
                }
                return $return;
        }
}


?>