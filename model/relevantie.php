<?php

class relevantieModel extends model
{
        public function addView($veiling)
        {
                if(empty($_SESSION['bekekenVeilingen']))
                {
                        $_SESSION['bekekenVeilingen'] = array($veiling);
                }
                else
                {
                        if(!in_array($veiling, $_SESSION['bekekenVeilingen']))
                        {
                                $_SESSION['bekekenVeilingen'][] = $veiling;
                                if(count($_SESSION['bekekenVeilingen'])>10)
                                {
                                        array_shift($_SESSION['bekekenVeilingen']);
                                }
                        }
                }
                
                $bestaand = array();
                $data = $this->db->fetchQueryAll("SELECT * FROM Relaties WHERE Voorwerpnummer = ".$veiling);
                foreach($data as $key=>$value)
                {
                        $bestaand[] = $value['GerelateerdeVoorwerpnummer'];
                }
                
                $query = "";
                
                foreach($_SESSION['bekekenVeilingen'] as $key=>$value)
                {
                        if($value!=$veiling)
                        {
                                if(in_array($value, $bestaand))
                                {
                                        $query .= "UPDATE Relaties SET Factor = Factor + 1 WHERE Voorwerpnummer = ".$veiling." AND GerelateerdeVoorwerpnummer = ".$value.";";
                                        $this->db->query("UPDATE Relaties SET Factor = Factor + 1 WHERE Voorwerpnummer = ".$veiling." AND GerelateerdeVoorwerpnummer = ".$value);
                                }
                                else
                                {
                                        $query .= "INSERT INTO Relaties (Voorwerpnummer, GerelateerdeVoorwerpnummer, Factor) VALUES (".$veiling.", ".$value.", 1);";
                                        $this->db->query("INSERT INTO Relaties (Voorwerpnummer, GerelateerdeVoorwerpnummer, Factor) VALUES (".$veiling.", ".$value.", 1)");
                                }
                        }
                }
//                if(!empty($query)) $this->db->query($query);
                
                $data = $this->db->fetchQuery("SELECT * FROM Suggesties WHERE Voorwerpnummer = ".$veiling." AND Gebruikersnaam = '".$this->getCurrentUser()."'");
                if(!empty($data))
                {
                        $this->db->query("UPDATE Suggesties SET Factor = Factor + 10 WHERE Voorwerpnummer = ".$veiling." AND Gebruikersnaam = '".$this->getCurrentUser()."'");
                }
                else
                {
                        $this->db->query("INSERT INTO Suggesties (Voorwerpnummer, Gebruikersnaam, Factor) VALUES (".$veiling.", '".$this->getCurrentUser()."', 10)");
                }
                
        }
        
        public function addSearch($veilingen)
        {
                foreach($veilingen as $key=>$value)
                {
                        $data = $this->db->fetchQuery("SELECT * FROM Suggesties WHERE Voorwerpnummer = ".$value." AND Gebruikersnaam = '".$this->getCurrentUser()."'");
                        if(!empty($data))
                        {
                                $this->db->query("UPDATE Suggesties SET Factor = Factor + 1 WHERE Voorwerpnummer = ".$value." AND Gebruikersnaam = '".$this->getCurrentUser()."'");
                        }
                        else
                        {
                                $this->db->query("INSERT INTO Suggesties (Voorwerpnummer, Gebruikersnaam, Factor) VALUES (".$value.", '".$this->getCurrentUser()."', 1)");
                        }
                }
        }
        
        public function addBid($veiling)
        {
                $data = $this->db->fetchQuery("SELECT * FROM Suggesties WHERE Voorwerpnummer = ".$veiling." AND Gebruikersnaam = '".$this->getCurrentUser()."'");
                if(!empty($data))
                {
                        $this->db->query("UPDATE Suggesties SET Factor = Factor + 100 WHERE Voorwerpnummer = ".$veiling." AND Gebruikersnaam = '".$this->getCurrentUser()."'");
                }
                else
                {
                        $this->db->query("INSERT INTO Suggesties (Voorwerpnummer, Gebruikersnaam, Factor) VALUES (".$veiling.", '".$this->getCurrentUser()."', 100)");
                }
        }
        
