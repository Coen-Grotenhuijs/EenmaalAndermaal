<?php

class relevantieModel extends model
{
        public function getZoekRelevantie($data, $text, $rubriek, $page, $perPage, $start = 0)
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
                return array_slice($data, ($page-1)*$perPage, $perPage);
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
                return array_slice($data, ($page-1)*$perPage, $perPage);
        }
        
}


?>