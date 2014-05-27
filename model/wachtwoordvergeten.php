<?php

class wachtwoordvergetenModel extends model
{

        public function getVraag($vraag)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Vraag WHERE Vraagnummer = ".intval($vraag));
                return $data['Tekstvraag'];
        }
}

?>