        public function getZoekRelevantie($data, $text, $rubriek, $page, $perPage, $start = 0)
        {
                // Waardering door gebruiker per product
                $userrating = array();
                
                // Uiteindelijke waardering product als alle factoren worden meegenomen
		$rating = array();
                
                // Geen zoekresultaat hoeft niet te worden gesorteerd.
		if(empty($data)) return array();
                
                // Voorwerpen die zoekresultaten vormen vaststellen
                $voorwerpNummers = array();
                
                foreach($data as $key=>$value)
                {
                        $voorwerpNummers[$key] = $value['Voorwerpnummer'];
                }
                
                if($this->getCurrentUser() != null)
                {
                        // Verzekeren dat iedere index een waarde toegewezen krijgt
                        foreach($data as $key=>$value)
                        {
                                $userrating[$value['Voorwerpnummer']] = intval($value['Factor']);
                                $rating[$value['Voorwerpnummer']] = $userrating[$value['Voorwerpnummer']];

                                if(!empty($text))
                                {
                                        if(strpos(strtolower($value['Titel']), strtolower($text))!==FALSE)
                                        {
                                                $userrating[$value['Voorwerpnummer']] += 500;
                                        }
                                        if(strpos(strtolower($value['Beschrijving']), strtolower($text))!==FALSE)
                                        {
                                                $userrating[$value['Voorwerpnummer']] += 200;
                                        }
                                }
                        }
                        // Van alle veilingen de waardering ophalen voor deze gebruiker
                        $result_all = $this->db->fetchQueryAll("SELECT *, Voorwerp.Voorwerpnummer AS Voorwerpnummer FROM Voorwerp LEFT JOIN Suggesties ON Suggesties.Voorwerpnummer = Voorwerp.Voorwerpnummer WHERE Suggesties.Gebruikersnaam = '".$this->getCurrentUser()."' OR Suggesties.Gebruikersnaam IS NULL ORDER BY Suggesties.Factor");
                        foreach($result_all as $key=>$value)
                        {
                                if(!empty($userrating[$value['Voorwerpnummer']])) $userrating[$value['Voorwerpnummer']] += intval($value['Factor']/50);
                                else $userrating[$value['Voorwerpnummer']] = intval($value['Factor']/50);
                                $rating[$value['Voorwerpnummer']] = $userrating[$value['Voorwerpnummer']];
                        }

                        // Alle relaties ophalen
                        $in = implode(",", $voorwerpNummers);
                        if(!empty($text))
                        {
                                $result2 = $this->db->fetchQueryAll("SELECT * FROM Relaties WHERE Voorwerpnummer IN (".$in.") AND GerelateerdeVoorwerpnummer IN (".$in.")");
                        }
                        else
                        {
                                $result2 = $this->db->fetchQueryAll("SELECT * FROM Relaties");
                        }

                        // Max factor ophalen
                        $result_max = $this->db->fetchQuery("SELECT MAX(Factor) AS Factor FROM Relaties");
                        $max = $result_max['Factor'];

                        // De waardering van gerelateerde producten optellen bij de reeds vastgestelde waardering
                        foreach($result2 as $key=>$value)
                        {
                                $rating[$value['Voorwerpnummer']] += $value['Factor']/$max*$userrating[$value['GerelateerdeVoorwerpnummer']];
                                $rating[$value['GerelateerdeVoorwerpnummer']] += $value['Factor']/$max*$userrating[$value['Voorwerpnummer']];
                        }
                }
                else
                {
                        // Verzekeren dat iedere index een waarde toegewezen krijgt
                        foreach($data as $key=>$value)
                        {
                                $userrating[$value['Voorwerpnummer']] = 0;
                                $rating[$value['Voorwerpnummer']] = 0;

                                if(!empty($text))
                                {
                                        if(strpos(strtolower($value['Titel']), strtolower($text))!==FALSE)
                                        {
                                                $userrating[$value['Voorwerpnummer']] += 500;
                                        }
                                        if(strpos(strtolower($value['Beschrijving']), strtolower($text))!==FALSE)
                                        {
                                                $userrating[$value['Voorwerpnummer']] += 200;
                                        }
                                }
                        }
                        foreach($userrating as $key=>$value)
                        {
                                $rating[$key] = $value;
                        }
                }
		
                // Sorteren
		arsort($rating);
                
                $rating = array_slice($rating, 0, 100);
                
                // Order query vaststellen
		$i = 1;
		$order = "";
		foreach($rating as $key=>$value)
		{
                        if(in_array($key, $voorwerpNummers))
                        {
                                $order .= "WHEN ".$key." THEN ".$i." ";
                                $i++;
                        }
		}
		
                // Query nogmaals uitvoeren maar nu met de juiste sorteringen, zelfde resultaten
                $voorwerpen = implode(",", $voorwerpNummers);
                if(empty($text))
                {
                        $result = $this->db->fetchQueryAll("SELECT * FROM Voorwerp LEFT JOIN Bestand ON Bestand.Filenaam = (SELECT MIN(Bestand.Filenaam) FROM Bestand WHERE Bestand.Voorwerp = Voorwerp.Voorwerpnummer) ORDER BY CASE Voorwerpnummer ".$order." END");
                }
                else
                {
                        $result = $this->db->fetchQueryAll("SELECT * FROM Voorwerp LEFT JOIN Bestand ON Bestand.Filenaam = (SELECT MIN(Bestand.Filenaam) FROM Bestand WHERE Bestand.Voorwerp = Voorwerp.Voorwerpnummer) WHERE Voorwerpnummer IN (".$voorwerpen.") ORDER BY CASE Voorwerpnummer ".$order." END");
                }
                // Pagina vaststellen
                $start_key = 0;
		foreach($result as $key=>$value)
		{
			if($value['Voorwerpnummer']==$start) $start_key = $key+1;
		}
                
                // Data opslaan in sessie voor gebruik bij scrollen
                $_SESSION['last_voorwerp'] = $result[min($perPage-1,count($result)-1)]['Voorwerpnummer'];
                $_SESSION['zoekresultaten'] = array();
                foreach($result as $key=>$value)
                {
                        $_SESSION['zoekresultaten'][] = $value['Voorwerpnummer'];
                }
                
		// Data teruggeven
                return array_slice($result, ($page-1)*$perPage, $perPage);
        }
        
