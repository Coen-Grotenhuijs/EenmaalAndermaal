<?php

class nieuweveilingModel extends model
{
        public function addVeiling($data)
        {
                if(!in_array($data['looptijd'], array(3,5,7,10)))
                {
                        $data['looptijd'] = 3;
                }
                if(!in_array($data['verzendinstructies'], array('Verzenden', 'Ophalen')))
                {
                        $data['verzendinstructies'] = 'Verzenden';
                }
                $looptijdbegindag = date('d/n/Y');
                $looptijdeindedag = date('d/n/Y', time() + 3600*24*round($data['looptijd']));
                $looptijdbegintijdstip = date('H:i:s');
                
                $id = 
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
                
                $this->db->query("INSERT INTO VoorwerpInRubriek (Voorwerp, RubriekOpLaagsteNiveau) VALUES (".$id.", ".$data['rubriek'].")");
                
                return $id;
        }
        
        public function addBestanden($files, $id)
        {
                foreach($files as $key=>$value)
                {
                        $this->db->fetchQuery("INSERT INTO Bestand (Filenaam, Voorwerp) VALUES ('".$value."', ".$id.")");
                }
        }
        
        
        public function getRubriek($id)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Rubriek WHERE Rubrieknummer = ".round($id));
                if(!empty($data)) return true;
                return false;
        }
        
        public function getVeiling($id)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Voorwerp WHERE Voorwerpnummer = ".$id);
                return $data;
        }
                
}

?>