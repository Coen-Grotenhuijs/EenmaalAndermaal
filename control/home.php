<?php

class homeControl extends control
{
	public function run()
	{
		$this->loadView('home/home','content');
                $this->replaceView('title','Home');
                
                Timer::resetCounter();
                
                $advertenties = $this->homeModel->getAdvertenties();
                foreach($advertenties as $key=>$value)
                {
                        $file = empty($value['Filenaam']) ? 'empty.jpg' : $value['Filenaam'];
                        $maxbod = $this->homeModel->getHoogsteBod($value['Voorwerpnummer']);
                        $prijs = ($maxbod==0) ? $value['Startprijs'] : $maxbod;
                        $this->loadView('home/advertentie','next_advertentie');
                        $this->replaceView('advertentie_image',$file);
                        $this->replaceView('advertentie_tag',$value['Titel']);
                        $this->replaceView('advertentie_prijs',$prijs);
                        $this->replaceView('advertentie_nummer', $value['Voorwerpnummer']);
                        $timer = new Timer();
                        $time = $timer->getTimestamp($value['Looptijdeindedag'], $value['Looptijdeindetijdstip']);
                        $this->replaceView('timer_class',$timer->setTimer($time));
                }
                
                if(empty($advertenties))
                {
                        $this->loadView('home/geenadvertenties', 'next_advertentie');
                }
                $this->replaceView('next_advertentie','');

                $suggesties = $this->homeModel->getSuggesties();
                foreach($suggesties as $key=>$value)
                {
                        $file = empty($value['Filenaam']) ? 'empty.jpg' : $value['Filenaam'];
                        $maxbod = $this->homeModel->getHoogsteBod($value['Voorwerpnummer']);
                        $prijs = ($maxbod==0) ? $value['Startprijs'] : $maxbod;
                        $this->loadView('home/suggestie','next_suggestie');
                        $this->replaceView('suggestie_image',$file);
                        $this->replaceView('suggestie_tag',$value['Titel']);
                        $this->replaceView('suggestie_prijs',$prijs);
                        $this->replaceView('suggestie_nummer', $value['Voorwerpnummer']);
                        $timer = new Timer();
                        $time = $timer->getTimestamp($value['Looptijdeindedag'], $value['Looptijdeindetijdstip']);
                        $this->replaceView('timer_class',$timer->setTimer($time));
                }

                if(empty($suggesties))
                {
                        $this->loadView('home/geensuggesties', 'next_suggestie');
                }
                $this->replaceView('next_suggestie','');
                
                
                if($this->homeModel->getLoggedIn())
                {
                        $this->loadView('home/loggedin','profiel_inloggen');
                        
                        $profiel = $this->homeModel->getProfielgegevens();

                        $this->replaceView('profiel_naam', $profiel['Voornaam'].' '.(empty($profiel['Tussenvoegsel']) ? '' : $profiel['Tussenvoegsel'].' ').$profiel['Achternaam']);
                        $this->replaceView('profiel_adres', $profiel['Adresregel1'].(trim($profiel['Adresregel2'])=='' ? '' : '<br>'.$profiel['Adresregel2']));
                        $this->replaceView('profiel_postcode', $profiel['Postcode']);
                        $this->replaceView('profiel_woonplaats', $profiel['Plaatsnaam']);
                        $this->replaceView('profiel_land', $profiel['Land']);
                        
                        $this->loadView('home/feedback','feedback');

                        $feedback = $this->homeModel->getScore();

                        $score = 0;

                        foreach($feedback as $key=>$value)
                        {
                                switch($value['Feedbacksoort'])
                                {
                                        case -1:
                                                $this->replaceView('beoordeling_negatief', $value['Aantal']);
                                                $score -= $value['Aantal'];
                                                break;
                                        case 0:
                                                $this->replaceView('beoordeling_neutraal', $value['Aantal']);
                                                break;
                                        case 1:
                                                $this->replaceView('beoordeling_positief', $value['Aantal']);
                                                $score += $value['Aantal'];
                                                break;
                                }
                        }
                        $this->replaceView('beoordeling_negatief', 0);
                        $this->replaceView('beoordeling_neutraal', 0);
                        $this->replaceView('beoordeling_positief', 0);
                        $this->replaceView('beoordeling_score', $score);
                }
                else
                {
                        $this->loadView('home/loggedout','profiel_inloggen');
                }
	}
}

?>