        public function getAjax()
        {
                $perPage = 10;
                
                $voorwerpen = implode(',', $_SESSION['zoekresultaten']);
                
		$i = 1;
		$order = "";
		foreach($_SESSION['zoekresultaten'] as $key=>$value)
		{
			$order .= "WHEN ".$value." THEN ".$i." ";
			$i++;
		}
                
		$result = $this->db->fetchQueryAll("SELECT * FROM Voorwerp WHERE Voorwerpnummer IN (".$voorwerpen.") ORDER BY CASE Voorwerpnummer ".$order." END");
		
                // Pagina vaststellen
                $start_key = 0;
		foreach($result as $key=>$value)
		{
			if($value['Voorwerpnummer']==$_SESSION['last_voorwerp']) $start_key = $key+1;
		}
                
                if(!empty($_SESSION['last_voorwerp']) && empty($start_key))
                {
                        return array();
                }
                
                // Nieuwe pagina vaststellen
                $_SESSION['last_voorwerp'] = $_SESSION['zoekresultaten'][min($start_key+$perPage, count($_SESSION['zoekresultaten'])-1)];
                        
//                echo $_SESSION['last_voorwerp'];
                
		// Data teruggeven
                return array_slice($result, $start_key, $perPage);
        }

        public function getRelevantie($data, $page, $perPage, $start = 0)
        {
                // Waardering door gebruiker per product
                $userrating = array();
                
                // Uiteindelijke waardering product als alle factoren worden meegenomen
		$rating = array();
                
                // Geen zoekresultaat hoeft niet te worden gesorteerd.
		if(empty($data)) return array();
                
                
                // Verzekeren dat iedere index een waarde toegewezen krijgt
		foreach($data as $key=>$value)
		{
			$userrating[$value['Voorwerpnummer']] = intval($value['Factor']);
			$rating[$value['Voorwerpnummer']] = $userrating[$value['Voorwerpnummer']];
		}
		
                // Van alle veilingen de waardering ophalen voor deze gebruiker
		$result_all = $this->db->fetchQueryAll("SELECT *, Voorwerp.Voorwerpnummer AS Voorwerpnummer FROM Voorwerp INNER JOIN Suggesties ON Suggesties.Voorwerpnummer = Voorwerp.Voorwerpnummer WHERE Suggesties.Gebruikersnaam = '".$this->getCurrentUser()."' ORDER BY Suggesties.Factor");
		foreach($result_all as $key=>$value)
		{
			if(!empty($userrating[$value['Voorwerpnummer']])) $userrating[$value['Voorwerpnummer']] += intval($value['Factor']/10);
			else $userrating[$value['Voorwerpnummer']] = intval($value['Factor']/10);
			$rating[$value['Voorwerpnummer']] = $userrating[$value['Voorwerpnummer']];
		}

                // Alle relaties ophalen
		$result2 = $this->db->fetchQueryAll("SELECT * FROM Relaties");
                
                // De waardering van gerelateerde producten optellen bij de reeds vastgestelde waardering
		foreach($result2 as $key=>$value)
		{
			$rating[$value['Voorwerpnummer']] += $value['Factor']*$userrating[$value['GerelateerdeVoorwerpnummer']];
			$rating[$value['GerelateerdeVoorwerpnummer']] += $value['Factor']*$userrating[$value['Voorwerpnummer']];
		}
		
                // Sorteren
		arsort($rating);
                
                
                // Order query vaststellen
		$i = 1;
		$order = "";
		foreach($rating as $key=>$value)
		{
			$order .= "WHEN ".$key." THEN ".$i." ";
			$i++;
		}
		
                
                // Voorwerpen die zoekresultaten vormen vaststellen
                $voorwerpNummers = array();
                
                foreach($data as $key=>$value)
                {
                        $voorwerpNummers[$key] = $value['Voorwerpnummer'];
                }
                
                // Query nogmaals uitvoeren maar nu met de juiste sorteringen, zelfde resultaten
                $voorwerpen = implode(",", $voorwerpNummers);
		$result = $this->db->fetchQueryAll("SELECT * FROM Voorwerp WHERE Voorwerpnummer IN (".$voorwerpen.") ORDER BY CASE Voorwerpnummer ".$order." END");
		
                // Pagina vaststellen
                $start_key = 0;
		foreach($result as $key=>$value)
		{
			if($value['Voorwerpnummer']==$start) $start_key = $key+1;
		}
                
		// Data teruggeven
                return array_slice($result, ($page-1)*$perPage, $perPage);
        }
        
}


?>