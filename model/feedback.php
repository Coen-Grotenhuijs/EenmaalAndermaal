<?php

class feedbackModel extends model
{
        public function getKoperVerkoperGebruiker($voorwerp)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Voorwerp WHERE (Koper = '".$this->getCurrentUser()."' OR Verkoper = '".$this->getCurrentUser()."')AND Voorwerpnummer = ".$voorwerp);
                return $data;
        }
                
        public function getFeedback($voorwerp)
        {
//                $data = $this->db->fetchQuery("SELECT * FROM Feedback WHERE Voorwerp = ".$voorwerp." AND ");
                return $data;
        }
}

?>