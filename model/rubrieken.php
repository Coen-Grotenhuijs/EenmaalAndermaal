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
                                $array[] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $diepte).$value['Rubrieknaam'];
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
}

?>