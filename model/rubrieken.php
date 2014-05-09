<?php

class veilingModel extends model
{
        public function getRubrieken()
        {
                $data = $this->rubriek(0,0);
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
                                $array[] = str_repeat('-', $diepte).$value['Rubrieknaam'];
                                $subsub = $this->rubriek($value['Rubrieknummer'], $diepte+1);
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