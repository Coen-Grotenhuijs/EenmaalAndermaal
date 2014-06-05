<?php

class nieuweveilingModel extends model
{
        public function addVeiling($data)
        {
                $looptijdbegindag = date('d/n/Y');
                $looptijdeindedag = date('d/n/Y', time() + 3600*24*$data['looptijd']);
                $looptijdbegintijdstip = date('H:i:s');
                
                $data = 
                $this->db->insertGetId("INSERT INTO Voorwerp (  Titel,
                                                                Beschrijving,
                                                                Plaatsnaam,
                                                                Land,
                                                                Looptijd,
                                                                Verzendinstructies,
                                                                Verzendkosten,
                                                                Startprijs,
                                                                Betalingswijze,
                                                                Betalinginstructie,
                                                                Verkoper,
                                                                Looptijdeindedag,
                                                                Looptijdbegintijdstip,
                                                                Looptijdbegindag)
                                                VALUES (        '".$data['voorwerpnaam']."',
                                                                '".$data['beschrijving']."',
                                                                '".$data['plaatsnaam']."',
                                                                '".$data['land']."',
                                                                '".$data['looptijd']."',
                                                                '".$data['verzendinstructies']."',
                                                                ".$data['verzendkosten'].",
                                                                ".$data['startprijs'].",
                                                                '".$data['betalingswijze']."',
                                                                '".$data['betalingsinstructies']."',
                                                                '".$this->getCurrentUser()."',
                                                                '".$looptijdeindedag."',
                                                                '".$looptijdbegintijdstip."',
                                                                '".$looptijdbegindag."')");
                return $data;
        }
        
        public function addBestanden($files, $id)
        {
                foreach($files as $key=>$value)
                {
                        $this->db->fetchQuery("INSERT INTO Bestand (Filenaam, Voorwerp) VALUES ('".$value."', ".$id.")");
                }
        }
                
}

?>